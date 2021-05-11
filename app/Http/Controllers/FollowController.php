<?php

namespace App\Http\Controllers;

use App\Models\GlobalRoomMessages;
use App\Models\GuestRoomMessages;
use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FollowController extends Controller
{
    /**
     * @var UrlGenerator
     */
    private $urlGenerator;

    public function __construct(UrlGenerator $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function index()
    {
        $texts = GlobalRoomMessages::where('file_name',null)->select(['id','title','created_at'])->orderBy('created_at','desc')->get();
        $youtubes = GlobalRoomMessages::where('file_name',null)->where('title','LIKE','%youtube%')->select(['id','title','created_at'])->orderBy('created_at','desc')->get();
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
    public function upload(Request $request)
    {
        $rules = array(
            'file'=>'max:3000000'
        );
        $error = Validator::make($request->all(),$rules);

        if ($error->fails()){
            return response()->json(['error'=>$error->errors()->all()]);
        }
        $new = new GlobalRoomMessages();
        $new->title = $request->title;
        if ($request->hasFile('file')){
            $uploadingS3 = $request->file('file')->store('public/global_files','s3');
            Storage::disk('s3')->setVisibility($uploadingS3,'public');
            $new->file_name = Storage::disk('s3')->url($uploadingS3);
        }

        if($request->password){
            $new->password =Hash::make($request->password);
        }
        $new->save();
        return response()->json(["status"=>200,"data"=>$new]);
    }

    public function password(Request $request,$file_id)
    {
        $file = GlobalRoomMessages::findOrFail($file_id);
        if (password_verify($request->password, $file->password)) {
            return response()->json(['status'=>200,'download_link'=>$this->urlGenerator->to($file->file_name)]);
        }else{
            return response()->json(['status'=>400,'message'=>"password is incorrect"]);
        }
    }
}
