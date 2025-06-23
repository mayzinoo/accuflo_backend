<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Models\Classes;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:create category'],['only' => ['create', 'store']]);
        $this->middleware(['permission:edit category'],['only' =>['edit', 'update']]);
        $this->middleware(['permission:delete category'],['only' => 'destroy']);
        $this->middleware(['permission:list category'],['only' => 'index']);
    }
    public function index()
    {
        $classes = Classes::with('categories')->get();
        return view('admin.category.index', compact('classes'));
    }

    public function create()
    {
        [$classes] = $this->getClass();
        return view('admin.category.create', compact('classes'));
    }

    public function store(CreateCategoryRequest $request)
    {
        $data = $request->validated();
        $category = Category::create($data);
        return redirect()
            ->route('category.index')
            ->with('success', 'Category created successfully.');
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $data = $request->validated();
        $category->update($data);
        return redirect()
            ->route('category.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()
            ->route('category.index')
            ->with('success', 'Category deleted successfully.');
    }


    private function getClass()
    {
        $classes = Classes::get();
        return [$classes];
    }
}
