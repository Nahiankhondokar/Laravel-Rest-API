<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class APIController extends Controller
{
    // get single user
    public function UserRegister($id = null){
       if(empty($id)){
        $user = User::get();
        return response() -> json([ "user" => $user], 200);
       }else {
        $user = User::find($id);
        return response() -> json([ "user" => $user], 200);
       }
    }

    // get all user
    public function getUserList(Request $request){

        // return $request -> header('Authorization');

        $token = "Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c";
        $header = $request -> header('Authorization');
        if(!empty( $header)){
            if($header == $token){
                $user = User::all();
                return response() -> json([ "user" => $user], 200);
            }else {
                return response() -> json([ "message" => "Invalid token"], 422);
            }
        }else {
            return response() -> json([ "message" => "Your are not authoraized"], 422);
        }

        

    }



    // add user
    public function RegisterUser(Request $request){

        // get all data
        $allData = $request -> input();


        // smple validation
        
        // if(empty($allData['name']) || empty($allData['email']) || empty($allData['password']) ){
        //     $err_msg  = 'All Fields are required';
        // }

        // if(!filter_var($allData['email'], FILTER_VALIDATE_EMAIL)){
        //     $err_msg  = 'invalid email';
        // }

        // $user_count = User::where('email', $allData['email']) -> count();
        // if($user_count > 0){
        //     $err_msg  = 'email already exists'; 
        // }

        // // validation msg
        // if(!empty($err_msg) && isset($err_msg)){
        //     return response() -> json(["message" => $err_msg, "status" => false], 422);
        // }


        // advance validation 
        $roles = [
            'name'      => 'required',
            'email'     => 'required|email|unique:users',
            'password'  => 'required'
        ];
        $customMsg = [
            'name.required'     => "name is required",
            'email.required'     => "email is required",
            'email.email'     => "valid email is required",
            'email.unique'     => "email is exists",
            'password.required'     => "password is required"
        ];

        $msg = Validator::make($allData, $roles, $customMsg);

        if($msg -> fails()){
            return response() -> json([ $msg -> errors()], 422);
        }

        // token generate
        $token = Str::random(60);

        // data insert
        $uesr = new User();
        $uesr -> name       = $request -> name;
        $uesr -> email      = $request -> email;
        $uesr -> password   = bcrypt($request -> password);
        $uesr -> api_token  = $token;
        $uesr -> save();

        return response() -> json(["message" => "User Registered", "token" => $token ], 201);

    }

    // login user 
    public function LoginUser(Request $request){

        // get all data
        $allData = $request -> input();

        // advance validation 
        $roles = [
            'email'     => 'required|email|exists:users',
            'password'  => 'required'
        ];
        $customMsg = [
            'email.required'        => "email is required",
            'email.email'           => "valid email is required",
            'email.unique'          => "email is exists",
            'password.required'     => "password is required"
        ];

        $msg = Validator::make($allData, $roles, $customMsg);

        if($msg -> fails()){
            return response() -> json([ $msg -> errors()], 422);
        }

        // user login
        $user = User::where('email', $allData['email']) -> first();
        // $pass_verify = Hash::check($allData['password'], $user -> password);
        $pass_check = \password_verify($allData['password'], $user -> password);

        if($pass_check){
            $new_token = Str::random(60);
            User::where('email', $allData['email']) -> update(['api_token'    => $new_token]);

            return response() -> json([ 
                "user" => $user,
                "message" => "user loggedin",
                "token"     => $new_token
            ], 200);

        }else {
            return response() -> json([ "message" => "password incorrect" ], 422);
        }


    }

    // user logout
    public function LogoutUser(Request $request){

        $token = $request -> header('Authorization');
        if(empty($token)){

            return response() -> json([ "message" => "token is missing" ], 422);

        }else {
            $api_token = str_replace('Bearer ', '', $token);
            $api_count = User::where('api_token', $api_token) -> count();

            if($api_count > 0){
                User::where('api_token', $api_token) -> update(['api_token' => NULL]);
                return response() -> json([ "message" => "User logged out" ], 200);
            }
        }

    }

    // add multiple user 
    public function addMultipleUser(Request $request){

        $allData = $request -> input();

         // multiple data advance validation 
         $roles = [
            'users.*.name'      => 'required',
            'users.*.email'     => 'required|email|unique:users',
            'users.*.password'  => 'required'
        ];
        $customMsg = [
            'users.*.name.required'     => "name is required",
            'users.*.email.required'     => "email is required",
            'users.*.email.email'     => "valid email is required",
            'users.*.email.unique'     => "email is exists",
            'users.*.password.required'     => "password is required"
        ];

        $msg = Validator::make($allData, $roles, $customMsg);

        if($msg -> fails()){
            return response() -> json([ $msg -> errors()], 422);
        }

        // data insert
        foreach ($allData['users'] as $key => $value) {
            $uesr = new User();
            $uesr -> name       = $value['name'];
            $uesr -> email      = $value['email'];
            $uesr -> password   = bcrypt($value['password']);
            $uesr -> save();
        }

        return response() -> json(["message" => "User Added" ], 201);
    }


    // update user
    public function UpdateUser($id, Request $request){

        // get all data
        $allData = $request -> input();
        
        // advance validation 
        $roles = [
            'name'      => 'required',
            'email'     => 'required|email|unique:users',
            'password'  => 'required'
        ];
        $customMsg = [
            'name.required'     => "name is required",
            'email.required'     => "email is required",
            'email.email'     => "valid email is required",
            'email.unique'     => "email is exists",
            'password.required'     => "password is required"
        ];

        $msg = Validator::make($allData, $roles, $customMsg);

        if($msg -> fails()){
            return response() -> json([ $msg -> errors()], 422);
        }

        // data update
        $update = User::find($id);
        $update -> name = $request['name'];
        $update -> email = $request['email'];
        $update -> password = bcrypt($request['password']);
        $update -> update();

        return response() -> json(["message" => "User Updated"], 202);
    }

    /**
     *  update user name by patch mathod
     *  when we need to update only one field this time we can use PATCH method
     */
    public function UpdateUserName(Request $request, $id){

        // get all data
        $allData = $request -> input();
        
        // advance validation 
        $roles = [
            'name'      => 'required',
            'email'     => 'required|email|unique:users',
            'password'  => 'required'
        ];
        $customMsg = [
            'name.required'     => "name is required",
            'email.required'     => "email is required",
            'email.email'     => "valid email is required",
            'email.unique'     => "email is exists",
            'password.required'     => "password is required"
        ];

        $msg = Validator::make($allData, $roles, $customMsg);

        if($msg -> fails()){
            return response() -> json([ $msg -> errors()], 422);
        }

        // data update
        $update = User::find($id);
        $update -> name = $request['name'];
        $update -> update();

        return response() -> json(["message" => "User Name Updated"], 202);
    }


    // delete user 
    public function DeleteUser($id){
        $delete = User::find($id);
        $delete -> delete();

        return response() -> json(["message" => "User Deleted"], 202);
    }

    // delete multiple user
    public function DeleteMultipleUser($ids){

        $multi_id = explode(',', $ids);
        User::whereIn('id', $multi_id) -> delete();

        return response() -> json(["message" => "Multi User Deleted"], 202);
    }



}
