<?php

namespace App\Http\Controllers\Admin;

use App\Filters\FullCountFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateFullCountRequest;
use App\Http\Requests\UpdateFullCountRequest;
use App\Models\FullCount;
use App\Models\Item;
use App\Models\Station;
use App\Models\Period;
use DB;
use Illuminate\Http\Request;
class FullCountController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:create full count'],['only' => ['create', 'store']]);
        $this->middleware(['permission:edit full count'],['only' =>['edit', 'update']]);
        $this->middleware(['permission:delete full count'],['only' => 'destroy']);
        $this->middleware(['permission:list full count'],['only' => 'index']);
    }
    public function index(FullCountFilter $filter)
    {
        $period_id = session()->get('period_id');
        $last_period_id = $this->get_last_period_id();
        $branch_id = session()->get('branch_id');
        $last_period_status = get_last_period_status();
        $period_status = get_current_period_status();
        $fullCountLastPeriodQuery = FullCount::where('period_id', $last_period_id)->where('branch_id', $branch_id);
        $fullCountCurrentPeriodQuery = FullCount::where('period_id', $period_id)->where('branch_id', $branch_id);
        $last_period_full_counts = $fullCountLastPeriodQuery->filter($filter)->get();
        $current_period_full_counts = $fullCountCurrentPeriodQuery->filter($filter)->get();
        $combined_full_counts = $last_period_full_counts->merge($current_period_full_counts);
        $used_fullcount_ids = [];
        $full_counts = $combined_full_counts->filter(function($full_count) use($current_period_full_counts, $last_period_id, &$used_fullcount_ids) {
            if($full_count->period_id == $last_period_id){
                $full_count['last_full_count_id'] = $full_count->id;
                $full_count['last_period_count'] = $full_count->period_count;
                $full_count['inventory_level'] = $full_count->period_count;
                $full_count['created_at'] = $full_count->created_at;
                foreach($current_period_full_counts as $data){
                    if(($data['item_id'] == $full_count->item_id) && ($data['station_id'] == $full_count->station_id) && ($data['package_id'] == $full_count->package_id)){
                        $full_count['current_period_count'] = $data['period_count'];
                        $full_count['inventory_level'] = abs($full_count['current_period_count'] - $full_count['last_period_count']);
                        $full_count['up_or_down'] = ($full_count['current_period_count'] > $full_count['last_period_count']) ? 'up' : 'down';
                        $full_count['already_updated'] = ( $full_count->already_updated || $data['already_updated'] ) ? 1 : 0;
                        $full_count['current_full_count_id'] = $data['id'];
                        array_push($used_fullcount_ids, $data['id']);
                    }
                }
                return $full_count;
            }else{
                if(!in_array($full_count->id, $used_fullcount_ids)){
                    $full_count['created_at'] = $full_count->created_at;
                    $full_count['last_period_count'] = null;
                    $full_count['current_period_count'] = $full_count->period_count;
                    $full_count['inventory_level'] = $full_count->period_count;
                    $full_count['up_or_down'] = 'up';
                    $full_count['current_full_count_id'] = $full_count->id;
                    return $full_count;
                }
            }
        });

        $full_counts = $full_counts->sortByDesc('created_at');
        $stations = Station::where('branch_id', $branch_id)->get();
        return view('admin.fullcount.index', compact('full_counts', 'period_status', 'last_period_status', 'period_id', 'last_period_id', 'stations'));
    }

    public function create()
    {
        $period_id = session()->get('period_id');
        [$items, $stations] = $this->getItemsAndStations();
        return view('admin.fullcount.create', compact('items', 'stations', 'period_id'));
    }

    public function store(CreateFullCountRequest $request)
    {
        
        $data = $request->validated();
        $data['size'] = $request->size;
        $data['period_id'] = session()->get('period_id');
        $data['user_id'] = auth()->user()->id;
        $data['branch_id'] = session()->get('branch_id');
        $data['package_id'] = $request->package_id;
        $fullcount = FullCount::create($data);
        return redirect()
                ->route('fullcount.index')
                ->with('success', 'Add Count successfully.');        
    }

    public function edit(FullCount $fullcount)

    {
        [$items, $stations] = $this->getItemsAndStations();
        return view('admin.fullcount.edit', compact('fullcount', 'items', 'stations'));
    }

    public function update(UpdateFullCountRequest $request, FullCount $fullcount)
    {

        $data = $request->validated();
        $fullcount->update($data);

        return redirect()
            ->route('fullcount.index')
            ->with('success', 'Count updated successfully.');
    }

    public function destroy(FullCount $fullcount)
    {
        $fullcount->delete();

        return redirect()
            ->route('fullcount.index')
            ->with('success', 'Count deleted successfully.');
    }
    private function getItemsAndStations()
    {
        $items = Item::get();
        $stations = Station::where('branch_id', session()->get('branch_id'))->get();
        return [$items, $stations];
    }
    public function storePeriodCount(Request $request)
    {
        $data = [];
        $data['item_id'] = $request->item_id;
        $data['size'] = $request->size;
        $data['period_id'] = $request->period_id;
        $data['user_id'] = auth()->user()->id;
        $data['branch_id'] = session()->get('branch_id');
        $data['station_id'] = $request->station_id;
        $data['period_count'] = $request->period_count;
        $data['package_id'] = $request->package_id;
        $data['already_updated'] = 1;
        $fullcount = FullCount::create($data);

        return response([ 'status' => 'success', 'id' => $fullcount->id ]);
    }
    public function updatePeriodCount(Request $request)
    {
        $full_count = FullCount::find($request->id);
        $full_count->update(
                        [
                            'period_count' => $request->period_count,
                            'already_updated' => 1,
                        ]
                    );
        return response([ 'status' => 'success']);
    }
    private function get_last_period_id()
    {
        $last_period = Period::where('id', '<', session()->get('period_id'))
                            ->where('branch_id', session()->get('branch_id'))
                            ->select(DB::raw('MAX(id) as id'))
                            ->first();
        return $last_period->id;
    }
    private function get_count_in_last_period($last_period_id)
    {
        $full_count = FullCount::where('branch_id', session()->get('branch_id'))
                                ->where('period_id', $last_period_id)
                                ->first();
        
        if($full_count != null){
            return $full_count->current_period_count;
        }else{
            return 0;
        }
    }
}
