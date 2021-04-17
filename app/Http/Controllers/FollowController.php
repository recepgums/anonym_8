<?php

namespace App\Http\Controllers;

use App\Models\GlobalRoomMessages;
use App\Models\GuestRoomMessages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FollowController extends Controller
{
    public function index()
    {
        $texts = GlobalRoomMessages::where('file_name',null)->select(['id','title','created_at'])->orderBy('created_at','desc')->get();
        $files = GlobalRoomMessages::whereNotNull('file_name')->whereNotNull('password')->orderBy('created_at','desc')->select(['id','title','created_at'])->get();
        return response()->json(['status'=>200,'texts'=>$texts,'files'=>$files]);
    }

    public function detail($id)
    {
        return GlobalRoomMessages::findOrFail($id);
    }
    public function delete(Request $request)
    {
        $data = GlobalRoomMessages::find($request->id);
        $data->delete();
        Storage::disk('s3')->delete('public/global_files/fry0mkpnMFfOR2CXzRh7s3FEF06Kj2qtv9IdAz1D.pdf');
        return response()->json(['status' => 200, "message" => "Success"]);
    }

    public function private_room_index($number)
    {
        $data = GuestRoomMessages::where('room_number', $number)->orderBy('created_at', 'desc')->get();
        return $data;
    }
}
