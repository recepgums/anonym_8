<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\PassportAuthController;
use App\Models\GlobalRoomMessages;
use App\Models\GuestRoomMessages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('register', [PassportAuthController::class, 'register']);
Route::get('login', [PassportAuthController::class, 'login'])->name('login');

Route::middleware('auth:api')->group(function () {

});
Route::post('/removeFile',[FollowController::class,'delete']);
Route::get('/private_room/{number}',[FollowController::class,'private_room_index']);
Route::get('/', [FollowController::class,'index']);
Route::get('/get/{id}', [FollowController::class,'detail']);
Route::post('/{file_id}/password', [FollowController::class,'password']);
Route::post('/', [FollowController::class,'upload']);

//TODO get link
//TODO rooms
//TODO qr code
