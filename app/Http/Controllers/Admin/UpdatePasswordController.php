<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateAdminPasswordRequest;
use App\Models\User;


class UpdatePasswordController extends Controller
{
    public function index(){
        return view('auth.passwords.update');
    }
    public function update(UpdateAdminPasswordRequest $request){
        $data=$request->validated();
       
        $data['password']=bcrypt($request->new_password);
        Auth::user()->update($data);         
        Auth::logout();
 
        $request->session()->invalidate();
     
        $request->session()->regenerateToken();
     
        return redirect('/'); 
    }
}
