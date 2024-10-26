<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\AppResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use AppResponse;
    
    public function __construct(Request $request)
    {
        $this->middleware('auth:api',['except'=>['login','register']]);
    }
   
    public function register(Request $request){


        $validation = Validator::make($request->all(),[
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required|confirmed',
            'roles'=>'required'

        ]);


        if($validation->fails()){
            return $this->errorResponse('Validation Failed',400,$validation->errors()->toArray());

        }

        $user = User::create(array_merge(
            $validation->validated(),
            ['password'=>bcrypt($request->password)],

        ));
      
        $user->syncRoles($request->roles);
       
        return $this->successResponse('Register Succesfully Added',200);


    }

    public function login(Request $request){

        $validation = Validator::make($request->all(),[
       
            'email'=>'required|email',
            'password'=>'required',

        ]);

        if($validation->fails()){
            return $this->errorResponse('Validation Failed',400,$validation->errors()->toArray());

        }

        if(!$token=auth()->attempt($validation->validated())){
            return $this->errorResponse('Invalid Credentials',401);
        }
        return $this->createToken($token);


    }
    public function createToken($token){

        return response()->json([
           'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth()->factory()->getTTL(),
            'user'=>auth()->user(),
        ]);
    }

    public function logout(){
        auth()->logout();
        return $this->successResponse('Logged Out Successfully',200);
    }

}
