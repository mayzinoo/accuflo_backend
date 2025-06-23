<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\FullCount;
use App\Models\Weight;
use App\Models\Item;
use App\Models\ItemPackage;
use App\Models\ItemSize;
use App\Models\Invoice;
use DB;

class Summary extends Model
{
    use HasFactory;

    public static function getOnHand($period_id, $branch_id){
        //get purchase price
        $invoice_details = Invoice::where('branch_id', $branch_id)
            ->join('invoice_details', 'invoice_details.invoice_id', '=', 'invoices.id')
            ->select('item_id', 'unit_price', 'purchase_package', 'purchased_quantity', 'total_cost', 'extended_price')
            ->get();

        $invoice_details_by_item = $invoice_details->keyBy('item_id');
        $invoice_details_by_package = $invoice_details->keyBy('purchase_package');

        //get full count each item
        $fullcounts = FullCount::where('period_id', $period_id)
            ->where('branch_id', $branch_id)
            ->groupBy('package_id')
            ->select('item_id', 'package_id', DB::raw('sum(period_count) as total_count'))
            ->get();
        $fullcount_by_package = $fullcounts->keyBy('package_id');

        //get weight each item
        $weights = Weight::where('period_id', $period_id)
            ->where('branch_id', $branch_id)
            ->groupBy('package_id')
            ->select('item_id' ,'package_id', DB::raw('sum(weight) as total_count'))
            ->get();        

        //get qty for each item package
        $item_package = ItemPackage::whereIn('id', array_merge($fullcounts->pluck('package_id')->toArray(), $weights->pluck('package_id')->toArray()))->get()->keyBy('id');
        
        $item_size = ItemSize::whereIn('item_id', array_merge($fullcounts->pluck('item_id')->toArray(), $weights->pluck('item_id')->toArray()))->get()->keyBy('item_id');

        $fullcount_items = [];
        $onhand_cost_by_fullcount = [];
        foreach($fullcounts as $fullcount){
            // check purchase exit in full count
            if(isset($invoice_details_by_package[$fullcount->package_id])){
                $item_package_qty = $item_package[$fullcount->package_id]->qty;
                $per_bottle_price = $invoice_details_by_package[$fullcount->package_id]->unit_price / $item_package_qty;
            }else{
                if(isset($invoice_details_by_item[$fullcount->item_id])){
                    $item_package_qty = $item_package[$invoice_details_by_item[$fullcount->item_id]->purchase_package]->qty;
                    $per_bottle_price = $invoice_details_by_item[$fullcount->item_id]->unit_price / $item_package_qty;
                }else{
                    $per_bottle_price = 0;
                }
            }
            $total_bottle_by_package = ($fullcount->total_count * (isset($item_package[$fullcount->package_id]) ? $item_package[$fullcount->package_id]->qty : 0) );
            $fullcount_items[$fullcount->package_id] =  $total_bottle_by_package * $per_bottle_price;
            $onhand_cost_by_fullcount[$fullcount->item_id][$fullcount->package_id] =  $total_bottle_by_package * $per_bottle_price;
        }

        $weight_items = [];
        $onhand_cost_by_weight = [];
        $per_ml_price_by_item = [];
        foreach($weights as $weight){
            //per bottle price
            if(isset($invoice_details_by_package[$weight->package_id])){
                $item_package_qty = $item_package[$weight->package_id]->qty;
                $per_bottle_price = $invoice_details_by_package[$weight->package_id]->unit_price / $item_package_qty;
            }else{
                if(isset($invoice_details_by_item[$weight->item_id])){
                    $item_package_qty = $item_package[$invoice_details_by_item[$weight->item_id]->purchase_package]->qty;
                    $per_bottle_price = $invoice_details_by_item[$weight->item_id]->unit_price / $item_package_qty;
                }else{
                    $per_bottle_price = 0;
                }             
            }
            //per ml price
            $per_ml_price = $per_bottle_price / $item_size[$weight->item_id]->countable_unit;
            $onhand_ml = 0;
            // get current weight ml [Current weight (g) - previous weight (g)] / density of item (g/ml)]
            if($item_size[$weight->item_id]->density != 0){
                $used_ml = ($item_size[$weight->item_id]->full_weight - $weight->total_count) / $item_size[$weight->item_id]->density;
                $onhand_ml = $item_size[$weight->item_id]->countable_unit - $used_ml;
            }
            $per_ml_price_by_item[$weight->item_id] = $per_ml_price;
            $weight_items[$weight->package_id] = $per_ml_price * $onhand_ml;
            $onhand_cost_by_weight[$weight->item_id][$weight->package_id] =  $per_ml_price * $onhand_ml;
        }
        
        $total_fullcount_items = array_sum($fullcount_items);
        $total_weight_items = array_sum($weight_items);
        $on_hand_cost = ($total_fullcount_items + $total_weight_items);

        return [
            // 'used_cost' => $used_cost,
            // 'used_cost_by_item' => $used_cost_by_item,
            // 'purchase_cost_by_item' => $purchase_cost_by_item,
            'on_hand_cost' => $on_hand_cost,
            'invoice_details' => $invoice_details,
            'onhand_cost_by_weight' => $onhand_cost_by_weight,
            'weight_items' => $weight_items,
            'onhand_cost_by_fullcount' => $onhand_cost_by_fullcount,
            'fullcount_items' => $fullcount_items,
            'per_ml_price_by_item' => $per_ml_price_by_item,
        ];
    }

