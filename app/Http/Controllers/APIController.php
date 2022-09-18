<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class APIController extends Controller
{
    // get all user
    public function getUsers($id = null){
       if(empty($id)){
        $user = User::get();
        return response() -> json([ "user" => $user], 200);
       }else {
        $user = User::find($id);
        return response() -> json([ "user" => $user], 200);
       }
    }


    // add user
    public function addUsers(Request $request){

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

        $msg = Validator::make($allData, $roles);

        if($msg -> fails()){
            return response() -> json([ $msg -> errors()], 422);
        }

        // data insert
        $uesr = new User();
        $uesr -> name       = $request -> name;
        $uesr -> email      = $request -> email;
        $uesr -> password   = bcrypt($request -> password);
        $uesr -> save();

        return response() -> json(["message" => "User Added" ], 201);

    }

    // add multiple user 
    public function addMultipleUser(Request $request){

        $allData = $request -> input();

        foreach ($allData['users'] as $key => $value) {
            $uesr = new User();
            $uesr -> name       = $value['name'];
            $uesr -> email      = $value['email'];
            $uesr -> password   = bcrypt($value['password']);
            $uesr -> save();
        }

        return response() -> json(["message" => "User Added" ], 201);
    }
}
