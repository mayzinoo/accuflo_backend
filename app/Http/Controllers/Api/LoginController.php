<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Auth;
use App\GlobalConstants;

class LoginController extends BaseController {

    public function login(Request $request){
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $auth_user = Auth::user(); 
            if(strtolower($auth_user->role)==strtolower(GlobalConstants::USER_TYPES['client'])){
                $success['token'] =  $auth_user->createToken(env('TOKEN'))->plainTextToken; 
                $success['users'] =  $auth_user;
       
                return $this->sendResponse($success, 'Login successfully!');
                            
            }
            else{
                return $this->sendError('Unauthorised.', ['error'=>'Unauthorised :: not client']);
            }
           
        } 
        else{ 
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised :: wrong password']);
        } 
    }
}