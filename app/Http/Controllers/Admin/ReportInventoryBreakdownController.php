<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Classes;
use App\Models\FullCount;
use App\Models\Invoice;
use App\Models\InvoiceDetails;
use App\Models\Item;
use App\Models\ItemPackage;
use App\Models\ItemSize;
use App\Models\Station;
use App\Models\Weight;
use App\Models\Period;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Builder\Class_;

class ReportInventoryBreakdownController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:inventory breakdown');
    }
    public function index(Request $request)
    {
        $branch_id = session()->get('branch_id');
        $period_id = session()->get('period_id');
        $last_period = Period::where('id', '<', $period_id)
        ->where('branch_id', $branch_id)
        ->select(DB::raw('MAX(id) as id'))
        ->first();
        $last_period_id = $last_period->id;
        if (isset($request->generate)) {

            $stations = Station::where([['branch_id', $branch_id], ['period_id', $period_id]])->get();

            $fullcount_item_id = FullCount::select('item_id')->pluck('item_id')->toArray();
            $weight_item_id=Weight::select('item_id')->pluck('item_id')->toArray();
            
            $item_id=array_unique(array_merge($fullcount_item_id,$weight_item_id));
            $remaining_weight_item_id=array_diff($item_id,$fullcount_item_id);
            $items = Item::whereIn('id',  $item_id)
                ->pluck('id');

            $classes = Item::whereIn('id',  $item_id)
                ->select('class_id')
                ->groupBy('class_id')
                ->get();

            $categories = Item::whereIn('id',  $item_id)
                ->select('category_id')
                ->groupBy('category_id')
                ->get();

        } else {
            $stations = [];
            $items = [];
            $classes = [];
            $categories = [];
            $remaining_weight_item_id=[];
        }

        return view('admin.report_inventory_berakdown.index', compact('stations', 'items', 'classes', 'categories','remaining_weight_item_id','last_period_id','period_id'));
    }
}
