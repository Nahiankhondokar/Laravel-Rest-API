<?php

use App\Http\Controllers\APIController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// user API 
Route::get('users/{id?}', [APIController::class, 'getUsers']);
Route::post('add-users/', [APIController::class, 'addUsers']);
Route::post('add-multiple-user/', [APIController::class, 'addMultipleUser']);
Route::put('update-user/{id}', [APIController::class, 'UpdateUser']);
Route::patch('update-user-name/{id}', [APIController::class, 'UpdateUserName']);
