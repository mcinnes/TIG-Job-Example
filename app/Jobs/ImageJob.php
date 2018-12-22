<?php

namespace App\Jobs;

use App\Image;

class ImageJob extends Job
{
    protected $image;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Image $image)
    {
        $this->image = $image;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //Do something with the image?
        error_log($this->image);

    }
}
