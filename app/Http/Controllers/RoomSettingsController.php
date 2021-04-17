<?php

namespace App\Http\Controllers;


use App\Models\GuestRoomMessages;
use App\Models\RoomSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class RoomSettingsController extends Controller
{
    public function show_private_room(Request $request){
        $this->validate($request, [
            'room_number' => 'required|between:0,9999999999|integer',
        ]);
        $number=$request->room_number;
        $all = GuestRoomMessages::where('room_number',$number)->orderBy('created_at','desc')->get();
        if(!RoomSetting::where('room_number',$number)->first()){
            $new = new RoomSetting();
            $new->room_number = $number;
            $new->save();
        }
        View::share('all',$all);
        View::share('number',$number);
//        event(new PrivateMessage($request->room_number));
        return view('private_room')->withErrors($request->all());
    }
}
