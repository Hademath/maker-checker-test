<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;


class AuthController extends Controller{
    public function __construct(){
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
        $this->result = (object) array(
            "status" => false,
            "status_code" => 200,
            "message" => null,
            "data"=> (object) null,
            "token" => null,
            "debug" => null
        );
       
    }


    public function Register(Request $request){
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'email' => 'required|email|unique:users',
            'phone' => 'required|unique:users',
            'password'=> 'required|required|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
            // $this->result->message = $validator->errors()->get(['email', 'name']);
        }else{
            $user = User::create([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'email'=>$request->email,
                'phone' => $request->phone,
                'password' => bcrypt($request->password),
                ]
            );
           
            if($user){
                $this->result->status = true;
                $this->result->message = "Successful";
                $this->result->data = $user;
            }else{
                $this->result->status = false;
                $this->result->message = "Not Successfull";
            }
           
       }
        return response()->json($this->result);
    }

    public function login(Request $request){
    	$validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        if (! $token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Invalid login credentials'], 401);
        }else{
            $this->result->status = true;
            $this->result->message = "Login Successful";
            $this->result->data = $token;
        }
        return $this->createNewToken($token);
    }


       protected function createNewToken($token)
    {
        return response()->json([
            'message'=> 'Successfully logged in',
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }


    public function refresh()
    {
        return $this->createNewToken(auth()->refresh());
    }
   public function logout(Request $request){
        $user = Auth::user()->token();
        $user->revoke();
        return 'Successfully logged out'; 
}

}
