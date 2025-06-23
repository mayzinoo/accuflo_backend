<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateQualityRequest;
use App\Http\Requests\UpdateQualityRequest;
use App\Models\Classes;
use App\Models\Quality;
use Illuminate\Http\Request;

class QualityController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:create quality'],['only' => ['create', 'store']]);
        $this->middleware(['permission:edit quality'],['only' =>['edit', 'update']]);
        $this->middleware(['permission:delete quality'],['only' => 'destroy']);
        $this->middleware(['permission:list quality'],['only' => 'index']);
    }
    public function index()
    {
        $classes = Classes::with('qualities')->get();
        return view('admin.quality.index', compact('classes'));
    }

    public function create()
    {
        [$classes] = $this->getClass();
        return view('admin.quality.create', compact('classes'));
    }

    public function store(CreateQualityRequest $request)
    {
        $data = $request->validated();
        $quality = Quality::create($data);
        return redirect()
            ->route('quality.index')
            ->with('success', 'Quality created successfully.');
    }

    public function update(UpdateQualityRequest $request, Quality $quality)
    {
        $data = $request->validated();
        $quality->update($data);
        return redirect()
            ->route('quality.index')
            ->with('success', 'Quality updated successfully.');
    }

    public function destroy(Quality $quality)
    {
        $quality->delete();
        return redirect()
            ->route('quality.index')
            ->with('success', 'Quality deleted successfully.');
    }



    private function getClass()
    {
        $classes = Classes::get();
        return [$classes];
    }
}
