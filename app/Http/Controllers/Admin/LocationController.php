<?php

namespace App\Http\Controllers\Admin;

use App\Filters\LocationFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateLocationRequest;
use App\Http\Requests\UpdateLocationRequest;
use App\Models\Station;
use App\Models\Section;
use App\Models\Shelf;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:list station'],['only' => 'index']);
    }
    public function index()
    {
        $stations = Station::with(['sections' => function($query){
                                $query->withCount('shelves');
                            }])
                            ->where('branch_id', session()->get('branch_id'))
                            // ->where('period_id', session()->get('period_id'))
                            ->get();
        return view('admin.location.index', compact('stations'));
    }

    // public function create()
    // {
    //     [$stations] = $this->getStation();
    //     $user_id = auth()->user()->id;
    //     return view('admin.location.create', compact('stations','user_id'));
    // }

    // public function store(CreateLocationRequest $request)
    // {

    //     $location=Location::where('name',$request->name)->get();

    //     if(count($location) > 0 && $location[0]->station_id == $request->station_id ){
    //         return redirect()
    //             ->route('location.index')
    //             ->with('info', 'Section name already exists.');
    //     }
    //     else{
    //         $data = $request->validated();
    //         $loc = Location::create($data);
    //         return redirect()
    //         ->route('location.index')
    //         ->with('success', 'Location created successfully.');
    //     }

       
    // }

    // public function edit(Location $location)
    // {

    //     [$stations] = $this->getStation();
    //     return view('admin.location.edit', compact('location','stations'));
    // }

    // public function update(UpdateLocationRequest $request, Location $location)
    // {

    //     $data = $request->validated();

    //     $location->update($data);

    //     return redirect()
    //         ->route('location.index')
    //         ->with('success', 'Location updated successfully.');
    // }

    // public function destroy(Location $location)
    // {
    //     $location->delete();

    //     return redirect()
    //         ->route('location.index')
    //         ->with('success', 'Location deleted successfully.');
    // }

    // private function getStation()
    // {
    //     $stations = Station::get();
    //     return [$stations];
    // }
}
