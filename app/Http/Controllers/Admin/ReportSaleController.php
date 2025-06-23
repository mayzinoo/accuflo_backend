<?php

namespace App\Http\Controllers\Admin;

use App\Exports\SaleExport;
use PDF;
use App\Models\Period;
use App\Models\Recipe;
use App\Models\RecipeSale;
use App\Exports\SalesExport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Station;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportSaleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:sales');
    }
    public function index(Request $request)
    {
        $branch_id = session()->get('branch_id');
        $period_id = session()->get('period_id');

        $period = Period::where('id', 'LIKE', '%' . $period_id . '%')->get()[0];
        $start_date = Carbon::parse("$period->start_date")->format('Y-m-d H:i:s');
        $end_date = Carbon::parse("$period->end_date")->format('Y-m-d H:i:s');
        $station_id = isset($request->station_id) ? $request->station_id : 1;
        $stations = Station::where('branch_id', $branch_id)->get();
        if (isset($request->station_id)) {
            $recipes = Recipe::where([['branch_id', $branch_id], ['station_id', $station_id], ['period_id', $period_id]])
                ->get();
        } else {
            $recipes = [];
            $recipe_sales = [];
        }

        return view('admin.report_sale.index', compact('recipes', 'stations', 'station_id'));
    }

    public function exportExcel(Request $request)
    {

        $branch_id = session()->get('branch_id');
        $user = User::where('id', $branch_id)->get()[0];
        $period_id = session()->get('period_id');
        $period = Period::where('id', $period_id)->get()[0];
        $start_date = Carbon::parse($period->start_date)->format('M-d-Y');
        $new_start_date = explode("-", $start_date);
        $end_date = Carbon::parse($period->end_date)->format('M-d-Y');
        $new_end_date = explode("-", $end_date);

        $file_name =  $user->name . ' - Sales Report for ' . $new_start_date[0] . ' ' . $new_start_date[1] . ' to ' . $new_end_date[0] . ' ' . $new_end_date[1] . ' ' . $new_end_date[2];

        return Excel::download(new SalesExport($request->excel_station_id), $file_name . '.xlsx');
    }

    public function exportPDF(Request $request)
    {

        $branch_id = session()->get('branch_id');
        $user = User::where('id', $branch_id)->get()[0];
        $period_id = session()->get('period_id');
        $period = Period::where('id', $period_id)->get()[0];
        $start_date = Carbon::parse($period->start_date)->format('M-d-Y');
        $new_start_date = explode("-", $start_date);
        $end_date = Carbon::parse($period->end_date)->format('M-d-Y');
        $new_end_date = explode("-", $end_date);

        $station_id = $request->pdf_station_id;

        $recipes = Recipe::where([['branch_id', $branch_id], ['period_id', $period_id], ['station_id', $station_id]])
            ->get();

        $pdf = PDF::loadView('admin.report_sale.exportpdf', compact('recipes'));

        $file_name = $user->name . ' - Sale Report for ' . $new_start_date[0] . ' ' . $new_start_date[1] . ' to ' . $new_end_date[0] . ' ' . $new_end_date[1] . ' ' . $new_end_date[2] . ' .pdf ';

        return $pdf->download($file_name);
    }
}
