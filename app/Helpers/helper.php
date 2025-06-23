<?php

use App\Models\Period;
use App\Models\User;
use App\Models\ItemSize;
use App\Models\ItemPackage;
use App\Models\FullCount;
use App\Models\Weight;
use App\Models\Branch;
use App\GlobalConstants;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

if (!function_exists('active_path')) {
    function active_path($path = null)
    {
        return request()->routeIs($path) ? 'active' : '';
    }
}

if (!function_exists('active_treeview')) {
    function active_treeview($arr_path = [])
    {
        $menu_active = '';
        if (count($arr_path) > 0) {
            foreach ($arr_path as $key => $val) {
                if (request()->routeIs($val)) {
                    $menu_active = 'active';
                }
            }
        }
        return $menu_active;
    }
}

if (!function_exists('expand_treeview')) {
    function expand_treeview($arr_path = [])
    {
        $menu_open = '';
        if (count($arr_path) > 0) {
            foreach ($arr_path as $key => $val) {
                if (request()->routeIs($val)) {
                    $menu_open = 'menu-open';
                }
            }
        }
        return $menu_open;
    }
}

if (!function_exists('pr')) {
    function pr($data)
    {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }
}

if (!function_exists('date_range')) {

    function date_range($start_date, $end_date)
    {
        $converted_start_date = date("Y-m-d", strtotime($start_date));
        $converted_end_date = date("Y-m-d", strtotime($end_date));
        $arr_start_date = explode('-', $converted_start_date);
        $arr_end_date = explode('-', $converted_end_date);

        if ($arr_start_date[1] == $arr_end_date[1]) {
            return date("M d", strtotime($start_date)) . ' to ' . date("M d, Y", strtotime($end_date));
        } else {
            return optional($start_date)->toFormattedDateString() . ' to ' . optional($end_date)->toFormattedDateString();
        }
    }
}

if (!function_exists('get_periods')) {
    function get_periods($customer_id)
    {
        $periods = Period::where('user_id', $customer_id)->get();
        return $periods;
    }
}

if (!function_exists('get_last_period_status')) {
    function get_last_period_status()
    {
        $target_period = Period::where('id', '<', session()->get('period_id'))
            ->where('branch_id', session()->get('branch_id'))
            ->select(DB::raw('MAX(id) as id'))
            ->first();

        $period = Period::where('id', $target_period->id)->first();
        if ($period) {
            return $period->status;
        } else {
            return null;
        }
    }
}

if (!function_exists('get_current_period_status')) {
    function get_current_period_status()
    {
        $period = Period::where('id', session()->get('period_id'))->first();
        if($period){
            return $period->status;
        }else{
            return null;
        }
    }
}

if (!function_exists('get_customers')) {
    function get_customers()
    {
        if(Auth::user()->role == "super_admin" || Auth::user()->role == "admin"){
            $customers = User::where('role', GlobalConstants::USER_TYPES['client'])->get();
        }else{
            $customers = User::where('role', GlobalConstants::USER_TYPES['client'])
                               ->where('company_id', Auth::user()->company_id)
                               ->get();
        }
        return $customers;
    }
}

if (!function_exists('get_branches')) {
    function get_branches()
    {
        if(Auth::user()->role == "super_admin" || Auth::user()->role == "admin"){
            $branches = Branch::get();
        }else{
            $branches = Branch::where('company_id', Auth::user()->company_id)
                               ->get();
        }
        return $branches;
    }
}

if (!function_exists('selected_customer_id')) {
    function selected_customer_id()
    {
        $selected_customer_id = '';
        if (request()->branch_id) {
            $selected_customer_id = request()->branch_id;
        } elseif (Session::get('customer_id')) {
            $selected_customer_id = Session::get('customer_id');
        } elseif (last_access_customer_id()) {
            $selected_customer_id = last_access_customer_id();
        } else {
            if(Auth::user()->role == "super_admin" || Auth::user()->role == "admin"){
                $customer = User::where('role', GlobalConstants::USER_TYPES['client'])
                ->select(DB::raw('MAX(id) as id'))
                ->first();
            }else{
                $customer = User::where('role', GlobalConstants::USER_TYPES['client'])
                ->where('company_id', Auth::user()->company_id)
                ->select(DB::raw('MAX(id) as id'))
                ->first();
            }
            $selected_customer_id = $customer->id;
        }
        return $selected_customer_id;
    }
}

