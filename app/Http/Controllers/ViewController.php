<?php

namespace App\Http\Controllers;

use App\Transcription;

class ViewController extends Controller
{
    protected $transcription;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    public function view($id)
    {
        $this->transcription = Transcription::find($id);
        return view('view.index', ['text' => $this->transcription->text, 'updated_at' => $this->transcription->updated_at, 's3_id' => $this->transcription->s3_id]);
    }

    //
}