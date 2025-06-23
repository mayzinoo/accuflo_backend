<?php

namespace App\Http\Controllers\Admin;

use App\Exports\BatchmixExport;
use PDF;
use App\GlobalConstants;
use App\Http\Controllers\Controller;
use App\Models\Batchmix;
use App\Models\Period;
use App\Models\User;
use App\Models\Branch;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class ReportBatchMixController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:batch mix');
    }
    public function index(Request $request)
    {
        $branch_id = session()->get('branch_id');
        $period_id = session()->get('period_id');
        $period = Period::where('id', $period_id)->get()[0];
        //$start_date = Carbon::parse("$period->start_date")->format('Y-m-d H:i:s');
        //$end_date = Carbon::parse("$period->end_date")->format('Y-m-d H:i:s');

        if (isset($request->generate)) {
            $batchmixs = Batchmix::where([['branch_id', $branch_id], ['period_id', $period_id]])
                ->get();
        } else {
            $batchmixs = [];
        }

        $BATCHMIX_WEIGHT_UNIT = GlobalConstants::BATCHMIX_WEIGHT_UNIT;
        $BATCHMIX_VOLUME_UNIT = GlobalConstants::BATCHMIX_VOLUME_UNIT;
        $BATCHMIX_UOM = GlobalConstants::BATCHMIX_UOM;
        $BATCHMIX_UD = GlobalConstants::BATCHMIX_UD;

        return view('admin.report_batchmix.index', compact('batchmixs', 'BATCHMIX_WEIGHT_UNIT', 'BATCHMIX_VOLUME_UNIT', 'BATCHMIX_UOM', 'BATCHMIX_UD'));
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

        $file_name = 'Batch Mix Report for ' . $user->name . ' - ' . $new_start_date[0] . ' ' . $new_start_date[1] . ' to ' . $new_end_date[0] . ' ' . $new_end_date[1] . ' ' . $new_end_date[2];

        return Excel::download(new BatchmixExport, $file_name . '.xlsx');
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

        //$report_start_date = Carbon::parse("$period->start_date")->format('Y-m-d H:i:s');
        //$report_end_date = Carbon::parse("$period->end_date")->format('Y-m-d H:i:s');

        $batchmixs = Batchmix::where([['branch_id', $branch_id], ['period_id', $period_id]])->get();
        $BATCHMIX_WEIGHT_UNIT = GlobalConstants::BATCHMIX_WEIGHT_UNIT;
        $BATCHMIX_VOLUME_UNIT = GlobalConstants::BATCHMIX_VOLUME_UNIT;
        $BATCHMIX_UOM = GlobalConstants::BATCHMIX_UOM;
        $BATCHMIX_UD = GlobalConstants::BATCHMIX_UD;

        $pdf = PDF::loadView('admin.report_batchmix.exportpdf', compact('batchmixs', 'BATCHMIX_WEIGHT_UNIT', 'BATCHMIX_VOLUME_UNIT', 'BATCHMIX_UOM', 'BATCHMIX_UD'));

        $file_name = 'Batch Mix Report for ' . $user->name . ' - ' . $new_start_date[0] . ' ' . $new_start_date[1] . ' to ' . $new_end_date[0] . ' ' . $new_end_date[1] . ' ' . $new_end_date[2] . ' .pdf ';
        return $pdf->download($file_name);
    }
}
