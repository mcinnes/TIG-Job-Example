<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //

    }
    public function view(){
        return view('upload.index');
    }

    public function store(Request $request)
    {
        $storagePath = Storage::disk('s3')->put("pre-process", $request->photo, 'public');

        return view('upload.success', ['id' => $storagePath]);

    }


    //
}