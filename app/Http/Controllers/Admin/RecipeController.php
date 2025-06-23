<?php

namespace App\Http\Controllers\Admin;

use App\Filters\RecipeFilter;
use App\GlobalConstants;
use App\Http\Requests\CreateRecipeRequest;
use App\Http\Requests\UpdateRecipeRequest;
use App\Models\Recipe;
use App\Models\RecipeIngredients;
use App\Models\RecipeSale;
use Hamcrest\Core\IsNull;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models;
use App\Helpers\Helper;
use App\Models\PriceLevel;
use App\Models\Station;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use App\Imports\SalesImport;
use App\Imports\RecipesImport;
use App\Imports\SalesImportForOtherPriceLevel;
use App\Http\Requests\ImportSalesRequest;
use Maatwebsite\Excel\Facades\Excel;
use Auth;

class RecipeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:create sales'],['only' => ['create', 'store']]);
        $this->middleware(['permission:edit sales'],['only' =>['edit', 'update']]);
        $this->middleware(['permission:delete sales'],['only' => 'destroy']);
        $this->middleware(['permission:list sales'],['only' => 'index']);
        $this->middleware(['permission:sales upload via file'],['only' => 'showImportSales']);
    }
    public function index(Request $request,RecipeFilter $filter)
    {
        $branch_id = session()->get('branch_id');
        $period_id = session()->get('period_id');

        $stations = Station::where('branch_id', $branch_id)->get();
        if(isset($request['station-id'])){
            $station_id = $request['station-id'];
        }else{
            $station = Station::where('branch_id', $branch_id)->first();
            $station_id = $station->id ?? null;
        }
        
        // $price_levels = GlobalConstants::PRICE_LEVELS;
        $price_levels=PriceLevel::where('station_id',$station_id)
                                ->where('client_id',$branch_id)
                                ->where('period_id', $period_id)
                                ->get();
        $price_level_id=0;

        if(isset($request['price-level-id'])){
            $price_level_id = $request['price-level-id'];
        }else{
            //get price level id from db if exist
            $price_level=PriceLevel::where('station_id',$station_id)
                                    ->where('client_id',$branch_id)
                                    ->where('level','Regular')
                                    ->where('period_id', $period_id)
                                    ->get();
           
            if($price_level->count()){
                $price_level_id=$price_level[0]->id;
            }
            else{
                $price_level=PriceLevel::where('station_id',$station_id)
                                        ->where('client_id',$branch_id)
                                        ->where('period_id', $period_id)
                                        ->get();
                if($price_level->count()){
                    $price_level_id=$price_level[0]->id;
                }
               
            }
                       
        }
        
        $recipeQuery = Recipe::query();
        $recipe_ids = $recipeQuery->filter($filter)->where('station_id', $station_id)->pluck('id');
        $sale_recipes = RecipeSale::whereIn('recipe_id', $recipe_ids)->where('price_level_id', $price_level_id)->orderBy('created_at', 'desc')->get();
        $total_qty = RecipeSale::whereIn('recipe_id', $recipe_ids)->where('price_level_id', $price_level_id)->sum('qty');
        $total_revenue = RecipeSale::whereIn('recipe_id', $recipe_ids)->where('price_level_id', $price_level_id)->sum('revenue');
        
        return view('admin.recipe.index', compact('sale_recipes', 'stations', 'price_levels', 'station_id', 'price_level_id', 'total_qty', 'total_revenue'));
    }

    public function create(Request $request)
    {
        $branch_id = session()->get('branch_id');
        $period_id = session()->get('period_id');
        $stations = Station::where('branch_id', $branch_id)->get();
        if(isset($request['station-id'])){
            $station_id = $request['station-id'];
        }else{
            $station = Station::where('branch_id', $branch_id)->first();
            $station_id = $station->id ?? null;
        }

        // $price_levels = GlobalConstants::PRICE_LEVELS;
        $price_levels=PriceLevel::where('station_id',$station_id)
                                ->where('client_id',$branch_id)
                                ->where('period_id', $period_id)
                                ->get();
        $price_level_id=0;

        if(isset($request['price-level-id'])){
            $price_level_id = $request['price-level-id'];
        }else{
            //get price level id from db if exist
            $price_level=PriceLevel::where('station_id',$station_id)
                                    ->where('client_id',$branch_id)
                                    ->where('level','Regular')
                                    ->where('period_id', $period_id)
                                    ->get();
           
            if($price_level->count()){
                $price_level_id=$price_level[0]->id;
            }
        }

        $WEIGHT_UOM = GlobalConstants::WEIGHT_UOM;
        return view('admin.recipe.create', compact('WEIGHT_UOM', 'stations', 'price_levels', 'station_id', 'price_level_id'));
    }

    public function store(CreateRecipeRequest $request)
    { 
        $data = $request->validated();
        $data['branch_id'] = session()->get('branch_id');
        $data['period_id'] = session()->get('period_id');
        $recipe = Recipe::create($data);

        if(isset($request->prices)) {
            foreach ($request->prices as $key => $val) {
                $recipe_sale = new RecipeSale();
                $recipe_sale->recipe_id = $recipe->id;
                $recipe_sale->price_level_id = $key;
                $recipe_sale->price = $val ?? 0;
                $recipe_sale->save();
            }
        }

        foreach($request->item_name as $key => $item_name){
            if($item_name && $request->qty[$key] > 0) {
                $recipe_ingredient = new RecipeIngredients();
                $recipe_ingredient->recipe_id = $recipe->id;
                $recipe_ingredient->item_id = $item_name;
                $recipe_ingredient->qty = $request->qty[$key];
                $recipe_ingredient->package_id = $request->package[$key];
                $recipe_ingredient->uom_text = $request->package_text[$key];
                $recipe_ingredient->save();
            }
        }

        return redirect()
            ->route('sales.index', ['station-id' => $data['station_id']])
            ->with('success', 'Recipe created successfully.');
    }

    public function edit(Request $request, $id)
    {
        $branch_id = session()->get('branch_id'); 
        $stations = Station::where('branch_id', $branch_id)->get();
        $period_id = session()->get('period_id');
        if(isset($request['station-id'])){
            $station_id = $request['station-id'];
        }else{
            $station = Station::where('branch_id', $branch_id)->first();
            $station_id = $station->id ?? null;
        }

        $price_levels = PriceLevel::where('station_id',$station_id)
                                    ->where('client_id',$branch_id)
                                    ->where('period_id', $period_id)
                                    ->get();
        $price_level_id=0;
        if(isset($request['price-level-id'])){
            $price_level_id = $request['price-level-id'];
        }else{
            //get price level id from db if exist
            $price_level=PriceLevel::where('station_id',$station_id)
                                    ->where('client_id',$branch_id)
                                    ->where('level','Regular')
                                    ->where('period_id', $period_id)
                                    ->get();
           
            if($price_level->count()){
                $price_level_id=$price_level[0]->id;
            }
           
        }

        $WEIGHT_UOM = GlobalConstants::WEIGHT_UOM;
        $recipe = Recipe::where('id', $id)->first();
        $recipe_sales = RecipeSale::where('recipe_id', $id)->get();
        $recipe_ingredients = RecipeIngredients::where('recipe_id', $id)->get();
        return view('admin.recipe.edit', compact('recipe', 'recipe_sales', 'recipe_ingredients', 'WEIGHT_UOM', 'stations', 'station_id', 'price_levels', 'price_level_id'));
    }

    public function update(UpdateRecipeRequest $request, $id)
    {
        $data = $request->validated();
        $recipe = Recipe::find($id);
        $recipe->name = $request->name;
        $recipe->plu = $request->plu;
        $recipe->tax = $request->tax;
        $recipe->save();
        
        if(isset($request->prices)) {
            foreach($request->prices as $key => $val) {
                $recipeSale = RecipeSale::updateOrCreate(
                    ['recipe_id' => $recipe->id, 'price_level_id' => $key],
                    ['price' => $val ?? 0]
                );
            }
        }

        if(isset($request->prices)) {
            foreach ($request->prices as $key => $val) {
                $recipe_sale = RecipeSale::where('recipe_id', $recipe->id)->where('price_level_id', $key)->first();
                $recipe_sale->price = $val ?? 0;
                $recipe_sale->revenue = $recipe_sale->price * $recipe_sale->qty;
                $recipe_sale->save();
            }
        }

        RecipeIngredients::where('recipe_id', $recipe->id)->delete();
        foreach($request->item_name as $key => $item_name){
            if($item_name && $request->qty[$key] > 0) {
                $recipe_ingredient = new RecipeIngredients();
                $recipe_ingredient->recipe_id = $recipe->id;
                $recipe_ingredient->item_id = $item_name;
                $recipe_ingredient->qty = $request->qty[$key];
                $recipe_ingredient->package_id = $request->package[$key];
                $recipe_ingredient->uom_text = $request->package_text[$key];
                $recipe_ingredient->save();
            }
        }

        return redirect()
            ->route('sales.index', ['station-id' =>$recipe->station_id ])
            ->with('success', 'Recipe updated successfully.');
    }

    public function updatePrice(Request $request)
    {
        $period_id = session()->get('period_id');

        $period = Models\Period::where('id', 'LIKE', '%' . $period_id . '%')->get()[0];
        // get recipe
        $recipe = Models\Recipe
            ::withCount("sales")
            ->where('id', $request->recipe_id)->first();

        // get sale with period
        $sale = RecipeSale::where([
            'recipe_id' => $recipe->id,
            'period_id' => $period->id,
            'station_id' => $request->station_id,
            'level' => $request->level,
            'type' => $request->type
        ])
            ->first();

        $hasChanges = $sale->price != $request->price;
        $isNew = $recipe->sales_count > 1;

        $sale->price = $request->price;
        $sale->qty_sold = $request->sold;


        // only change status when there are changes in price and if the period is more than 1 else remain
        $recipe->change_status = ($hasChanges && $isNew) ? 1 : $recipe->change_status;

        // no need to commit rollback
        $recipe->save();
        $sale->save();

        return response()->json(['success' => [$hasChanges]]);
    }

    public function checkStation(Request $request)
    {
        $branch_id = session()->get('branch_id');
        $stations = PriceLevel::where('station_id', 'LIKE', '%' . $request['id'] . '%')
            ->where('client_id', $branch_id)->get();
        return $stations;
    }

    public function showImportSales(Request $request){
        $branch_id=session()->get('branch_id');
        
        $station_id=isset($request->station_id) ? $request->station_id : null;
        $price_level_id=isset($request->price_level_id) ? $request->price_level_id : null;
        
        $price_levels=[];
        $stations=Station::where('branch_id',$branch_id)->get();
        
        if($station_id){
            $price_levels=PriceLevel::where('station_id',$station_id)
            ->get();
        }
        else{
            if($stations->count()){
                $price_levels=PriceLevel::where('station_id',$stations[0]->id)
                ->get();
            }
            
        }
        return view('admin.import.sales')->with(compact('stations','price_levels','price_level_id','station_id'));
    }

    public function importSales(ImportSalesRequest $request){
        $period_id = session()->get('period_id');
        $branch_id= session()->get('branch_id');
        $station_id=$request->station_id;
        
        $price_level_id=$request->price_level_id;
               
        Excel::import(new RecipesImport($station_id,$branch_id,$period_id),$request->sales_file);
        Excel::import(new SalesImport($station_id,$price_level_id,$period_id),$request->sales_file);

        $total_price_levels=PriceLevel::where(['station_id'=> $station_id, 'client_id'=> $branch_id])->pluck('id')->toArray();
        if(count($total_price_levels)){
            //get remaining price_level_ids
           
            $remaining_price_level_ids=array_diff($total_price_levels,[$price_level_id]);
            foreach($remaining_price_level_ids as $remaining_price_level_id){
                //insert recipe sale with price 0
                Excel::import(new SalesImportForOtherPriceLevel($station_id,$remaining_price_level_id),$request->sales_file);
            }
            
        }
      
        return redirect()->route('sales_import.index',['station_id'=>$station_id,'price_level_id'=>$price_level_id])->with('success','Successfully imported.');
    }

    public function updateRecipeSales(Request $request){
        $data = $request->all();
        RecipeSale::where('id', $data['id'])
                    ->update([
                        'price' => $data['price'],
                        'qty' => $data['qty'],
                        'revenue' => $data['revenue']
                    ]);
        return response(['message' => 'success']);
    }

    public function destroy($recipe_id){
        RecipeIngredients::where('recipe_id', $recipe_id)->delete();
        RecipeSale::where('recipe_id', $recipe_id)->delete();
        Recipe::where('id', $recipe_id)->delete();
        
        return redirect()
        ->route('sales.index')
        ->with('success', 'Recipe deleted successfully.');
        
    }

    public function getPriceLevel($station_id){
        $client_id =  session()->get('branch_id');
        $period_id = session()->get('period_id');
        $price_levels=PriceLevel::where('station_id',$station_id)
                                ->where('client_id', $client_id)
                                ->where('period_id', $period_id)
                                ->get();
                
        return response([ 'status' => $period_id, 'price_levels' => $price_levels]);
    }
    public function parseData(Request $request){
        $data = [];
        $sale_file = request('sale_file');
        $read_sale_file = fopen($sale_file, "r");
        while(! feof($read_sale_file)) {
            $row_data = fgets($read_sale_file);
            $row_data = str_replace(PHP_EOL, '', $row_data);
            $row_data = $this->parseDataByDelimiter($request->delimiter, $row_data);
            $row_data = $this->convert_from_latin1_to_utf8_recursively($row_data);
            array_push($data, $row_data);
        }
        fclose($read_sale_file);
        return response()->json($data);
    }

    public function parseDataByDelimiter($type, $row_data){
        $data = null;
        switch($type){
            case 'comma':
                $data = explode(',', $row_data);
                break;
            case 'pipe':
                $data = explode('|', $row_data);
                break;
            case 'semicolon':
                $data = explode(';', $row_data);
                break;
            case 'single_space':
                $data = explode(' ', $row_data);
                break;
            case 'tab':
                $data = explode('   ', $row_data);
                break;
            default:
                break;
        }
        return $data;
    }
    public function importData(Request $request){ 
        $result = json_decode($request->getContent(), true);
        $recipe_data = $result['recipe_data'];
        $period_id = session()->get('period_id');
        $response_arr = [];
        foreach($recipe_data as $key => $data){
            $price_levels = PriceLevel::where('station_id', $data['station_id'])
                                    ->where('period_id', $period_id)
                                    ->get();
            $recipe = new Recipe();
            $recipe->name = $data['name'] ?? '';
            $recipe->plu = $data['plu'] ?? '';
            $recipe->user_id = Auth::user()->id;
            $recipe->branch_id = session()->get('branch_id');
            $recipe->period_id = session()->get('period_id');
            $recipe->station_id = $data['station_id'];
            $recipe->save();
            foreach($price_levels as $index => $price_level){
                $recipe_sale = new RecipeSale();
                $recipe_sale->recipe_id = $recipe->id;
                $recipe_sale->price_level_id = $price_level->id;
                $recipe_sale->price = (($data['price_level_id'] == $price_level->id) && (is_int((int)$data['price'])) && ($data['price'] != '') ) ? $data['price'] : 0;
                $recipe_sale->qty = (($data['price_level_id'] == $price_level->id) && (is_int((int)$data['qty'])) && ($data['qty'] != '')) ? $data['qty'] : 0;
                $recipe_sale->revenue = (($data['price_level_id'] == $price_level->id) && (is_int((int)$data['revenue'])) && ($data['revenue'] != ''))? $data['revenue'] : 0;
                if($data['price_level_id'] == $price_level->id){
                    $response_data = [];
                    $response_data['plu'] = $data['plu'];
                    $response_data['name'] = $data['name'];
                    $response_data['price'] = $recipe_sale->price;
                    $response_data['qty'] = $recipe_sale->qty;
                    $response_data['revenue'] = $recipe_sale->revenue;
                    array_push($response_arr, $response_data);
                }
                $recipe_sale->save();
            }
        }
        return response(['status' => true, 'message' => 'recipe store successfully', 'data' => $response_arr ]);
    }
    public static function convert_from_latin1_to_utf8_recursively($dat)
   {
      if (is_string($dat)) {
         return utf8_encode($dat);
      } elseif (is_array($dat)) {
         $ret = [];
         foreach ($dat as $i => $d) $ret[ $i ] = self::convert_from_latin1_to_utf8_recursively($d);

         return $ret;
      } elseif (is_object($dat)) {
         foreach ($dat as $i => $d) $dat->$i = self::convert_from_latin1_to_utf8_recursively($d);

         return $dat;
      } else {
         return $dat;
      }
   }
}