if (!function_exists('selected_branch_id')) {
    function selected_branch_id()
    {
        $selected_branch_id = '';
        if (request()->branch_id) {
            $selected_branch_id = request()->branch_id;
        } elseif (Session::get('branch_id')) {
            $selected_branch_id = Session::get('branch_id');
        } 
        // elseif (last_access_branch_id()) {
        //     $selected_branch_id = last_access_branch_id();
        //     return "last";
        //     // Session::put('period_id', Period::where('branch_id', $selected_branch_id)->value('id'));
        //     // Session::put('branch_id', $selected_branch_id);
        // } 
        else {
            if(Auth::user()->role == "super_admin" || Auth::user()->role == "admin"){
                $customer = User::where('role', GlobalConstants::USER_TYPES['client'])
                ->select(DB::raw('MIN(branch_id) as branch_id'))
                ->first();
            }else{
                $customer = User::where('role', GlobalConstants::USER_TYPES['client'])
                ->where('id', Auth::user()->id)
                ->select(DB::raw('MIN(branch_id) as branch_id'))
                ->first();
            }
            $selected_branch_id = $customer->branch_id;
            Session::put('period_id', Period::where('branch_id', $selected_branch_id)->value('id'));
            Session::put('branch_id', $selected_branch_id);
        }
        return $selected_branch_id;
    }
}


if (!function_exists('last_access_branch_id')) {
    function last_access_branch_id()
    {
        $branch_id = null;
        $branch = User::where('last_access_customer_id', 1)
                        ->where('role', GlobalConstants::USER_TYPES['client'])
                        ->where('company_id', Auth::user()->company_id)
                        ->first();
        if ($branch) {
            $branch_id = $branch->id;
        }
        return $branch_id;
    }
}

if (!function_exists('last_access_customer_id')) {
    function last_access_customer_id()
    {
        $customer_id = null;
        $customer = User::where('last_access_customer_id', 1)
                        ->where('role', GlobalConstants::USER_TYPES['client'])
                        ->where('company_id', Auth::user()->company_id)
                        ->first();
        if ($customer) {
            $customer_id = $customer->id;
        }
        return $customer_id;
    }
}

if (!function_exists('package_format')) {
    function package_format($item_id)
    {
        $package_format = [];
        $item_size_list = ItemSize::where('item_id', $item_id)->get();
        $item_package_list = ItemPackage::where('item_id', $item_id)->get();
        foreach ($item_size_list as $item_size) {
            $item_package_list = ItemPackage::where('item_size_id', $item_size->id)->get();
            foreach ($item_package_list as $item_package) {
                if ($item_package->unit_from == $item_package->unit_to) {
                    $countable_format = '(' . $item_size->countable_unit . ' ' . $item_size->countable_size . ')';
                } else {
                    $countable_format = '(' . $item_package->qty . 'x' . $item_size->countable_unit . ' ' . $item_size->countable_size . ')';
                }
                if ($item_package->package_barcode != null) {
                    $package_format[$item_package->id] = (object) ['unit_to' => $item_package->unit_to, 'text' => $item_package->unit_to . $countable_format . ' - ' . $item_package->package_barcode];
                } else {
                    $package_format[$item_package->id] = (object) ['unit_to' => $item_package->unit_to, 'text' => $item_package->unit_to . $countable_format];
                }
            }
        }
        return $package_format;
    }
}

