<?php

namespace App\Http\Controllers\Admin;

use App\GlobalConstants;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePriceLevelRequest;
use App\Models\PriceLevel;
use App\Models\Recipe;
use App\Models\RecipeSale;
use App\Models\Station;
use Illuminate\Http\Request;

class PriceLevelController extends Controller
{
    public function index(Request $request)
    {
        $branch_id = session()->get('branch_id');
        $stations = Station::where('branch_id', $branch_id)->get();
        
        $station_id=isset($request->station_id) ? $request->station_id : ($stations->count() ? $stations[0]->id : null ); 
        
        $types = GlobalConstants::PRICE_LEVEL_TYPE;
        $client_id = session()->get('branch_id');
        
        $period_id = session()->get('period_id');

        $price_level_datas = PriceLevel::where('station_id', $station_id)
                                        ->where('client_id', $branch_id)
                                        ->where('period_id', $period_id)
                                        ->get();
        
        return view('admin.price_level.index', compact('stations', 'types', 'price_level_datas','station_id','client_id','period_id'));
    }

    public function store(CreatePriceLevelRequest $request)
    {   
        $ids=$request->id;
        
        foreach($ids as $index => $id){
            if($id!=null){
                $price_level_data=PriceLevel::find((int)$id);
                $price_level_data->type=$request->type[$index];
                $price_level_data->level=$request->price_level[$index];
                $price_level_data->save();
            }
            else{
                
                $price_level_data=new PriceLevel;
                $price_level_data->station_id=$request->station_id_copy;
                $price_level_data->client_id=$request->client_id;
                $price_level_data->period_id=$request->period_id;
                $price_level_data->level=$request->price_level[$index];
                $price_level_data->type=$request->type[$index];
                $price_level_data->save();

                $recipes = Recipe::where([['station_id', $request->station_id_copy],['period_id', $request->period_id ]])->get();
               
                if($recipes->count() && $index != 0){
                    
                    // $recipe_sale_data = RecipeSale::where('recipe_id', $recipe->id)->first();
                    foreach($recipes as $recipe){
                        //add new recipe sale with 0 price for new created price level and all recipes found in db 
                        $recipe_sale = new RecipeSale();
                        $recipe_sale->recipe_id = $recipe->id;
                        // $recipe_sale->period_id = $request->period_id;
                        // $recipe_sale->station_id = $request->station_id_copy;
                        $recipe_sale->price_level_id = $price_level_data->id;
                        $recipe_sale->price = 0;
                        // $recipe_sale->level = $request->price_level[$index];
                        // $recipe_sale->type = $request->type[$index];
                        // $recipe_sale->tax = $recipe_sale_data->tax;
                        $recipe_sale->save();
                    }
                   
                }
                
            }
        }
       
        return redirect()
            ->route('price_level.index',['station_id' => $request->station_id_copy]);
    }

    public function delete($id){
        
        $price_level=PriceLevel::find($id);
        $station_id=$price_level->station_id;
        $price_level->delete();
        RecipeSale::where([['price_level_id', $id]])->delete();

        return redirect()
            ->route('price_level.index',['station_id' => $station_id]);
    }
    
    public function updateOrCreate(Request $request){
        $price_level_id;
        if($request->price_data_id>0){
            $price_level=PriceLevel::find($request->price_data_id);
            $price_level_id=$price_level->id;
            if($request->has('type')){
                $price_level->type=$request->type;
                $price_level->save();
            }
            if($request->has('price_level')){
                if($request->price_level){
                    $price_level->level=$request->price_level;
                    $price_level->save();
                }
                else{
                    $price_level->level="";
                    $price_level->save();
                }
            }
        }
        return $price_level_id;
    }

    public function getPriceLevels($station_id){
        $client_id =  session()->get('branch_id');
        $period_id = session()->get('period_id');
        $price_levels=PriceLevel::where('station_id',$station_id)->where('client_id', $client_id)->where('period_id', $period_id)->get();
        return response([ 'status' => $period_id, 'price_levels' => $price_levels]);
    }
}

