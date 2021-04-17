<?php

namespace App\Http\Controllers;

use App\Models\GuestRoomMessages;
use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Hash;

class GuestRoomMessagesController extends Controller
{
    private $url;
    public function __construct(UrlGenerator $url)
    {
        $this->url = $url;
    }

    public function create_private_room_message(Request $request){
        $this->validate($request, [
            'file' => 'max:10240',
        ]);
        $new = new GuestRoomMessages();
        $new->room_number = $request->room_number;
        $new->title = $request->title;
        if ($request->hasFile('room_file')){
            $filenameWithExt = $request->file('room_file')->getClientOriginalName();
            $filenameWithExt = trim($filenameWithExt);
            $fileName = pathinfo($filenameWithExt,PATHINFO_FILENAME);
            $extension = $request->file('room_file')->getClientOriginalExtension();
            if($extension==='php'){
                return "php file can't upload, try again after compression it";
            }
            $fileNameToStore = $fileName.'_'.time().'.'.$extension;
            $path = $request->file('room_file')->storeAs('public/private_room_files',$fileNameToStore);
            $new->file_name = $fileNameToStore;
        }
        if($request->password){
            $new->password =Hash::make($request->password);
        }
        $new->save();
//        event(new PrivateMessage($request->room_number));


        return redirect()->back()->withErrors($request->all());
    }
    public function ajax_private(Request $request){
        $data = GuestRoomMessages::find($request->id);
        if (password_verify($request->password, $data->password)) {
            return response()->json(['status'=>200,'download_link'=>$this->url->to('/storage/private_room_files/'.$data->file_name)]);
        }else{
            return response()->json(['status'=>400,'message'=>"password incorrect"]);
        }
    }
}
