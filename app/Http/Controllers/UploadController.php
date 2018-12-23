<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transcription;
use App\Jobs\TranscriptionJob;

class UploadController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }
    public function view(){
        return view('upload.index');
    }

    public function store(Request $request)
    {
        //Create our new transcription object
        $transcription = new Transcription();
        $transcription->contact = $request->contact;
        $transcription->text = $request->text;
        //Save transcription to database
        $transcription->save();

        //Create our new job to process the transcription
        $job = (new TranscriptionJob($transcription));
        //Add job into the queue
        $this->dispatch($job);

        //Return our view to the user
        return view('upload.success');

    }

}