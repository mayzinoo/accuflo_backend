<?php

namespace App\Http\Controllers\Admin;

use App\Exports\DetailedVarianceExport;
use App\Exports\SummaryVarianceExport;
use App\Exports\VarianceExport;
use App\GlobalConstants;
use PDF;
use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\FullCount;
use App\Models\Item;
use App\Models\Period;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReportVarianceController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:variance');
    }
    public function index(Request $request)
    {
        $branch_id = session()->get('branch_id');
        $period_id = session()->get('period_id');
        $report_types = GlobalConstants::REPORT_TYPE;
        $report_types_1 = GlobalConstants::REPORT_TYPE_1;

        if ((isset($request->category_quality_id) && isset($request->detail_summary_id))) {

            $item_id = FullCount::select('item_id')
                ->where('period_id', $period_id)
                ->where('branch_id', $branch_id)
                ->pluck('item_id')->toArray();

            $items = Item::whereIn('id',  $item_id)
                ->pluck('id');

            $classes = Item::whereIn('id',  $item_id)
                ->select('class_id')
                ->where('class_id', '>' , 0)
                ->groupBy('class_id')
                ->get();

            if (
                $request->category_quality_id == 'Category' &&
                $request->detail_summary_id == 'Detailed'
            ) {

                $categories = Item::whereIn('id',  $item_id)
                    ->select('category_id')
                    ->groupBy('category_id')
                    ->get();
                $category_quality_status = 'Category';
                $detail_summary_status = 'Detailed';
                $qualities = [];
            } else if (
                $request->category_quality_id == 'Quality' &&
                $request->detail_summary_id == 'Detailed'
            ) {
                $qualities = Item::whereIn('id',  $item_id)
                    ->select('quality_id')
                    ->groupBy('quality_id')
                    ->get();
                $category_quality_status = 'Quality';
                $detail_summary_status = 'Detailed';
                $categories = [];
            } else if (
                $request->category_quality_id == 'Category' &&
                $request->detail_summary_id == 'Summary'
            ) {
                $categories = Item::whereIn('id',  $item_id)
                    ->select('category_id')
                    ->groupBy('category_id')
                    ->get();
                $category_quality_status = 'Category';
                $detail_summary_status = 'Summary';
                $qualities = [];
            } else if (
                $request->category_quality_id == 'Quality' &&
                $request->detail_summary_id == 'Summary'
            ) {
                $qualities = Item::whereIn('id',  $item_id)
                    ->select('quality_id')
                    ->groupBy('quality_id')
                    ->get();
                $category_quality_status = 'Quality';
                $detail_summary_status = 'Summary';
                $categories = [];
            }
        } else {
            $items = [];
            $classes = [];
            $categories = [];
            $qualities = [];
            $category_quality_status = '';
            $detail_summary_status = '';
        }

        return view('admin.report_variance.index', compact('items', 'classes', 'categories', 'qualities', 'category_quality_status', 'detail_summary_status', 'report_types', 'report_types_1'));
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

        if (
            ($request->excel_category_quality_id == 'Category' &&
                $request->excel_detail_summary_id == 'Detailed') ||
            ($request->excel_category_quality_id == 'Quality' &&
                $request->excel_detail_summary_id == 'Detailed')
        ) {
            $file_name =  $user->name . ' - Detailed Variance Report for ' . $new_start_date[0] . ' ' . $new_start_date[1] . ' to ' . $new_end_date[0] . ' ' . $new_end_date[1] . ' ' . $new_end_date[2];
            return Excel::download(new DetailedVarianceExport($request->excel_category_quality_id, $request->excel_detail_summary_id), $file_name . '.xlsx');
        } else {
            $file_name =  $user->name . ' - Summary Variance Report for ' . $new_start_date[0] . ' ' . $new_start_date[1] . ' to ' . $new_end_date[0] . ' ' . $new_end_date[1] . ' ' . $new_end_date[2];
            return Excel::download(new SummaryVarianceExport($request->excel_category_quality_id, $request->excel_detail_summary_id), $file_name . '.xlsx');
        }
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

        if ((isset($request->pdf_category_quality_id) && isset($request->pdf_detail_summary_id))) {

            $item_id = FullCount::select('item_id')->where('period_id', $period_id)->pluck('item_id')->toArray();

            $items = Item::whereIn('id',  $item_id)
                ->pluck('id');

            $classes = Item::whereIn('id',  $item_id)
                ->select('class_id')
                ->where('class_id', '>' , 0)
                ->groupBy('class_id')
                ->get();

            if (
                $request->pdf_category_quality_id == 'Category' &&
                $request->pdf_detail_summary_id == 'Detailed'
            ) {

                $categories = Item::whereIn('id',  $item_id)
                    ->select('category_id')
                    ->groupBy('category_id')
                    ->get();
                $category_quality_status = 'Category';
                $detail_summary_status = 'Detailed';
                $qualities = [];
            } else if (
                $request->pdf_category_quality_id == 'Quality' &&
                $request->pdf_detail_summary_id == 'Detailed'
            ) {
                $qualities = Item::whereIn('id',  $item_id)
                    ->select('quality_id')
                    ->groupBy('quality_id')
                    ->get();
                $category_quality_status = 'Quality';
                $detail_summary_status = 'Detailed';
                $categories = [];
            } else if (
                $request->pdf_category_quality_id == 'Category' &&
                $request->pdf_detail_summary_id == 'Summary'
            ) {
                $categories = Item::whereIn('id',  $item_id)
                    ->select('category_id')
                    ->groupBy('category_id')
                    ->get();
                $category_quality_status = 'Category';
                $detail_summary_status = 'Summary';
                $qualities = [];
            } else if (
                $request->pdf_category_quality_id == 'Quality' &&
                $request->pdf_detail_summary_id == 'Summary'
            ) {
                $qualities = Item::whereIn('id',  $item_id)
                    ->select('quality_id')
                    ->groupBy('quality_id')
                    ->get();
                $category_quality_status = 'Quality';
                $detail_summary_status = 'Summary';
                $categories = [];
            }

        } else {
            $items = [];
            $classes = [];
            $categories = [];
            $qualities = [];
            $category_quality_status = '';
            $detail_summary_status = '';
        }

        if (
            ($request->pdf_category_quality_id == 'Category' &&
                $request->pdf_detail_summary_id == 'Detailed') ||
            ($request->pdf_category_quality_id == 'Quality' &&
                $request->pdf_detail_summary_id == 'Detailed')
        ) {
            $pdf = PDF::loadView('admin.report_variance.detailedexportpdf', compact('items', 'classes', 'categories', 'qualities', 'category_quality_status', 'detail_summary_status'));

            $file_name =  $user->name . ' - Detailed Variance Report for ' . $new_start_date[0] . ' ' . $new_start_date[1] . ' to ' . $new_end_date[0] . ' ' . $new_end_date[1] . ' ' . $new_end_date[2] . ' .pdf ';
    
            return $pdf->download($file_name);
        }else{
            $pdf = PDF::loadView('admin.report_variance.summaryexportpdf', compact('items', 'classes', 'categories', 'qualities', 'category_quality_status', 'detail_summary_status'));

            $file_name =  $user->name . ' - Summary Variance Report for ' . $new_start_date[0] . ' ' . $new_start_date[1] . ' to ' . $new_end_date[0] . ' ' . $new_end_date[1] . ' ' . $new_end_date[2] . ' .pdf ';
    
            return $pdf->download($file_name);
        }

       
    }
}
