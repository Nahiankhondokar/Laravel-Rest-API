<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class APIController extends Controller
{
    // get all user
    public function getUsers($id = null){
       if(empty($id)){
        $user = User::get();
        return response() -> json([ "user" => $user]);
       }else {
        $user = User::find($id);
        return response() -> json([ "user" => $user]);
       }
    }


    // add user
    public function addUsers(Request $request){

        $uesr = new User();
        $uesr -> name       = $request -> name;
        $uesr -> email      = $request -> email;
        $uesr -> password   = bcrypt($request -> password);
        $uesr -> save();

        return response() -> json(["message" => "User Added" ]);

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

        return response() -> json(["message" => "User Added" ]);
    }
}