    public static function getUsedCost($period_id, $branch_id, $current_on_hand, $perivous_on_hand){

        $invoice_details = Invoice::where('branch_id', $branch_id)
        ->where('period_id', $period_id)
        ->join('invoice_details', 'invoice_details.invoice_id', '=', 'invoices.id')
        ->select('item_id', 'unit_price', 'purchase_package', 'purchased_quantity', 'total_cost', 'extended_price')
        ->get();
        //used cost = purchase cost - on hand        
        $used_cost = (array_sum($invoice_details->pluck('total_cost')->toArray()) - $current_on_hand) + $perivous_on_hand;
        $used_cost_by_item = [];
        $purchase_cost_by_item = [];
        foreach($invoice_details as $invoice_detail){
            $weight = isset($onhand_cost_by_weight[$invoice_detail->item_id]) ? array_sum($onhand_cost_by_weight[$invoice_detail->item_id]) : 0;
            $fullcount = isset($onhand_cost_by_fullcount[$invoice_detail->item_id]) ? array_sum($onhand_cost_by_fullcount[$invoice_detail->item_id]) : 0;
            $weight_and_full_count = ($weight + $fullcount);

            $used_cost_by_item[$invoice_detail->item_id] = $invoice_detail->extended_price - $weight_and_full_count;

            $purchase_cost_by_item[$invoice_detail->item_id][$invoice_detail->purchase_package] = $invoice_detail->extended_price;
        }

        return [
            'used_cost' => $used_cost,
            'used_cost_by_item' => $used_cost_by_item,
            'purchase_cost_by_item' => $purchase_cost_by_item,
        ];
    }