function get_total_unopened($item_id, $size,$period_id)
{
    $total_period_count = DB::table('full_counts')->selectRaw('sum(period_count) as period_count')
        ->where('item_id', $item_id)->where('size', $size)->where('period_id',$period_id)
        ->groupBy('size')
        ->get();
    if($total_period_count->count()){
        return $total_period_count[0]->period_count;
    }
    return 0;
}
function get_total_opened($item_id,$size,$period_id){
    $total=0;
    $weights=Weight::where('period_id',$period_id)->where('item_id',$item_id)->where('size',$size)->get();
    foreach($weights as $weight){
        $size=$weight->size;
        preg_match('/([a-zA-Z]*[a-zA-Z])/', $size, $matches);
        //$countable_size is ml now
        $countable_size=$matches[0];
                                
        $pattern="/{$countable_size}/i";
        $countable_unit=trim(preg_replace($pattern,'',$size));
        $item_size=ItemSize::where('item_id',$item_id)->where('countable_unit',$countable_unit)->where('countable_size',$countable_size)->get()[0];
        $on_hand=($weight->weight-$item_size->empty_weight)/($item_size->full_weight-$item_size->empty_weight);
        
        $total+=$on_hand;
    }
    return number_format($total,1,".","");
   
}
function get_fullcount1($items, $category_id)
{
    $fullcounts_data = DB::table('full_counts')
        ->leftJoin('invoice_details', 'full_counts.package_id', '=', 'invoice_details.purchase_package')
        ->join('items', 'full_counts.item_id', '=', 'items.id')
        ->join('categories', 'items.category_id', '=', 'categories.id')
        ->selectRaw(
            'full_counts.size, full_counts.station_id, full_counts.item_id,
            max(invoice_details.unit_price) as unit_price,max(full_counts.package_id) as package_id,
            max(invoice_details.id) as invoice_detail_id, items.name as item_name, categories.name as category'
        )
        ->whereIn('full_counts.item_id', $items)
        ->where('categories.id', $category_id)
        ->groupBy('invoice_details.item_id', 'size')
        ->orderBy('invoice_details.item_id')
        ->get();

    return $fullcounts_data;
}
function get_weight($items,$category_id){
    $weights_data = DB::table('weights')
    ->leftJoin('invoice_details', 'weights.package_id', '=', 'invoice_details.purchase_package')
    ->join('items', 'weights.item_id', '=', 'items.id')
    ->join('categories', 'items.category_id', '=', 'categories.id')
    ->selectRaw(
        'weights.size, weights.station_id, weights.item_id,
        max(invoice_details.unit_price) as unit_price,max(weights.package_id) as package_id,
        max(invoice_details.id) as invoice_detail_id, items.name as item_name, categories.name as category'
    )
    ->whereIn('weights.item_id', $items)
    ->where('categories.id', $category_id)
    ->groupBy('invoice_details.item_id', 'size')
    ->orderBy('invoice_details.item_id')
    ->get();
    return $weights_data;
}
function get_unit($item_package_id){
    $item_package=ItemPackage::find($item_package_id);
    if($item_package){
        return $item_package->unit_to;
    }
    return "";
}
function get_all_previous($period_id,$item_id,$size){
    $total=0;
    $full_counts=FullCount::where('period_id',$period_id)->where('item_id',$item_id)->where('size',$size)->get();
    foreach($full_counts as $full_count){
        $total+=$full_count->period_count;
    }

    $weights=Weight::where('period_id',$period_id)->where('item_id',$item_id)->where('size',$size)->get();
    foreach($weights as $weight){
        $size=$weight->size;
        preg_match('/([a-zA-Z]*[a-zA-Z])/', $size, $matches);
        //$countable_size is ml now
        $countable_size=$matches[0];
                                
        $pattern="/{$countable_size}/i";
        $countable_unit=trim(preg_replace($pattern,'',$size));
        $item_size=ItemSize::where('item_id',$item_id)->where('countable_unit',$countable_unit)->where('countable_size',$countable_size)->get()[0];
        $on_hand=($weight->weight-$item_size->empty_weight)/($item_size->full_weight-$item_size->empty_weight);
        $total+=$on_hand;
    }
    return number_format($total,1,".","");
 
}

