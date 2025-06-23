<?php

namespace App\Http\Controllers\Admin;

use App\Exports\PurchaseSummaryExport;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Classes;
use App\Models\Invoice;
use App\Models\InvoiceDetails;
use App\Models\Item;
use App\Models\Period;
use App\Models\User;
use App\Models\Branch;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class ReportPurchaseSummaryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:purchase summary');
    }
    public function index(Request $request)
    {
        $branch_id = session()->get('branch_id');
        $period_id = session()->get('period_id');

        if (isset($request->from_date) && isset($request->to_date)) {
            
            $item_id = InvoiceDetails::whereDate('created_at', '>=', $request->from_date)
            ->whereDate('created_at', '<=', $request->to_date)
            ->select('item_id')->pluck('item_id')->toArray();

            $items = Item::whereIn('id',  $item_id)
                ->select('id')
                ->get();

            $classes = Item::whereIn('id',  $item_id)
                ->select('class_id')
                ->groupBy('class_id')
                ->get();

            $categories = Item::whereIn('id',  $item_id)
                ->select('category_id')
                ->groupBy('category_id')
                ->get();
        } else {
            $items = [];
            $classes = [];
            $categories = [];
        }

        return view('admin.report_purchase_summary.index', compact('items', 'classes', 'categories'));
    }

    public function exportExcel(Request $request)
    {
        $branch_id = session()->get('branch_id');
        $user = Branch::where('id', $branch_id)->first();
        $period_id = session()->get('period_id');
        $period = Period::where('id', $period_id)->get()[0];
        $start_date = Carbon::parse($period->start_date)->format('M-d-Y');
        $new_start_date = explode("-", $start_date);
        $end_date = Carbon::parse($period->end_date)->format('M-d-Y');
        $new_end_date = explode("-", $end_date);

        $file_name = 'Purchase Report for ' . $user->name . ' - ' . $new_start_date[0] . ' ' . $new_start_date[1] . ' to ' . $new_end_date[0] . ' ' . $new_end_date[1] . ' ' . $new_end_date[2];

        return Excel::download(new PurchaseSummaryExport($request->excel_form_date, $request->excel_to_date), $file_name . '.xlsx');
    }
}
