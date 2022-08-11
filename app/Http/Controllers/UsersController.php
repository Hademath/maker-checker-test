<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Admin;
use Mail;
use App\Mail\SendMail;
use HasApiTokens;

class UsersController extends Controller
{
       public function __construct() {
        $this->middleware('jwt');
        $this->result = ( object ) array(
            'status' => false,
            'status_code' => 200,
            'message' => null,
            'data'=> ( object ) array(),
            'token' => null,
            'debug' => null
        );

    }


 public function get_all_users(){
        $users = User::paginate(15);
        if($users){
            $this->result->status = true;
            $this->result->message = "Successful";
            $this->result->data = $users;
        }else{
            $this->result->status = false;
            $this->result->message = "Not Successfull";
        }
        return response()->json($this->result);

    }

 public function view_all_pending_request(){
         $users = User::query()->where('status', '=', '1')->get();
         if(!$users){
            $this->result->status= false;
            $this->result->message ="No Pending  Request At the moment";
             return response()->json($this->result);
         }
        if($users){
            $this->result->status = true;
            $this->result->message = "Successful";
            $this->result->data = $users;
        }
        return response()->json($this->result);

    }

 public function get_user_by_id($id){
        $user = User::find($id);
        if (!$user) {
            $this->result->status_code = 404;
            $this->result->message = 'User not found';
            return response()->json($this->result);
        }
        if($user){
            $this->result->status = true;
            $this->result->message = "Successful";
            $this->result->data = $user;
        }
        return response()->json($this->result);
    }

//Get the login amdin details
public static function get_admin_info($officer)
    {
        $staff = Admin::where('id', $officer)->get()->first();
        return $staff;
    }

//Super Admin can edit the credentials of users that has been approved by the HR and send email back to the HR for the update  
 public function update_user(Request $request, $role){
        // dd($request);
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            $this->result->status_code = 422;
            $this->result->message = $validator->errors();
            return response()->json($this->result);
        }
        $id = $request->id;
        $firstname = $request->firstname;
        $lastname = $request->lastname;
        $email = $request->email;

        if (!User::find($id)) {
            $this->result->status_code = 404;
            $this->result->message = 'The user with the given ID does not exist';
            return response()->json($this->result);
        }
        $approved_user = User::where('id', $id)->where('status', '2')->first();
         if (!$approved_user) {
            $this->result->status_code = 404;
            $this->result->message = 'This user Credential is yet to be approved for editing';
            return response()->json($this->result);
        }

        $admin_mail = Admin::select('email')->where('role', 'customer officer')->get()->toArray();
        $super_admin_role=Auth::check() && Auth::user()->role == "super admin";
        if(!$super_admin_role){
            $this->result->status_code = 404;
            $this->result->message = 'You are not authorised to Edit User\'s Profile';
            return response()->json($this->result);
        }
        if ($super_admin_role && $approved_user) {
            $user_update = User::where('id', $id)->update(['firstname' => $firstname,'lastname' => $lastname, 'email' => $email]);
    
     //THIS  SEND EMAIL TO  CUSTOMER OFFICERS ABOUT THE UPDATE MADE TO PARTICULAR USER,
    //   $mailData = [
    //         'title' => 'Mail from Maker Checker',
    //         'body' => 'The user details has been updated successfully, kindly notify the user about the update'
    //     ];
         
    //     Mail::to($admin_mail )->send(new SendMail($mailData));
           
     //   // dd("Email is sent successfully.");

        if ($user_update) 
            $this->result->status = true;
            $this->result->message = 'User Updated successfully ';
        } else {
            $this->result->message = 'Something went wrong, try again';
        }
        return response()->json($this->result);
    }

//Only customer officer can approve pending Users for Updating by super Admin
public function approve_user($id)
    {
        $user = User::find($id);
        if (!$user) {
            $this->result->status_code = 404;
            $this->result->message = 'User not found';
            return response()->json($this->result);
        }
      $admin_role=Auth::check() && Auth::user()->role == "customer officer";
        if(!$admin_role){
            $this->result->status_code = 404;
            $this->result->message = 'You are not authorised to approve User';
            return response()->json($this->result);
        }
    $user_already_approved = User::where('id', $id)->where('status', '2')->get()->first();
        if($user_already_approved){
            $this->result->status_code = 404;
            $this->result->message = 'This user has been approved';
            return response()->json($this->result);
        }
        $user_approved = User::where('id', $id)->where('status', '1')->update(['status' => '2']);
        if ($user_approved) {
            $this->result->status = true;
            $this->result->message = 'User activated successfully';
        } else {
            $this->result->message = 'Something went wrong, try again';
        }

        return response()->json($this->result);
    }

    
 public function decline_user($id)
    {
        $user = User::find($id);
        if (!$user) {
            $this->result->status_code = 404;
            $this->result->message = 'User not found';
            return response()->json($this->result);
        }
   $admin_role=Auth::check() && Auth::user()->role == "customer officer";
        if(!$admin_role){
            $this->result->status_code = 404;
            $this->result->message = 'You are not authorised to Decline User';
            return response()->json($this->result);
        }
    $user_already_declined = User::where('id', $id)->where('status', '0')->get()->first();
        if($user_already_declined){
            $this->result->status_code = 404;
            $this->result->message = 'This user has been declined';
            return response()->json($this->result);
        }
        $user_decline = User::where('id', $id)->where('status', '1')->update(['status' => '0']);
        if ($user_decline) {
            $this->result->status = true;
            $this->result->message = 'User Declined successfully';
        } else {
            $this->result->message = 'Something went wrong, try again';
        }

        return response()->json($this->result);
    }


}
