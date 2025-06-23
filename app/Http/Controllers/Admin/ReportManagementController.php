<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\User;
use App\Models\Invoice;
use App\Models\InvoiceDetails;
use App\Models\FullCount;
use App\Models\Weight;
use App\Models\Item;
use App\Models\ItemPackage;
use App\Models\ItemSize;
use App\Models\Recipe;
use App\Models\RecipeIngredients;
use App\Models\RecipeSale;
use App\Models\Period;
use Illuminate\Http\Request;
use DB;

class ReportManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:management');
    }
    public function index()
    {        
        $period_id = session()->get('period_id');
        $branch_id = session()->get('branch_id');
        
        //get purchase price
        $invoices = Invoice::where('period_id', $period_id)->where('branch_id', $branch_id)->get();
        $invoice_details = InvoiceDetails::whereIn('invoice_id', $invoices->pluck('id'))
            ->select('item_id', 'unit_price', 'purchase_package', 'purchased_quantity')
            ->get();

        $invoice_details_by_item = $invoice_details->keyBy('purchase_package');

        //get qty for each item package
        $item_package = ItemPackage::whereIn('id', $invoice_details->pluck('purchase_package'))->get()->keyBy('id');
        
        $item_size = ItemSize::whereIn('item_id', $invoice_details->pluck('item_id'))->get()->keyBy('item_id');
            
        //get full count each item
        $fullcount = FullCount::where('period_id', $period_id)
            ->where('branch_id', $branch_id)
            ->groupBy('package_id')
            ->select('package_id', DB::raw('sum(period_count) as total_count'))
            ->get();
        $fullcount_by_package = $fullcount->keyBy('package_id');

        //get weight each item
        $weight = Weight::where('period_id', $period_id)
            ->where('branch_id', $branch_id)
            ->groupBy('package_id')
            ->select('package_id', DB::raw('sum(weight) as total_count'))
            ->get();        
        $weight_by_package = $weight->keyBy('package_id');

        //get price for each item
        $fullcount_items = [];
        foreach($invoice_details as $invoice_detail){
            //price / total bottle
            if(isset($fullcount_by_package[$invoice_detail->purchase_package])){
                $per_bottle_price = $invoice_detail->unit_price / $item_package[$invoice_detail->purchase_package]->qty;
                $fullcount_items[$invoice_detail->purchase_package] = $fullcount_by_package[$invoice_detail->purchase_package]->total_count * $per_bottle_price;
            }            
        }
        
        $weight_items = [];
        foreach($invoice_details as $invoice_detail){
            if(isset($weight_by_package[$invoice_detail->purchase_package])){
                //1ml price
                $per_ml_price = $invoice_detail->unit_price / $item_size[$invoice_detail->item_id]->countable_unit;
                // get current weight ml [Current weight (g) - previous weight (g)] / density of item (g/ml)]
                $onhand_ml = 0;
                if($item_size[$invoice_detail->item_id]->density != 0){
                    $used_ml = ($item_size[$invoice_detail->item_id]->full_weight - $weight_by_package[$invoice_detail->purchase_package]->total_count) / $item_size[$invoice_detail->item_id]->density;
                    $onhand_ml = $item_size[$invoice_detail->item_id]->countable_unit - $used_ml;
                }
                $weight_items[$invoice_detail->purchase_package] = $per_ml_price * $onhand_ml;
            }
        } 

        
        $total_fullcount_items = array_sum($fullcount_items);
        $total_weight_items = array_sum($weight_items);

        $on_hand_cost = ($total_fullcount_items + $total_weight_items);
        
        $used_cost_by_item = [];
        foreach($invoice_details as $invoice_detail){
            $weight = isset($weight_items[$invoice_detail->purchase_package]) ? $weight_items[$invoice_detail->purchase_package] : 0;
            $fullcount = isset($fullcount_items[$invoice_detail->purchase_package]) ? $fullcount_items[$invoice_detail->purchase_package] : 0;
            $weight_full_count = ($weight + $fullcount);
            if($weight_full_count != 0){
                $used_cost_by_item[$invoice_detail->purchase_package] = ($invoice_detail->unit_price * $invoice_detail->purchased_quantity ) - $weight_full_count;
            }
        }
        $used_cost = array_sum($used_cost_by_item);

        //get sale cost
        $sales = Recipe::where('recipes.period_id', $period_id)
            ->join('recipe_sales', 'recipe_sales.recipe_id', '=', 'recipes.id')
            ->join('price_levels', 'price_levels.id', '=', 'recipe_sales.price_level_id')
            ->where('price_levels.type', 0)
            ->pluck('revenue')->toArray();
        
        $sale_cost = array_sum($sales);
        
        //get item pie chart data
        $item_merge = array_merge(array_keys($fullcount_items), array_keys($weight_items));
        $item_array = array_unique($item_merge);
        $items = ItemPackage::whereIn('item_packages.id', $item_array)
            ->join('items', 'items.id', '=', 'item_packages.item_id')
            ->join('classes', 'classes.id', '=', 'items.class_id')
            ->pluck('type','item_packages.id');

        $pie_chart_class = [];
        $total_class_cost = 0;
        foreach($items as $key=>$item){
            $w_cost = isset($weight_items[$key]) ? $weight_items[$key] : 0;
            $f_cost = isset($fullcount_items[$key]) ? $fullcount_items[$key] : 0;
            if(isset($pie_chart_class[$item])){
                if(array_key_exists($item ,$pie_chart_class)){
                    $total_class_cost = $pie_chart_class[$item] + number_format($w_cost, 2) + number_format($f_cost, 2);
                }
            }else{
                $total_class_cost = number_format($w_cost, 2) + number_format($f_cost, 2);
            }
            $pie_chart_class[$item] = $total_class_cost ;
        }
        //get sale cost pie chart
        $sale_items = Recipe::where('recipes.period_id', $period_id)
            ->join('recipe_ingredients', 'recipe_ingredients.recipe_id', '=', 'recipes.id')
            ->join('item_packages', 'item_packages.id', '=', 'recipe_ingredients.package_id')
            ->join('items', 'items.id', '=', 'item_packages.item_id')
            ->join('classes', 'classes.id', '=', 'items.class_id')
            ->pluck('recipe_ingredients.qty','classes.type')->toArray();
            
        $data['on_hand_cost'] = number_format($on_hand_cost, 2);
        $data['used_cost'] = number_format($used_cost, 2);
        $data['sale_cost'] = number_format($sale_cost, 2);
        $data['pie_chart_class'] = array_keys($pie_chart_class);
        $data['pie_chart_class_cost'] = array_values($pie_chart_class);
        $data['sale_pie_chart_class'] = array_keys($sale_items);
        $data['sale_pie_chart_class_cost'] = array_values($sale_items);

        return view('admin.report_mgmt.index', $data);
    }
}
