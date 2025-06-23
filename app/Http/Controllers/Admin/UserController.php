<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Filters\UserFilter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Spatie\Permission\Models\Role;
use App\Models\Period;
use App\Models\Company;
use App\Models\Branch;
use App\Models\Permission;
use App\GlobalConstants;
use Carbon\Carbon;
use Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:create user'],['only' => ['create', 'store']]);
        $this->middleware(['permission:edit user'],['only' =>['edit', 'update']]);
        $this->middleware(['permission:delete user'],['only' => 'destroy']);
        $this->middleware(['permission:list user'],['only' => 'index']);
    }
    public function index(UserFilter $filter)
    {
        $userQuery = User::query();

        if(Auth::user()->role == 'client'){
            $userQuery = $userQuery->where('company_id', Auth::user()->company_id)->where('role', 'client');
        }        

        $users = $userQuery->filter($filter)->latest('id')->paginate();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        if(Auth::user()->role == 'client'){
            $companies = Company::where('id', Auth::user()->company_id)->get();
            $branches = Branch::where('company_id', Auth::user()->company_id)->get();
        }else{
            $companies = Company::all();
            $branches = Branch::all();
        }
        
        $permission_roles = Role::pluck('name','name')->all();
        return view('admin.users.create', compact('companies', 'branches', 'permission_roles'));
    }

    public function store(CreateUserRequest $request)
    {
        $data = $request->validated();
       
        $data['password'] = bcrypt($request->password);
        
        $user = User::create($data);
        
        if($user->role == "super_admin" || $user->role == "admin"){
            $permission_role = Permission::pluck('name');
            $user->givePermissionTo($permission_role);
        }else{
            $role = $request->permission_role;
            $user->syncRoles($role);
        }

        return redirect()
            ->route('users.index')
            ->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        if(Auth::user()->role == 'client'){
            $companies = Company::where('id', Auth::user()->company_id)->get();
            $branches = Branch::where('company_id', Auth::user()->company_id)->get();
        }else{
            $companies = Company::all();
            $branches = Branch::all();
        }
        $permission_roles = Role::pluck('name','name')->all();
        return view('admin.users.edit', compact('user', 'companies', 'branches', 'permission_roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();
        $data['password']   = ($data['password'] === null) ? $user->password : bcrypt($data['password']);

        $user->update($data);

        $user->syncPermissions([]);
        if($user->role == "super_admin" || $user->role == "admin"){
            $permission_role = Permission::pluck('name');
            $user->givePermissionTo($permission_role);
        }else{
            $role = $request->permission_role;
            $user->syncRoles($role);
        }


        return redirect()
            ->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
            
        return redirect()
        ->route('users.index')
        ->with('success', 'User deleted successfully.');
    }

    public function lastAccessCustomerId(Request $request)
    {
        return $request->last_access_customer_id;
        User::where('branch_id', '!=', $request->last_access_customer_id)->update(['last_access_customer_id' => null]);
        User::where('branch_id', $request->last_access_customer_id)->update(['last_access_customer_id' => 1]);
        return response([ 'status' => 'Successfully Updated', 'data' => $request->last_access_customer_id ]); 
    }
}
