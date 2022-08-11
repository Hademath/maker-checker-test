<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Admin;
use App\Models\Roles;



class AdminAuthController extends Controller
{
    //

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
        // return response()->json([ 'valid' => auth()->check() ]);
       
    }


    public function Register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:6',
            'email' => 'required|email|unique:admin',
            'phone' => 'required|string',
            'role' => 'required|string',
            'password'=> 'required|string|min:6',
        ]);

        if($validator->fails()){
            $this->result->status_code = 422;
            $this->result->message = $validator->errors()->get('*');
        }else{
            $admin = Admin::create([
                'name' => $request->name,
                'email'=>$request->email,
                'phone' => $request->phone,
                'role' => $request->role,
                'password' => bcrypt($request->password),
                ]
            );
           
            if($admin){
                $this->result->status = true;
                $this->result->message = "Successful";
            }else{
                $this->result->status = false;
                $this->result->message = "Not Successfull";
            }
           
        }
        return response()->json($this->result);
    }


    public function login(Request $request){

        //valid credential
       $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);
        
         if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        if(!$token = Auth::guard('api')->attempt(['email' => $request->email, 'password' => $request->password])) {
            $this->result->status_code = 401;
            $this->result->message = "Invalid login Details Provided";
            return response()->json($this->result);
        }

        $active_staff = Admin::query()->where('email', $request->email)->get()->first();
        if($active_staff['status'] == 0){
            $this->result->status_code = 401;
            $this->result->message = "No Account Found";
            return response()->json($this->result);
        }

        $admin = Admin::where('email', $request->email)->first();
        $role = Roles::where('name', $admin->role)->first();
        $admin->role = $role->name;       
       
        $this->result->token = $this->respondWithToken($token);
        $this->result->status = true;
        $this->result->data->admin = $admin;
        $this->result->data->role = $role;
        return $this->result;
        return response()->json($this->result);
        
    }


    protected function respondWithToken($token)
    {
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }


    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }
 



public function logout(Request $request){
        $user = Auth::logout();
        // $user->revoke();
        return 'Successfully logged out'; // modify as per your need
}


}
