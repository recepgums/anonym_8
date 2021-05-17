<?php

namespace App\Http\Controllers;

use App\Models\GlobalRoomMessages;
use App\Models\GuestRoomMessages;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
        $texts = GlobalRoomMessages::where('file_name', null)->select(['id', 'title', 'created_at'])->orderBy('created_at', 'desc')->get();
        $youtubes = GlobalRoomMessages::where('file_name', null)->where('title', 'LIKE', '%youtube%')->select(['id', 'title', 'created_at'])->orderBy('created_at', 'desc')->get();
        $files = GlobalRoomMessages::whereNotNull('file_name')->whereNotNull('password')->orderBy('created_at', 'desc')->select(['id', 'title', 'created_at'])->get();
        return response()->json(['youtubes' => $youtubes, 'texts' => $texts, 'files' => $files],200);
    }

    public function detail($id)
    {
        try {
            return GlobalRoomMessages::findOrFail($id)->toArray();
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Post not found'],404);
        }
    }

    public function delete(Request $request)
    {
        try {
            $file = GlobalRoomMessages::findOrFail($request->id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Post not found'],404);
        }
        if (password_verify($request->password, $file->password)) {
            $file->delete();
            $file_name = explode("/",$file->file_name);
            $file_name = end($file_name);
            Storage::disk('s3')->delete('public/global_files/'.$file_name);
            return response()->json(["message" => "Success"],200);
        } else {
            return response()->json(['message' => "File couldn't delete"],400);
        }
    }

    public function private_room_index($number)
    {
        $data = GuestRoomMessages::where('room_number', $number)->orderBy('created_at', 'desc')->get();
        return $data;
    }

    public function upload(Request $request)
    {
        $rules = array(
            'file' => 'max:3000000'
        );
        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['error' => $error->errors()->all()]);
        }
        $new = new GlobalRoomMessages();
        $new->title = $request->title;
        if ($request->hasFile('file')) {
            if(is_array($request->file )) {
                if (count($request->file) === 1) {
                    $uploadingS3 = $request->file('file')[0]->store('public/global_files', 's3');
                    Storage::disk('s3')->setVisibility($uploadingS3, 'public');
                    $new->file_name = Storage::disk('s3')->url($uploadingS3);
                }else{
                    die();
                }
            }else{
                $uploadingS3 = $request->file('file')->store('public/global_files', 's3');
                Storage::disk('s3')->setVisibility($uploadingS3, 'public');
                $new->file_name = Storage::disk('s3')->url($uploadingS3);
            }

        }

        if ($request->password) {
            $new->password = Hash::make($request->password);
        }
        $new->save();
        return response()->json(["data" => $new],200);
    }

    public function password(Request $request, $file_id)
    {
        $file = GlobalRoomMessages::findOrFail($file_id);
        if (password_verify($request->password, $file->password)) {
            return response()->json(['download_link' => $this->urlGenerator->to($file->file_name)],200);
        } else {
            return response()->json(['message' => "password is incorrect"],400);
        }
    }
}