function get_total_purchased($item_id, $item_package_id)
{
    $total_purchased = DB::table('invoice_details')->selectRaw('sum(purchased_quantity) as purchased_quantity')
        ->where('item_id', $item_id)->where('purchase_package', $item_package_id)
        ->groupBy('item_id', 'purchase_package')->get();
    if ($total_purchased->count()) {
        return $total_purchased[0]->purchased_quantity;
    }
    return 0;
}

 function get_full_count_with_category($items, $category_id)
{

    $fullcounts_data = DB::table('full_counts')
        ->leftJoin('invoice_details', 'full_counts.package_id', '=', 'invoice_details.purchase_package')
        ->join('items', 'full_counts.item_id', '=', 'items.id')
        ->join('item_packages', 'full_counts.package_id', 'item_packages.id')
        ->join('item_sizes', 'full_counts.item_id', '=', 'item_sizes.item_id')
        ->join('categories', 'items.category_id', '=', 'categories.id')
        ->selectRaw(
            'full_counts.size, full_counts.station_id, full_counts.item_id, invoice_details.item_id as invoice_item_id, 
            items.name as item_name, item_sizes.countable_unit, item_sizes.countable_size, invoice_details.purchase_package as invoice_purchase_package, 
            invoice_details.unit_price, invoice_details.extended_price, item_packages.qty as package_qty,
            sum(item_sizes.countable_unit * item_packages.qty*full_counts.period_count) as used_fullcount'
        )
        ->whereIn('full_counts.item_id', $items)
        ->where('categories.id', $category_id)
        ->groupBy('full_counts.item_id')
        ->get();

    //dd($fullcounts_data);
    return $fullcounts_data;
}

function get_full_count_with_quality($items, $quality_id)
{
        $fullcounts_data = DB::table('full_counts')
        ->leftJoin('invoice_details', 'full_counts.package_id', '=', 'invoice_details.purchase_package')
        ->join('items', 'full_counts.item_id', '=', 'items.id')
        ->join('item_packages', 'full_counts.package_id', 'item_packages.id')
        ->join('item_sizes', 'full_counts.item_id', '=', 'item_sizes.item_id')
        ->join('qualities', 'items.quality_id', '=', 'qualities.id')
        ->selectRaw(
            'full_counts.size, full_counts.station_id, full_counts.item_id, invoice_details.item_id as invoice_item_id, 
            items.name as item_name, item_sizes.countable_unit, item_sizes.countable_size, invoice_details.purchase_package as invoice_purchase_package, 
            invoice_details.unit_price, invoice_details.extended_price, item_packages.qty as package_qty,
            sum(item_sizes.countable_unit * item_packages.qty*full_counts.period_count) as used_fullcount'
        )
        ->whereIn('full_counts.item_id', $items)
        ->where('qualities.id', $quality_id)
        ->groupBy('full_counts.item_id')
        ->get();

    return $fullcounts_data;
} 

 function get_total_sales_data($item_id)
{
    $total_sales = DB::table('recipe_ingredients')
        ->join('recipes', 'recipe_ingredients.recipe_id', 'recipes.id')
        ->join('recipe_sales', 'recipe_sales.recipe_id', '=', 'recipes.id')
        ->where('recipe_ingredients.item_id', $item_id)
        ->selectRaw(
            'sum(recipe_sales.qty*recipe_ingredients.qty) as sold_qty, 
            recipe_ingredients.qty as sale_ingredients_qty,
            recipe_sales.revenue, recipe_sales.price as sale_price,
            recipe_ingredients.package_id as sale_package_id, 
            recipe_ingredients.uom_text as sale_uom_text'
        )
        ->groupBy('recipe_ingredients.item_id')
        ->get();


    if ($total_sales->count()) {
        return [$total_sales[0]->sold_qty, $total_sales[0]->revenue, $total_sales[0]->sale_price, $total_sales[0]->sale_package_id, $total_sales[0]->sale_uom_text, $total_sales[0]->sale_ingredients_qty];
    }
    return "";
}

function get_total_purchase_data($item_id)
{
    $total_purchases = DB::table('invoice_details')
        ->join('accuflo.item_packages', 'invoice_details.purchase_package', 'item_packages.id')
        ->join('accuflo.item_sizes', 'invoice_details.item_id', '=', 'item_sizes.item_id')
        ->where('invoice_details.item_id', $item_id)
        ->selectRaw(
            'sum(item_sizes.countable_unit*item_packages.qty*invoice_details.purchased_quantity) as used_purchase'
        )
        ->groupBy('invoice_details.item_id')
        ->get();

    if ($total_purchases->count()) {
        return $total_purchases[0]->used_purchase;
    }
    return "";
} 
