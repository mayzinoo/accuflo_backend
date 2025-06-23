<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\FullCount;
use App\Models\Weight;
use App\Models\Session;
use App\Models\Station;
use DB;
use Auth;

class SaveController extends BaseController
{
    public function save(Request $request){
        $user_id=Auth::id();
        $branch_id = auth()->user()->branch_id;
        $station=Station::where('name',$request->station)->where('branch_id',$branch_id)->get();
        if($station->count()){
            if($request->has('noOfCounts')){
                   
                //create new session data
                $session=Session::create([
                    'user_id'      => $user_id,
                    'branch_id'      => $branch_id,
                    'station_id'   => $station[0]->id,
                    'item_id'   => $request->product_id,
                    'item_size_id' => $request->item_size_id,
                    'item_package_id' => $request->packaging_id,
                    'current_period_count' => $request->noOfCounts,
                    'submit_time' => $request->time,
                    'device'      => $request->device
                ]);  
                return response()->json(['success' => true, 'message' => 'Count value is Saved'], 200);
            }
            elseif($request->has('weight')){
                //create new session data
                $session=Session::create([
                    'user_id'      => $user_id,
                    'branch_id'      => $branch_id,
                    'station_id'   => $station[0]->id,
                    'item_id'   => $request->product_id,
                    'item_size_id' => $request->item_size_id,
                    'item_package_id' => $request->packaging_id,
                    'current_period_weight' => $request->weight,
                    'unit'     => $request->unit,
                    'section'  => $request->section,
                    'shelf'    => $request->shelf,
                    'submit_time' => $request->time,
                    'device'      => $request->device
                ]);  
            
                return response()->json(['success' => true, 'message' => 'Weight value is Saved'], 200);
            }
        }else{
            return $this->sendError('Station value error', ['error'=>'Station '.$request->station .' dosen\'t exist for this user']);  
        }

      
    }
}