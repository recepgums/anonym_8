<?php

use App\Http\Controllers\GlobalController;
use App\Http\Controllers\GuestRoomMessagesController;
use App\Http\Controllers\RoomSettingsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [GlobalController::class,'home_page']);
//Route::post('/create_global',[GlobalController::class,'create_global'])->name('create_global');
//Route::post('/global_password_check/{message_id}',[GlobalController::class,'global_password_check'])->name('global_password_check');
Route::get('/private_room',[RoomSettingsController::class,'show_private_room'])->name('show_private_room');
Route::post('/private_room_create/{room_number}',[GuestRoomMessagesController::class,'create_private_room_message'])->name('create_private_room_message');

Route::post('ajaxdeneme',[GlobalController::class,'ajax_password'])->name('ajax_password');
Route::post('ajaxprivate',[GuestRoomMessagesController::class,'ajax_private'])->name('ajax_private');

Route::post('/get_link',[GlobalController::class,'get_link']);
Route::get('/l/{file_link}',[GlobalController::class,'get_file_with_link']);