    public static function getSaleCost($period_id, $branch_id){
        //get full count each item
        $fullcounts = FullCount::where('period_id', $period_id)
            ->where('branch_id', $branch_id)
            ->groupBy('package_id')
            ->select('item_id', 'package_id', DB::raw('sum(period_count) as total_count'))
            ->get();
        $fullcount_by_package = $fullcounts->keyBy('package_id');

        //get weight each item
        $weights = Weight::where('period_id', $period_id)
            ->where('branch_id', $branch_id)
            ->groupBy('package_id')
            ->select('item_id' ,'package_id', DB::raw('sum(weight) as total_count'))
            ->get();

        //get qty for each item package
        $item_package = ItemPackage::whereIn('id', array_merge($fullcounts->pluck('package_id')->toArray(), $weights->pluck('package_id')->toArray()))->get()->keyBy('id');
        
        $item_size = ItemSize::whereIn('item_id', array_merge($fullcounts->pluck('item_id')->toArray(), $weights->pluck('item_id')->toArray()))->get()->keyBy('item_id');
        //get sale price
        $sale_details = Recipe::where('recipes.period_id', $period_id)
            ->join('recipe_ingredients', 'recipe_ingredients.recipe_id', '=', 'recipes.id')
            ->join('recipe_sales', 'recipe_sales.recipe_id', '=', 'recipes.id')
            ->where('recipe_sales.qty', '!=', 0)
            ->groupBy('recipe_id')
            ->select('item_id','package_id','recipe_ingredients.qty as ingredients_qty', 'recipe_sales.qty as sale_qty', 'revenue', 'recipe_sales.recipe_id')
            ->get();
        
        $sale_cost = $sale_details->sum('revenue');
        $sale_cost_by_item = [];
        foreach($sale_details as $sale_detail){
            // $sale_used_ml = 0;
            // check item package uom or not
            $sale_qty = $sale_detail->ingredients_qty * $sale_detail->sale_qty;
            if($sale_detail->package_id < 1){
                //if not item package , qty is from sold
                $sale_used_ml = $sale_qty;
            }else{
                //if uom with item package -> get package total bottle
                $sale_used_ml = ($sale_qty * (isset($item_package[$sale_detail->package_id]) ? $item_package[$sale_detail->package_id]->qty : 0)) * $item_size[$sale_detail->item_id]->countable_unit ;
            }
            $sale_per_ml_price = isset($per_ml_price_by_item[$sale_detail->item_id]) ? $per_ml_price_by_item[$sale_detail->item_id] : 0;
            $sale_cost_by_item[$sale_detail->item_id] = $sale_per_ml_price * $sale_used_ml;
        }

        return [
            'sale_cost' => $sale_cost,
            'sale_cost_by_item' => $sale_cost_by_item
        ];
    }

    public static function getPieChartClass($current_period){
        $pie_chart_class = [];
        $item_merge = array_merge(array_keys($current_period['fullcount_items']), array_keys($current_period['weight_items']));
        $item_array = array_unique($item_merge);
        $items = ItemPackage::whereIn('item_packages.id', $item_array)
            ->join('items', 'items.id', '=', 'item_packages.item_id')
            ->join('classes', 'classes.id', '=', 'items.class_id')
            ->pluck('type','item_packages.id');

        $total_class_cost = 0;
        foreach($items as $key=>$item){
            //on hand pie chart
            $w_cost = isset($current_period['weight_items'][$key]) ? $current_period['weight_items'][$key] : 0;
            $f_cost = isset($current_period['fullcount_items'][$key]) ? $current_period['fullcount_items'][$key] : 0;
            if(isset($pie_chart_class[$item])){
                if(array_key_exists($item ,$pie_chart_class)){
                    $total_class_cost = $pie_chart_class[$item] + $w_cost + $f_cost;
                }
            }else{
                $total_class_cost = $w_cost + $f_cost;
            }
            $pie_chart_class[$item] = $total_class_cost;
        }

        foreach($pie_chart_class as $class_key=> $class){
            $on_hand_percent = array_sum($current_period) !=0 ? ($class / array_sum($current_period)) * 100 : 0;
            $pie_chart_class[$class_key] = $on_hand_percent;
        }

        return $pie_chart_class;
    }

    public static function getUsedPieChartClass($used_cost_by_item){
        $used_items_obj = Item::whereIn('items.id', array_keys($used_cost_by_item))
            ->join('classes', 'classes.id', '=', 'items.class_id')
            ->select('type', 'items.id', 'items.name')
            ->get();
        $used_items_key_by_id = $used_items_obj->keyBy('id');
        $used_items = $used_items_obj->pluck('type','id');

        $used_pie_chart_class = [];
        foreach($used_items as $used_item_key=>$used_item){
            $used_pie_chart_class[$used_item] = $used_cost_by_item[$used_item_key];
        }

        foreach($used_pie_chart_class as $use_class_key=> $use_class){
            $used_percent = array_sum($used_cost_by_item) !=0 ? ($use_class / array_sum($used_cost_by_item)) * 100 : 0;
            $used_pie_chart_class[$use_class_key] = $used_percent;
        }

        return $used_pie_chart_class;
    }

