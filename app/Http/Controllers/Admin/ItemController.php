<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateItemRequest;
use App\Models\Category;
use App\Models\Classes;
use App\Models\Item;
use App\Models\Invoice;
use App\Models\InvoiceDetails;
use App\Models\Quality;
use App\Filters\ItemFilter;
use App\GlobalConstants;
use App\Http\Requests\UpdateItemRequest;
use App\Models\ItemPackage;
use App\Models\ItemSize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class ItemController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:create item'],['only' => ['create', 'store']]);
        $this->middleware(['permission:edit item'],['only' =>['edit', 'update']]);
        $this->middleware(['permission:delete item'],['only' => 'destroy']);
        $this->middleware(['permission:list item'],['only' => 'index']);
        $this->middleware(['permission:create item size'],['only' => ['createSize', 'storeSize']]);
        $this->middleware(['permission:edit item size'],['only' =>['editSize', 'updateSize']]);
        $this->middleware(['permission:delete item size'],['only' => 'deleteSize']);
    }
    public function index(ItemFilter $filter)
    {
        $itemQuery = Item::query();

        $items = $itemQuery->filter($filter)->latest('id')->paginate();

        return view('admin.item.index', compact('items'));
    }

    public function create()
    {
        $units = GlobalConstants::UNITS;
        $COUNTABLE_UNIT_ID = GlobalConstants::COUNTABLE_UNIT_ID;
        $EMPTY_WEIGHT_ID = GlobalConstants::EMPTY_WEIGHT_ID;
        $FULL_WEIGHT_ID = GlobalConstants::FULL_WEIGHT_ID;
        $DENSITY_WEIGHT_ID = GlobalConstants::DENSITY_WEIGHT_ID;
        $DENSITY_UNIT_ID = GlobalConstants::DENSITY_UNIT_ID;
        $branch_id = session()->get('branch_id');
        $period_id = session()->get('period_id');
        [$classes, $categories, $qualities] = $this->getClassesCategoriesAndQualities();
        return view('admin.item.create', compact(
            'classes',
            'categories',
            'qualities',
            'units',
            'branch_id',
            'period_id',
            'COUNTABLE_UNIT_ID',
            'EMPTY_WEIGHT_ID',
            'FULL_WEIGHT_ID',
            'DENSITY_WEIGHT_ID',
            'DENSITY_UNIT_ID'
        ));
    }

    public function store(CreateItemRequest $request)
    {
        $data = $request->validated();
        $item = new Item();
        $item->name = $request->name;
        $item->class_id = $request->class_id;
        $item->category_id = $request->category_id;
        $item->quality_id = $request->quality_id;
        $item->user_id = auth()->user()->id;
        $item->branch_id = session()->get('branch_id');
        $item->period_id = session()->get('period_id');
        $item->save();

        $item_size = new ItemSize();
        $item_size->item_id = $item->id;
        $item_size->countable_unit = $request->countable_unit;
        $item_size->countable_size = $request->countable_unit ? $request->countable_size : '';
        $item_size->empty_weight = $request->empty_weight;
        $item_size->empty_weight_size = $request->empty_weight ? $request->empty_weight_size : '';
        $item_size->full_weight = $request->full_weight;
        $item_size->full_weight_size = $request->full_weight ? $request->full_weight_size : '';
        $item_size->density = $request->density;
        $item_size->density_m_unit = $request->density ? $request->density_m_unit : '';
        $item_size->density_v_unit = $request->density ? $request->density_v_unit : '';
        $item_size->sizeoption = $request->sizeoption ? $request->sizeoption : '';
        $item_size->quantification = $request->quantification;
        //$item_size->barcode = $request->barcode;
        $item_size->package_status = $request->package_status;
        $item_size->save();

        $item_package = new ItemPackage();
        $item_package->item_id = $item->id;
        $item_package->item_size_id = $item_size->id;
        $item_package->qty = 1;
        $item_package->unit_from = $request->unit_from;
        $item_package->unit_to = $request->unit_from;
        $item_package->package_barcode = $request->barcode;
        $item_package->save();

        if ($request->package_status == 'yes') {
            for ($i = 0; $i < count($request->package_name); $i++) {
                $item_package = new ItemPackage();
                $item_package->item_id = $item->id;
                $item_package->item_size_id = $item_size->id;
                $item_package->qty = $request->package_size[$i];
                $item_package->unit_from = $request->unit_from;
                $item_package->unit_to = $request->package_name[$i];
                $item_package->package_barcode = $request->package_barcode[$i];
                $item_package->save();
            }
        }

        return redirect()
            ->route('item.index')
            ->with('success', 'Item created successfully.');
    }

    public function edit($id)
    {   
        $units = GlobalConstants::UNITS;
        $COUNTABLE_UNIT_ID = GlobalConstants::COUNTABLE_UNIT_ID;
        $EMPTY_WEIGHT_ID = GlobalConstants::EMPTY_WEIGHT_ID;
        $FULL_WEIGHT_ID = GlobalConstants::FULL_WEIGHT_ID;
        $DENSITY_WEIGHT_ID = GlobalConstants::DENSITY_WEIGHT_ID;
        $DENSITY_UNIT_ID = GlobalConstants::DENSITY_UNIT_ID;
        $pkgnamelist = Item::PACKAGENAME;
        $item = Item::where('id', $id)->first();
        $item_size = ItemSize::where([['item_id', $id]])->get();
        if($item_size->count()){
            $item_size=$item_size[0];
        }
           
        
        $item_package = ItemPackage::where('item_id', $id)->first();
        if($item->class_id>0){
            [$classes, $categories, $qualities] = $this->editClassesCategoriesAndQualities($item->class_id);
        }
        else{
            [$classes,$categories,$qualities]=$this->getClassesCategoriesAndQualities();
        }
        
        return view('admin.item.edit', compact(
            'item',
            'item_size',
            'classes',
            'categories',
            'qualities',
            'pkgnamelist',
            'units',
            'COUNTABLE_UNIT_ID',
            'EMPTY_WEIGHT_ID',
            'FULL_WEIGHT_ID',
            'DENSITY_WEIGHT_ID',
            'DENSITY_UNIT_ID',
            'item_package'
        ));
    }

    public function update(Request $request, $id)
    {
        $item_edit = Item::find($id);
        $item_edit->name = $request->name;
        $item_edit->class_id = $request->class_id;
        $item_edit->category_id = $request->category_id;
        $item_edit->quality_id = $request->quality_id;
        $item_edit->user_id = auth()->user()->id;
        $item_edit->branch_id = session()->get('branch_id');
        $item_edit->period_id = session()->get('period_id');
        $item_edit->save();

        $item_size_edit = ItemSize::where('item_id', '=', $item_edit->id)->first();
        
        
        if($request->quantification){
            if($item_size_edit==null){
                //no item size in db
                $item_size_edit = ItemSize::create([
                    'item_id' => $item_edit->id,
                    'countable_size' => '',
                    'empty_weight_size' => '',
                    'full_weight_size'  => '',
                    'quantification'    => $request->quantification,
                    'package_status'    => 'no'
                ]);
                //update item package 
                $item_package=ItemPackage::where('item_id',$item_edit->id)->where('item_size_id',0)->get();
                
                if($item_package){
                    $item_package=$item_package[0];
                    $item_package->item_size_id=$item_size_edit->id;
                    $item_package->save();
                }
                         
            }
            else{
                $item_size_edit->quantification = $request->quantification;
            }
            
        }
        if ($request->density !=  null) {
            if($item_size_edit==null){
                if($item_size_edit==null){
                    //no item size in db
                    $item_size_edit = ItemSize::create([
                        'item_id' => $item_edit->id,
                        'countable_size' => '',
                        'empty_weight_size' => '',
                        'full_weight_size'  => '',
                        'density'    => $request->density,
                        'density_m_unit' => $request->density_m_unit,
                        'density_v_unit' => $request->density_v_unit,
                        'package_status'    => 'no'
                    ]);
                    //update item package 
                    $item_package=ItemPackage::where('item_id',$item_edit->id)->where('item_size_id',0)->get();
                    
                    if($item_package){
                        $item_package=$item_package[0];
                        $item_package->item_size_id=$item_size_edit->id;
                        $item_package->save();
                    }
            }else{
                $item_size_edit->density = $request->density;
                $item_size_edit->density_m_unit = $request->density_m_unit;
                $item_size_edit->density_v_unit = $request->density_v_unit;
            }
            
        }

        if($item_size_edit){
            $item_size_edit->save();
        }
        
        return redirect()
            ->route('item.index')
            ->with('success', 'Item updated successfully.');
        }
    }

    public function destroy(Item $item)
    {
        ItemPackage::where('item_id', $item->id)->delete();
        ItemSize::where('item_id', $item->id)->delete();
        $item->delete();

        return redirect()
            ->route('item.index')
            ->with('success', 'Item deleted successfully.');
    }

    public function createSize($item_id)
    {
        $units = GlobalConstants::UNITS;
        $COUNTABLE_UNIT_ID = GlobalConstants::COUNTABLE_UNIT_ID;
        $EMPTY_WEIGHT_ID = GlobalConstants::EMPTY_WEIGHT_ID;
        $FULL_WEIGHT_ID = GlobalConstants::FULL_WEIGHT_ID;
        $item = Item::where('id', $item_id)->first();
        [$classes, $categories, $qualities] = $this->getClassesCategoriesAndQualities();
        return view('admin.item.createsize', compact(
            'item',
            'classes',
            'categories',
            'qualities',
            'units',
            'COUNTABLE_UNIT_ID',
            'EMPTY_WEIGHT_ID',
            'FULL_WEIGHT_ID'
        ));
    }

    public function storeSize(Request $request, $id)
    {
        $item_size = new ItemSize();
        $item_size->item_id = $id;
        $item_size->countable_unit = $request->countable_unit;
        $item_size->countable_size = $request->countable_unit ? $request->countable_size : '';
        $item_size->empty_weight = $request->empty_weight;
        $item_size->empty_weight_size = $request->empty_weight ? $request->empty_weight_size : '';
        $item_size->full_weight = $request->full_weight;
        $item_size->full_weight_size = $request->full_weight ? $request->full_weight_size : '';
        $item_size->package_status = $request->package_status;
        //$item_size->barcode = $request->barcode;
        $item_size->save();

        $item_package = new ItemPackage();
        $item_package->item_id = $id;
        $item_package->item_size_id = $item_size->id;
        $item_package->qty = 1;
        $item_package->unit_from = $request->unit_from;
        $item_package->unit_to = $request->unit_from;
        $item_package->package_barcode = $request->barcode;
        $item_package->save();

        if ($request->package_status == 'yes') {
            for ($i = 0; $i < count($request->package_name); $i++) {
                if($request->package_size[$i]){
                    $item_package = new ItemPackage();
                    $item_package->item_id = $id;
                    $item_package->item_size_id = $item_size->id;
                    $item_package->qty = $request->package_size[$i];
                    $item_package->unit_from = $request->unit_from;
                    $item_package->unit_to = $request->package_name[$i];
                    $item_package->package_barcode = $request->package_barcode[$i];
                    $item_package->save();
                }
            }
        }

        return redirect()
            ->route('item.index')
            ->with('success', 'Size updated successfully.');
    }

    public function editSize($item_id, $item_size_id)
    {
        $units = GlobalConstants::UNITS;
        $COUNTABLE_UNIT_ID = GlobalConstants::COUNTABLE_UNIT_ID;
        $EMPTY_WEIGHT_ID = GlobalConstants::EMPTY_WEIGHT_ID;
        $FULL_WEIGHT_ID = GlobalConstants::FULL_WEIGHT_ID;
        $DENSITY_WEIGHT_ID = GlobalConstants::DENSITY_WEIGHT_ID;
        $DENSITY_UNIT_ID = GlobalConstants::DENSITY_UNIT_ID;
        $pkgnamelist = Item::PACKAGENAME;
        $item = Item::where('id', $item_id)->first();
        $item_size = ItemSize::where([['item_id', $item_id], ['id', $item_size_id]])->get()[0];
        
        $item_packages = ItemPackage::where([['item_size_id', $item_size_id]])->get();
        if($item->class_id>0){
            [$classes, $categories, $qualities] = $this->editClassesCategoriesAndQualities($item->class_id);
        }
        else{
            [$classes, $categories, $qualities] = $this->getClassesCategoriesAndQualities();
        }
        
        return view('admin.item.editsize', compact(
            'item',
            'item_size',
            'item_packages',
            'classes',
            'categories',
            'qualities',
            'pkgnamelist',
            'units',
            'COUNTABLE_UNIT_ID',
            'EMPTY_WEIGHT_ID',
            'FULL_WEIGHT_ID',
            'DENSITY_WEIGHT_ID',
            'DENSITY_UNIT_ID'
        ));
    }

    public function updateSize(Request $request, $id)
    {
        $item_size_update = ItemSize::find($id);
        $item_size_update->item_id = $item_size_update->item_id;
        //$item_size_update->barcode = $request['barcode'];
        $item_size_update->countable_unit = $request['countable_unit'] ?? '';
        $item_size_update->countable_size = isset($request['countable_unit']) ? $request['countable_size'] : '';
        $item_size_update->empty_weight =  $request['empty_weight'] ?? '';
        $item_size_update->empty_weight_size = isset($request['empty_weight']) ? $request['empty_weight_size'] : '';
        $item_size_update->full_weight = $request['full_weight'] ?? '';
        $item_size_update->full_weight_size = isset($request['full_weight']) ? $request['full_weight_size'] : '';
        $item_size_update->sizeoption = $request['sizeoption'];
        $item_size_update->package_status = $request['package_status'];
        $item_size_update->save();

        $first_item_package = ItemPackage::where('item_size_id', $item_size_update->id)->first();
        ItemPackage::where([['item_size_id', $item_size_update->id]])->delete();
        $item_package = new ItemPackage();
        $item_package->item_id = $first_item_package->item_id;
        $item_package->item_size_id = $first_item_package->item_size_id;
        $item_package->qty = 1;
        $item_package->unit_from = $request['unit_from'];
        $item_package->unit_to = $request['unit_from'];
        $item_package->package_barcode = $first_item_package->package_barcode;
        $item_package->save();

        if ($request['package_status'] == 'yes' && isset($request['package_name'])) {
            for ($i = 0; $i < count($request['package_name']); $i++) {
                if($request->package_size[$i]){
                    $item_package = new ItemPackage();
                    $item_package->item_id = $first_item_package->item_id;
                    $item_package->item_size_id = $first_item_package->item_size_id;
                    $item_package->qty = $request['package_size'][$i];
                    $item_package->unit_from =$request['unit_from'];
                    $item_package->unit_to = $request['package_name'][$i];
                    $item_package->package_barcode = $request['package_barcode'][$i];
                    $item_package->save();
                }
            }
        }

        return redirect()
            ->route('item.index')
            ->with('success', 'Size updated successfully.');
    }

    public function deleteSize($item_id, $item_size_id){
        ItemPackage::where('item_size_id', $item_size_id)->delete();
        ItemSize::where('id', $item_size_id)->delete();

        return redirect()
            ->route('item.index')
            ->with('success', 'Item Size deleted successfully.');
    }

    public function ajaxFetchAll(Request $request)
    {
        if (empty($request->q))
            return response()->json(array('results' => []));

        $items = Item::select('id', 'name as text')
            ->where('name', 'LIKE', "%{$request->q}%")
            ->get();

        return response()->json(array('results' => $items));
    }

    public function checkSizePackage(Request $request)
    {
        //$items = Item::where('id', 'LIKE', '%' . $request['id'] . '%')->get()[0];
        $all_items=DB::table('items')
        ->join('item_sizes','items.id','=','item_sizes.item_id')
        ->join('item_packages','items.id','=','item_packages.item_id')
        ->select('items.*','item_sizes.countable_unit', 'item_sizes.countable_size', 'item_sizes.barcode','item_packages.qty', 'item_packages.unit_form', 'item_packages.package_barcode')
        ->where('items.id','LIKE',"%{$request->id}%")->get();
        // $all_items=DB::table('items')
        // ->join('item_sizes','items.id','=','item_sizes.item_id')
        // ->select('items.*','item_sizes.*')
        // ->where('items.id','LIKE',"%{$request->id}%")->get();
        return $all_items;
    }

    private function getClassesCategoriesAndQualities()
    {
        $classes = Classes::get();
        $categories = Category::where('class_id', 1)->get();
        $qualities = Quality::where('class_id', 1)->get();
        return [$classes, $categories, $qualities];
    }

    private function editClassesCategoriesAndQualities($class_id)
    {
        $class = Classes::find($class_id);
        $classes = Classes::where('type', $class->type)->get();
        $categories = Category::where('class_id', $class_id)->get();
        $qualities = Quality::where('class_id', $class_id)->get();
        return [$classes, $categories, $qualities];
    }

    public function getItemSizesPackages()
    {
        if (request()->expectsJson() && request('itemid')) {
            $item_id = request('itemid');
            
            $item = Item::with('itemSize.itemPackage')->where('id', $item_id)->first();

            return response($item);
        }

        abort(404);
    }

    public function searchbyname()
    {
        if (request()->expectsJson() && request('q')) {
            $term = request('q');
            $itemQuery = Item::select('id', 'name as text');

            $items = $itemQuery
                ->where('name', 'LIKE', "%{$term}%")
                ->get();

            return response($items);
        }

        abort(404);
    }

    public function searchbybarcode()
    {
        if (request()->expectsJson() && request('q')) {
            $term = request('q');
            // $itemQuery = Item::select("id", DB::raw("CONCAT(items.barcode,' ',items.name) as text"));

            // $items = $itemQuery
            //     ->where('barcode', 'LIKE', "%{$term}%")
            //     ->get();

            $item_ids = ItemPackage::where('package_barcode', 'LIKE', "%{$term}%")->pluck('item_id');
            $items = Item::select("id","name as text")->whereIn('id', $item_ids)->get();

            return response($items);
        }

        abort(404);
    }

    public function getItemById()
    {
        if (request()->expectsJson() && request('id')) {
            $id = request('id');
            //get latest unit price depend on vendor
            $unit_price = 0;
            $vendor_id = request('vendor_id');
            $current_invoice_id = request('invoice_id');
            $invoice = Invoice::where('vendor_id', $vendor_id)->where('id', '!=', $current_invoice_id)->orderBy('id', 'desc')->first();
            if ($invoice !== null) {
                $invoice_details = InvoiceDetails::where('invoice_id', $invoice->id)->where('item_id', $id)->orderBy('id', 'desc')->first();
                $unit_price = ($invoice_details === null) ? 0 : $invoice_details->unit_price;
            }

            $items = collect(Item::where('id', $id)->first());
            if (!$items->has('unit_price')) {
                $items->put('unit_price', $unit_price);
            }

            $item_sizes = ItemSize::where('item_id', $id)->get();
            $item_packages = ItemPackage::where('item_id', $id)->get();
            return response(['status' => 'success', 'data' => $items, 'item_sizes' => $item_sizes , 'item_packages' => $item_packages]);
        }
        abort(404);
    }
}
