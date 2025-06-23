<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function store(Request $request){
        $data = json_decode($request->data);
        foreach($data as $key => $val){
            session()->put($key,$val);
        }
        return response([ 'status' => 'Successfully Created', 'data' => session()->all() ]);
    }
}
