<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {   
        $permissions = Permission::paginate();
        return view('admin.permission.index', compact('permissions'));
    }
    public function create()
    {
        return view('admin.permission.create');
    }
    public function store(Request $request)
    {   
        $request->validate([
            'name' => 'required|unique:permissions,name',
            'title' => 'required'
        ]);

        Permission::create(['name' => $request->name, 'title' => $request->title ]);
        
        return redirect()
            ->route('permissions.index')
            ->with('success', 'permission created successfully.');
    }
    public function edit(Permission $permission)
    {
        return view('admin.permission.edit', compact('permission'));
    }
    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name,'.$permission->id,
            'title' => 'required'
        ]);

        $permission->update(['name' => $request->name, 'title' => $request->title ]);

        return redirect()
            ->route('permissions.index')
            ->with('success', 'permission updated successfully.');
    }
    public function destroy(Permission $permission)
    {
        $permission->delete();

        return redirect()
            ->route('permissions.index')
            ->with('success', 'permission deleted successfully.');
    }
}