    public static function getSalePieChartClass($sale_cost_by_item){
        $sale_items_obj = Item::whereIn('items.id', array_keys($sale_cost_by_item))
            ->join('classes', 'classes.id', '=', 'items.class_id')
            ->select('type', 'items.id', 'items.name')
            ->get();
        $sale_items_key_by_id = $sale_items_obj->keyBy('id');

        $sale_items = $sale_items_obj->pluck('type','id');

        $sale_pie_chart_class = [];
        foreach($sale_items as $sale_item_key=>$sale_item){
            $sale_pie_chart_class[$sale_item] = $sale_cost_by_item[$sale_item_key];
        }

        foreach($sale_pie_chart_class as $sale_class_key=> $sale_class){
            $sale_percent = array_sum($sale_cost_by_item) != 0 ? ($sale_class / array_sum($sale_cost_by_item)) * 100 : 0;
            $sale_pie_chart_class[$sale_class_key] = $sale_percent;
        }
        return $sale_pie_chart_class;
    }

    public static function getMissing($current_period, $current_sale){

        $sale_items_obj = Item::whereIn('items.id', array_keys($current_period['onhand_cost_by_fullcount'] ))
            ->join('classes', 'classes.id', '=', 'items.class_id')
            ->select('type', 'items.id', 'items.name')
            ->get();
        $sale_items_key_by_id = $sale_items_obj->keyBy('id');
        $sale_items = $sale_items_obj->pluck('type','id');

        $loss_leader = [];
        $missing_cost_by_item = [];
        $missing_percent_by_item = [];
        foreach($current_period['onhand_cost_by_fullcount'] as $item_key=>$item_value){
            $per_ml_cost = isset($current_period['per_ml_price_by_item'][$item_key]) ? $current_period['per_ml_price_by_item'][$item_key] : 0;
            $onhand_fullcount = isset($current_period['onhand_cost_by_fullcount'][$item_key]) ? array_sum($current_period['onhand_cost_by_fullcount'][$item_key]) : 0;
            $onhand_weight = isset($current_period['onhand_cost_by_weight'][$item_key]) ? array_sum($current_period['onhand_cost_by_weight'][$item_key]) : 0;
            $purchase_cost = isset($current_used['purchase_cost_by_item'][$item_key]) ? array_sum($current_used['purchase_cost_by_item'][$item_key]) : 0;

            $used = $purchase_cost - ($onhand_fullcount + $onhand_weight);
            $missing_cost = isset($current_sale['sale_cost_by_item'][$item_key])? ($current_sale['sale_cost_by_item'][$item_key]- $used) : 0 ;
            $missing_ml = $per_ml_cost !=0 ? ($missing_cost / $per_ml_cost) : 0;
            $used_ml = $per_ml_cost !=0 ? $used / $per_ml_cost : 0;
            $missing_perent = $used_ml !=0 ? ($missing_ml / $used_ml) * 100 : 0;
            if($missing_cost < 0){
                if(isset($sale_items_key_by_id[$item_key])){
                    $missing_item_array = [
                        'name' => $sale_items_key_by_id[$item_key]->name,
                        'cost' => $used,
                        'percent' => $missing_perent
                    ];
                    $missing_cost_by_item[$sale_items[$item_key]][] = $missing_item_array;
                    $loss_leader[$item_key] = $missing_item_array;
                }
                
            }            
        }

        // //sort ascending for loss leader array
        if(count($loss_leader) > 0){
            $sort = array();
            foreach($loss_leader as $k=>$v) {
                $sort['cost'][$k] = $v['cost'];
            }
            array_multisort($sort['cost'], SORT_ASC, $loss_leader);
        }

        return [
            'missing_cost_by_item' => $missing_cost_by_item,
            'loss_leader' => $loss_leader
        ];
    }
}
