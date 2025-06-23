<?php

namespace App\Http\Controllers\Admin;

use App\Exports\InventorySummaryExport;
use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\Invoice;
use App\Models\InvoiceDetails;
use App\Models\Item;
use App\Models\Period;
use App\Models\User;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class ReportInventorySummaryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:invoice summary');
    }
    public function index(Request $request)
    {

        $branch_id = session()->get('branch_id');
        $period_id = session()->get('period_id');

        if (isset($request->generate)) {
            
            $invoices = Invoice::where('period_id', $period_id)->where('branch_id', $branch_id)->get();

            if ($invoices->isEmpty()) {
                $invoices = [];
                $invoice_details = [];
                $class_names = [];
            } else {
                foreach ($invoices as $index => $invoice) {
                    $invoice_details = InvoiceDetails::where('invoice_id', $invoice->id)
                        ->select('item_id', 'extended_price')
                        ->get();

                    $class_names = [];
                    foreach ($invoice_details as $index => $invoice_detail) {
                        if (array_key_exists($invoice_detail->item->class->name, $class_names)) {
                            $class_names[$invoice_detail->item->class->name] = $class_names[$invoice_detail->item->class->name] + $invoice_detail->extended_price;
                        } else {
                            $class_names[$invoice_detail->item->class->name] = $invoice_detail->extended_price;
                        }
                    }
                }
            }
        } else {
            $invoices = [];
            $invoice_details = [];
            $class_names = [];
        }

        return view('admin.report_inventory_summary.index', compact('invoices', 'class_names'));
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

        $file_name =  $user->name . ' - Invoice Summary Report for ' . $new_start_date[0] . ' ' . $new_start_date[1] . ' to ' . $new_end_date[0] . ' ' . $new_end_date[1] . ' ' . $new_end_date[2];

        return Excel::download(new InventorySummaryExport($request->excel_station_id), $file_name . '.xlsx');
    }
}
