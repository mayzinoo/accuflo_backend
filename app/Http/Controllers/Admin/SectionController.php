<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateSectionRequest;
use App\Http\Requests\UpdateSectionRequest;
use App\Models\Section;
use App\Models\Shelf;

class SectionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:create section'],['only' => 'store']);
        $this->middleware(['permission:edit section'],['only' =>'update']);
        $this->middleware(['permission:delete section'],['only' =>'destroy']);
    }
    public function store(CreateSectionRequest $request)
    {
        $data = $request->validated();
        Section::create($data);

        return redirect()
                ->route('location.index')
                ->with('success', 'Section created successfully.');
    }
    public function update(UpdateSectionRequest $request,Section $section)
    {
        $data = $request->validated();
        $section->update($data);
        return redirect()
            ->route('location.index')
            ->with('success', 'Section updated successfully.');
    }
    public function destroy(Section $section)
    {
        Shelf::where('section_id', $section->id)->delete();
        $section->delete();

        return redirect()
            ->route('location.index')
            ->with('success', 'Section deleted successfully.');
    }
}
