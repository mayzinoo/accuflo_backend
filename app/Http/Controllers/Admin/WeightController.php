<?php

namespace App\Http\Controllers\Admin;

use App\Filters\WeightFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateWeightRequest;
use App\Http\Requests\UpdateWeightRequest;
use App\Models\Item;
use App\Models\Location;
use App\Models\Period;
use App\Models\Station;
use App\Models\Section;
use App\Models\Shelf;
use App\Models\Weight;
use App\Models\ItemPackage;
use App\Models\ItemSize;
use CreateWeightsTable;
use Illuminate\Http\Request;
use DB;

class WeightController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:create weight'],['only' => ['create', 'store']]);
        $this->middleware(['permission:edit weight'],['only' =>['edit', 'update']]);
        $this->middleware(['permission:delete weight'],['only' => 'destroy']);
        $this->middleware(['permission:list weight'],['only' => 'index']);
    }
    public function index(WeightFilter $filter)
    {
        $period_id = session()->get('period_id');
        $last_period_id = $this->get_last_period_id();
        $branch_id = session()->get('branch_id');
        $last_period_status = get_last_period_status();
        $period_status = get_current_period_status();
        $weightLastPeriodQuery = Weight::where('period_id', $last_period_id)->where('branch_id', $branch_id);
        $weightCurrentPeriodQuery = Weight::where('period_id', $period_id)->where('branch_id', $branch_id);
        $last_period_weights = $weightLastPeriodQuery->filter($filter)->get();
        $current_period_weights = $weightCurrentPeriodQuery->filter($filter)->get();
        $combined_weights = $last_period_weights->merge($current_period_weights);
        $used_weight_ids = [];
        $weights = $combined_weights->filter(function($weight) use($current_period_weights, $last_period_id, &$used_weight_ids) {
            if($weight->period_id == $last_period_id){
                $weight['created_at'] = $weight->created_at;
                $weight['last_weight_id'] = $weight->id;
                $weight['last_weight'] = $weight->weight;
                $weight['volume_difference'] = $weight->weight;
                foreach($current_period_weights as $data){
                    if(($data['item_id'] == $weight->item_id) && ($data['station_id'] == $weight->station_id) && ($data['package_id'] == $weight->package_id)){
                        $weight['current_weight'] = $data['weight'];
                        $weight['volume_difference'] = abs($weight['current_weight'] - $weight['last_weight']);
                        $weight['up_or_down'] = ($weight['current_weight'] > $weight['last_weight']) ? 'up' : 'down';
                        $weight['already_updated'] = ( $weight->already_updated || $data['already_updated'] ) ? 1 : 0;
                        $weight['current_weight_id'] = $data['id'];
                        array_push($used_weight_ids, $data['id']);
                    }
                }
                return $weight;
            }else{
                if(!in_array($weight->id, $used_weight_ids)){
                    $item_package=ItemPackage::find($weight->package_id);
                    $formatted_volume_diff=0;
                    if($item_package){
                        $item_size=ItemSize::find($item_package->item_size_id);
                        $V2=isset($item_size->density) ? ($weight->weight-$item_size->empty_weight)/$item_size->density : 0;
                        $V1=0;
                        $volume_diff=$V2-$V1;
                        $converted_volume_diff=floatval($volume_diff);
                        // $formatted_volume_diff=round($volume_diff);
                        $formatted_volume_diff=number_format($converted_volume_diff,1,".","");
                    
                        //get countable size 
                        preg_match('/([a-zA-Z]*[a-zA-Z])/', $weight->size, $matches);
                        $countable_size=$matches;
                        if(count($countable_size)){
                            $countable_size=$matches[0];
                            if(strtolower($countable_size)=='l'){
                                $formatted_volume_diff=$formatted_volume_diff*1000;
                            }
                        }                       
                    }
                    $weight['created_at'] = $weight->created_at;
                    $weight['last_weight'] = null;
                    
                    $weight['current_weight'] = $weight->weight;
                   
                    $weight['volume_difference'] = $formatted_volume_diff;
                    $weight['up_or_down'] = 'up';
                    $weight['current_weight_id'] = $weight->id;
                    return $weight;
                }
            }
        });

        $weights = $weights->sortByDesc('created_at');

        $stations = Station::where('branch_id', $branch_id)->get();
        return view('admin.weight.index', compact('weights', 'period_status', 'last_period_status', 'period_id', 'last_period_id', 'stations'));
    }

    public function create()
    {
        //$period = Period::orderBy('id', 'desc')->first();
        $period_id = session()->get('period_id');

        [$items, $stations] = $this->getItemsAndStations();
        return view('admin.weight.create', compact('items', 'stations', 'period_id'));
    }

    public function store(CreateWeightRequest $request)
    {
        $data = $request->validated();
        $section = Section::where('station_id', $request->station_id)->first();
        $shelves = Shelf::where('station_id', $request->station_id)->first();
        $data['item_id'] = $request->item_id;
        $data['station_id'] = $request->station_id;
        $data['section_id'] = $section ? $section->id : null;
        $data['shelf_id'] = $shelves ? $shelves->id : null;
        $data['period_id'] = session()->get('period_id');
        $data['branch_id'] = session()->get('branch_id');
        $data['user_id'] = auth()->user()->id;
        $data['unit_id'] =  $request->unit_id;
        $data['weight'] =  $request->weight;
        $data['package_id'] =  $request->package_id;
        $data['size'] =  $request->size;
        $weight = Weight::create($data);
        return redirect()
                ->route('weight.index')
                ->with('success', 'Add Weights successfully.');
    }

    public function edit(Weight $weight)

    {
        [$items, $stations] = $this->getItemsAndStations();
        return view('admin.weight.edit', compact('weight', 'items', 'stations'));
    }

    public function update(UpdateWeightRequest $request, Weight $weight)
    {
        $data = $request->validated();
        $weight->update($data);

        return redirect()
            ->route('weight.index')
            ->with('success', 'Weight updated successfully.');
    }

    public function destroy(Weight $weight)
    {
        $weight->delete();

        return redirect()
            ->route('weight.index')
            ->with('success', 'Weight deleted successfully.');
    }

    private function getItemsAndStations()
    {
        $items = Item::get();
        $stations = Station::get();
        return [$items, $stations];
    }

    public function checkLocation(Request $request)
    {
        $locations = Location::where('station_id', 'LIKE', '%' . $request['id'] . '%')->get();
        return $locations;
    }

    private function get_last_period_id()
    {
        $last_period = Period::where('id', '<', session()->get('period_id'))
                            ->where('branch_id', session()->get('branch_id'))
                            ->select(DB::raw('MAX(id) as id'))
                            ->first();
        return $last_period->id;
    }
    public function storeWeight(Request $request)
    {
        $data = [];
        $data['item_id'] = $request->item_id;
        $data['station_id'] = $request->station_id;
        $data['section_id'] = $request->section_id;
        $data['shelf_id'] = $request->shelf_id;
        $data['period_id'] = $request->period_id;
        $data['unit_id'] = $request->unit_id;
        $data['branch_id'] = session()->get('branch_id');
        $data['user_id'] = auth()->user()->id;
        $data['size'] = $request->size;
        $data['weight'] = $request->weight;
        $data['already_updated'] = 1;
        $data['package_id'] = $request->package_id;
        $fullcount = Weight::create($data);

        return response([ 'status' => 'success', 'id' => $fullcount->id]);
    }
    public function updateWeight(Request $request)
    {
        $weight = Weight::find($request->id);
        $weight->update(
                        [
                            'weight' => $request->weight,
                            'already_updated' => 1,
                        ]
                    );
        return response([ 'status' => 'success']);
    }
}
