<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Period;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\GlobalConstants;


class FinalizePeriodController extends Controller
{
    public function index()
    {
        $branch_id = session()->get('branch_id');

        $periods = Period::where('branch_id', $branch_id)->orderBy('id', 'DESC')->get();
        $max_period = Period::where('branch_id', $branch_id)
                        ->select(DB::raw('MAX(id) as id'))
                        ->first();
        $can_closed_period = Period::where('branch_id', $branch_id)
                                ->where('status', GlobalConstants::PERIOD_STATUS['open'])
                                ->select(DB::raw('MIN(id) as id'))
                                ->first();
        $reopen_period = Period::where('branch_id', $branch_id)
                                ->where('status', GlobalConstants::PERIOD_STATUS['close'])
                                ->select(DB::raw('MAX(id) as id'))
                                ->first();
        $period_status = GlobalConstants::PERIOD_STATUS;                   
        return view('admin.period.index', compact('periods', 'can_closed_period', 'reopen_period', 'period_status', 'max_period'));
    }

    public function store(Request $request)
    {
        $old_period = Period::find($request->old_period_id);
        Period::where('id', $request->old_period_id)
                ->update(['status' => GlobalConstants::PERIOD_STATUS['close']]);

        $max_period = Period::where('branch_id', session()->get('branch_id'))
                ->select(DB::raw('MAX(id) as id'))
                ->first();

        $period_count = Period::where('branch_id', session()->get('branch_id'))
                ->count();

        if(( $max_period->id == $request->old_period_id)  || ($period_count == 1)){
            $period['user_id'] = auth()->user()->id;
            $period['branch_id'] = session()->get('branch_id');
            $period['start_date'] = Carbon::parse($old_period->end_date)->addDay(1)->format('Y-m-d H:i:s');
            $period['end_date'] = Carbon::parse($old_period->end_date)->addDay(1)->format('Y-m-d H:i:s');
            $period['status'] = GlobalConstants::PERIOD_STATUS['open'];
            Period::create($period);
        }
        
        return redirect()
                ->route('periods.index')
                ->with('success', 'Period Created successfully.');
    }

    public function update(Request $request, $id){
        switch($request->action_type){
            case 'reopen':
                Period::where('id', $id)
                        ->update(['status' => GlobalConstants::PERIOD_STATUS['open']]);
                return response([ 'status' => 'success', 'id' => $id]);
                break;
            case 'change_date':
                Period::where('id', $id)
                        ->update(['start_date' => $request->start_date, 'end_date' => $request->end_date ]);
                return redirect()
                        ->route('periods.index')
                        ->with('success', 'Period Updated successfully.');
                break;
            default:
                return response([ 'status' => 'fail']);
        }
    }

    public function destroy($id)
    {
        Period::where('id', $id)->delete();

        return redirect()
                ->route('periods.index')
                ->with('success', 'Period Removed successfully.');
    }

    public function availablePeriodDates()
    {
        $start_date = null;
        $end_date = null;
        $start_date_condition = Period::where('id', '<', request()->id)
                            ->where('branch_id', session()->get('branch_id'))
                            ->select(DB::raw('MAX(id) as id'))
                            ->first();

        $end_date_condition = Period::where('id', '>', request()->id)
                            ->where('branch_id', session()->get('branch_id'))
                            ->select(DB::raw('MIN(id) as id'))
                            ->first();

        if($start_date_condition->id){
            $start_date_data = Period::where('id', $start_date_condition->id)
                                ->select('end_date')
                                ->first();
            $period_count = Period::where('branch_id', session()->get('branch_id'))->count();
            if($start_date_data){
                if($period_count > 1){
                    $start_date = Carbon::parse($start_date_data->end_date)->addDay(1)->format('Y-m-d H:i:s');
                }else{
                    $start_date = Carbon::parse($start_date_data->end_date)->format('Y-m-d H:i:s');
                }
            }
        }else{
            $period = Period::where('id', request()->id)->first();
            $start_date = Carbon::parse($period->start_date)->format('Y-m-d H:i:s');
        }

        if($end_date_condition->id){
            $end_date_data = Period::where('id', $end_date_condition->id)
                            ->select('start_date')
                            ->first();
            if($end_date_data){
                $end_date = Carbon::parse($end_date_data->start_date)->subDay(1)->format('Y-m-d H:i:s');
            }
        }
       
        return response(['status' => 'success', 'start_date' => $start_date, 'end_date' => $end_date ]);
    }
    public function periodDatesByUserId(){
        $html = '';
        $latest_period_id = '';
        $customer_id = request()->user_id;
        $branch_id = session()->get('branch_id');
        $periods = Period::where('branch_id', $branch_id)->orderBy('created_at', 'desc')->get();
        foreach($periods as $key => $period){
            $html .= "<a class='dropdown-item' href='#' id='period_" . $period->id . "' data-id='" . $period->id . "' data-status='" . $period->status . "' data-last_period='". date_range($period->start_date, $period->end_date) . "'>";

            if($period->status == 1){
                $html .= "<i class='fa fa-unlock'></i>&nbsp;&nbsp;";
            }else{
                $html .= "<i class='fa fa-lock'></i>&nbsp;&nbsp;";
            }
            $html .= date_range($period->start_date, $period->end_date);
            $html .= "</a>";
            if($period->id == session()->get('period_id')){
                $latest_period_id = $period->id;
            }elseif($key == 0){
                $customer_period = Period::where('branch_id', $branch_id)
                                    ->where('last_access_period_id', 1)
                                    ->first();
                if($customer_period){
                    $latest_period_id = $customer_period->id;
                }else{
                    $latest_period_id = $period->id;
                }
            }
        }
        return response(['html' => $html, 'latest_period_id' => $latest_period_id ]);
    }

    public function lastAccessPeriodId(Request $request)
    {
        Period::where('id', '!=', $request->last_access_period_id)->update(['last_access_period_id' => null]);
        Period::where('id', $request->last_access_period_id)->update(['last_access_period_id' => 1]);
        return response([ 'status' => 'Successfully Updated', 'data' => $request->last_access_period_id ]); 
    }
}
