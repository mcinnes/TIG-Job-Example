<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transcription extends Model
{
    protected $table = 'transcriptions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'contact', 's3_id', 'text', 'voice',
    ];

    
}