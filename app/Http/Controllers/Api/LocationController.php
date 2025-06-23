<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Station;
use App\Models\Section;
use App\Models\Shelf;
use Auth;

class LocationController extends BaseController {
    public function getStations(){
        $branch_id= auth()->user()->branch_id;
        $stations=Station::select('id','name','period_id')->where('branch_id',$branch_id)->get();
        return $stations;
    }
    public function getSections(Request $request){
        
        $branch_id= auth()->user()->branch_id;
        $station_name=$request->station;
        
        $station=Station::where('name',$station_name)->where('branch_id',$branch_id)->get();
        
        $sections=Section::select('id','name','station_id')->where('station_id',$station[0]->id)->get();
        return $sections;
    }
    public function getShelves(Request $request){
        $branch_id= auth()->user()->branch_id;
        $station_name=$request->station;
        $station=Station::where('name',$station_name)->where('branch_id',$branch_id)->get()[0];
        $section_name=$request->section;
        $section=Section::where('name',$section_name)->where('station_id',$station->id)->get()[0];
        $shelves=Shelf::select('station_id','section_id','shelf_name')->where('station_id',$station->id)->where('section_id',$section->id)->get();
        return $shelves;
    }
    
    public function get(Request $request){
        $branch_id= auth()->user()->branch_id;
        $stations=Station::where('branch_id',$branch_id)->get();
        $final_result; $result_station;
        foreach($stations as $station){
            $result_station['stationName']=$station->name;
            $sections=Section::where('station_id',$station->id)->get();
           
            $result_sections=[];
            foreach($sections as $section){
                $result['sectionName']=$section->name;
                $shelf_list=[];
                //get shelf name list
                $shelves=Shelf::where('section_id',$section->id)->get();
                foreach($shelves as $shelf){
                    $shelf_list[]=$shelf->shelf_name;
                }
                $result['shelf']=$shelf_list;
                $result_sections[]=$result;
            }
            $result_station['sections']=$result_sections;
            $final_result[]=$result_station;
          
        }
             
        return $final_result;
      
    }
    public function store(Request $request){
        $branch_id= auth()->user()->branch_id;
        $station=Station::where('name',$request->station)->get()[0];
        $section=Section::where('name',$request->section)->
        where('station_id',$station->id)->get()[0];
        $old_shelf=Shelf::where('station_id',$station->id)->where('section_id',$section->id)
        ->where('shelf_name',$request->shelf)->get();
        if($old_shelf->count()){
            return response()->json(['success' => false, 'message' => 'Same shelf is existed'], 400);
        }
        Shelf::create([
            'station_id' => $station->id,
            'section_id' => $section->id,
            'shelf_name' => $request->shelf
        ]);     
        
        return response()->json(['success' => true, 'message' => 'Shelf is created'], 200);

    }
}