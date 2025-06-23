<?php

namespace App\Http\Controllers\Admin;

use PDF;
use App\Exports\InventoryExport;
use App\Http\Controllers\Controller;
use App\Models\FullCount;
use App\Models\Period;
use App\Models\User;
use App\Models\Branch;
use App\Models\Weight;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class ReportInventoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:inventory');
    }
    public function index(Request $request)
    {
        $period_id = session()->get('period_id');
        $branch_id = session()->get('branch_id');
        if (isset($request->generate)) {
            $inventory_fullcounts = FullCount::where([['branch_id', $branch_id], ['period_id', $period_id]])
                ->get();
            $inventory_weights = Weight::where([['branch_id', $branch_id], ['period_id', $period_id]])
                ->get();
        } else {
            $inventory_fullcounts = [];
            $inventory_weights = [];
        }

        return view('admin.report_inventory.index', compact('inventory_fullcounts', 'inventory_weights'));
    }

    public function exportExcel()
    {
        $branch_id = session()->get('branch_id');
        $user = Branch::where('id', $branch_id)->first();
        $period_id = session()->get('period_id');
        $period = Period::where('id', $period_id)->get()[0];
        $start_date = Carbon::parse($period->start_date)->format('M-d-Y');
        $new_start_date = explode("-", $start_date);
        $end_date = Carbon::parse($period->end_date)->format('M-d-Y');
        $new_end_date = explode("-", $end_date);

        $file_name = 'Inventory Report - ' . $user->name . ' - ' . $new_start_date[0] . ' ' . $new_start_date[1] . ' to ' . $new_end_date[0] . ' ' . $new_end_date[1] . ' ' . $new_end_date[2];

        return Excel::download(new InventoryExport, $file_name . '.xlsx');
    }

    public function exportPDF()
    {
        $branch_id = session()->get('branch_id');
        $user = Branch::where('id', $branch_id)->first();
        $period_id = session()->get('period_id');
        $period = Period::where('id', $period_id)->get()[0];
        $start_date = Carbon::parse($period->start_date)->format('M-d-Y');
        $new_start_date = explode("-", $start_date);
        $end_date = Carbon::parse($period->end_date)->format('M-d-Y');
        $new_end_date = explode("-", $end_date);

        $inventory_fullcounts = FullCount::where([['branch_id', $branch_id], ['period_id', $period_id]])
            ->get();

        $inventory_weights = Weight::where([['branch_id', $branch_id], ['period_id', $period_id]])
            ->get();

        $pdf = PDF::loadView('admin.report_inventory.exportpdf', compact('inventory_fullcounts', 'inventory_weights'));

        $file_name = 'Inventory Report - ' . $user->name . ' - ' . $new_start_date[0] . ' ' . $new_start_date[1] . ' to ' . $new_end_date[0] . ' ' . $new_end_date[1] . ' ' . $new_end_date[2] . ' .pdf ';
        return $pdf->download($file_name);
    }
}
