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
}
