<?php

namespace App\Http\Controllers\Admin;

use DB;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Models\Permission;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:create role'],['only' => ['create', 'store']]);
        $this->middleware(['permission:edit role'],['only' =>['edit', 'update']]);
        $this->middleware(['permission:delete role'],['only' => 'destroy']);
        $this->middleware(['permission:list role'],['only' => 'index']);
    }
    public function index(Request $request)
    {   
        $roles = Role::orderBy('id','DESC')->paginate();
        return view('admin.roles.index',compact('roles'));
    }

    public function create()
    {
        $main_permissions = Permission::groupBy('title')->get();
        $main_permissions = $main_permissions->map(function($main_permission){
            $permissions = Permission::where('title', $main_permission->title)->get();
            $main_permission['permissions'] = $permissions;
            return $main_permission;
        });
        return view('admin.roles.create', compact('main_permissions'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);

        $role = Role::create(['name' => $request->get('name')]);
        $role->syncPermissions($request->get('permission'));

        return redirect()->route('roles.index')
                        ->with('success','Role created successfully');
    }

    public function show(Role $role)
    {
        $role = $role;
        $rolePermissions = $role->permissions;

        return view('roles.show', compact('role', 'rolePermissions'));
    }

    public function edit(Role $role)
    {
        $role = $role;
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        $main_permissions = Permission::groupBy('title')->get();
        $main_permissions = $main_permissions->map(function($main_permission){
            $permissions = Permission::where('title', $main_permission->title)->get();
            $main_permission['permissions'] = $permissions;
            return $main_permission;
        });

        return view('admin.roles.edit', compact('role', 'rolePermissions', 'main_permissions'));
    }

    public function update(Role $role, Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,'.$role->id,
            'permission' => 'required'
        ]);

        $role->update($request->only('name'));

        $role->syncPermissions($request->get('permission'));

        return redirect()->route('roles.index')
                    ->with('success','role updated successfully');
    }

    public function destroy(Role $role)
    {
        $role->delete();

        return redirect()->route('roles.index')
                    ->with('success','role deleted successfully');
    }
}
