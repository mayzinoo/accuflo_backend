<?php

namespace App\Http\Controllers\Admin;

use App\Filters\ClassFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateClassRequest;
use App\Http\Requests\UpdateClassRequest;
use App\Models\Category;
use App\Models\Classes;
use App\GlobalConstants;
use App\Models\Quality;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:create class'],['only' => ['create', 'store']]);
        $this->middleware(['permission:edit class'],['only' =>['edit', 'update']]);
        $this->middleware(['permission:delete class'],['only' => 'destroy']);
        $this->middleware(['permission:list class'],['only' => 'index']);
    }

    public function index(ClassFilter $filter)
    {
        $classQuery = Classes::query();

        $classes = $classQuery->filter($filter)->latest('id')->paginate();

        return view('admin.class.index', compact('classes'));
    }

    public function create()
    {
        $liquor_types=GlobalConstants::CLASS_LIQUOR_TYPE;
        
        return view('admin.class.create',compact('liquor_types'));
    }

    public function store(CreateClassRequest $request)
    {

        $data = $request->validated();
        $class = Classes::create($data);

        return redirect()
            ->route('class.index')
            ->with('success', 'Class created successfully.');
    }

    public function edit(Classes $class)
    {
        $liquor_types=GlobalConstants::CLASS_LIQUOR_TYPE;
        return view('admin.class.edit', compact('class','liquor_types'));
    }

    public function update(UpdateClassRequest $request, Classes $class)
    {

        $data = $request->validated();

        $class->update($data);

        return redirect()
            ->route('class.index')
            ->with('success', 'Class updated successfully.');
    }

    public function destroy(Classes $class)
    {
        $class->delete();

        return redirect()
            ->route('class.index')
            ->with('success', 'Class deleted successfully.');
    }

    public function searchbyname()
    {
        if (request()->expectsJson() && request('q')) {
            $term = request('q');
            $classQuery = Classes::select('id', 'name as text');

            $classes = $classQuery
                            ->where('name', 'LIKE', "%{$term}%")
                            ->get();

            return response($classes);
        }

        abort(404);
    }

    public function checkCategory(Request $request)
    {
        $category = Category::where('class_id', 'LIKE', '%' . $request['id'] . '%')->get();
        return $category;
    }

    public function checkQuality(Request $request)
    {
        $quality = Quality::where('class_id', 'LIKE', '%' . $request['id'] . '%')->get();
        return $quality;
    }

    public function checkType(Request $request)
    {
        $type = Classes::where('id', 'LIKE', '%' . $request['id'] . '%')->get()[0];
        return $type;
    }
}
