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
Route::get('user-list/', [APIController::class, 'getUserList']);

Route::post('register-user/', [APIController::class, 'RegisterUser']);
Route::get('login-user/', [APIController::class, 'LoginUser']);
Route::get('logout-user/', [APIController::class, 'LogoutUser']);
Route::post('add-multiple-user/', [APIController::class, 'addMultipleUser']);

Route::put('update-user/{id}', [APIController::class, 'UpdateUser']);
Route::patch('update-user-name/{id}', [APIController::class, 'UpdateUserName']);

Route::delete('delete-user/{id}', [APIController::class, 'DeleteUser']);
Route::delete('delete-multiple-user/{ids}', [APIController::class, 'DeleteMultipleUser']);

// passport package
Route::post('register-user-passport/',
 [APIController::class, 'RegisterUserWithPassport']);
