<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Image;
use App\Jobs\ImageJob;

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
        //Save the image to S3 using built in storage functionality
        $storagePath = Storage::disk('s3')->put("pre-process", $request->photo, 'public');

        //Create our new image
        $image = new Image();
        $image->contact = $request->contact;
        $image->s3_id = $storagePath;
        $image->save();

        error_log("saved");
        //ImageJob::dispatch($image);
        //Queue::push(new ImageJob($image));
        $job = (new ImageJob($image))->delay(2);

        $this->dispatch($job);

        //dispatch(new ImageJob($image));

        error_log("dispatched");
        return view('upload.success', ['id' => $storagePath]);

    }


    //
}