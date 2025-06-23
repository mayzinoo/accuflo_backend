<?php

namespace App\Http\Controllers\Admin;

use App\Filters\StationFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateStationRequest;
use App\Http\Requests\UpdateStationRequest;
use App\Models\Station;
use App\Models\Section;
use App\Models\Shelf;
use App\Models\PriceLevel;
use Illuminate\Http\Request;

class StationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:create station'],['only' => 'store']);
        $this->middleware(['permission:edit station'],['only' =>'update']);
        $this->middleware(['permission:delete station'],['only' =>'destroy']);
    }
    public function store(CreateStationRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->user()->id;
        $data['branch_id'] = session()->get('branch_id');

        $data['period_id'] = session()->get('period_id');
        
        $station = Station::create($data);
        
        $section['name'] = "Main";
        $section['station_id'] = $station->id;
        Section::create($section);
        
        //price level create
        $price_level_data=PriceLevel::create([
            'station_id' => $station->id,
            'client_id'  => $data['user_id'],
            'period_id'  => $data['period_id'],
            'level'      => 'Regular',
            'type'       => 0
        ]
        );

        return redirect()
                ->route('location.index')
                ->with('success', 'Station created successfully.');
    }

    public function update(UpdateStationRequest $request,Station $station)
    {
        $data = $request->validated();
        $station->update($data);
        return redirect()
            ->route('location.index')
            ->with('success', 'Station updated successfully.');
    }

    public function destroy(Station $station)
    {
        Shelf::where('station_id', $station->id)->delete();
        Section::where('station_id', $station->id)->delete();
        $station->delete();

        return redirect()
            ->route('location.index')
            ->with('success', 'Station deleted successfully.');
    }
}
