<?php

namespace App\Jobs;

use App\Transcription;
use Aws\Polly\PollyClient;
use Illuminate\Support\Facades\Storage;
use Aws\Sns\SnsClient;

class TranscriptionJob extends Job
{
    protected $transcription;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Transcription $transcription)
    {
        $this->transcription = $transcription;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //AWS Polly Configuration
        $config = [
            'version' => 'latest',
            'region' => 'us-east-1'
        ];

        //Create the Polly Client to process text to speech
        try {
            $client = new PollyClient($config);
        }
        catch(Exception $e) {
            Log::error($e); 
            exit;
        }

        //Create the SSML and options to convert to speech
        $speech = [
            'Text' => '<speak>'.$this->transcription->text.'</speak>',
            'OutputFormat' => 'mp3',
            'TextType' => 'ssml',
            'VoiceId' => 'Joanna'
        ];

        //Post speech data to AWS
        $response = $client->synthesizeSpeech($speech);

        $storagePathPrefix  = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();

        //Save data to local file from \Response object, could possibly be skipped and saved directly to S3
        file_put_contents($storagePathPrefix.'temp.mp3', $response['AudioStream']);

        //Generate new random file name and append file format
        $filename = uniqid().".mp3";

        //Move data from our local storage to S3
        $savedFile = Storage::disk('s3')->put("post-process/".$filename, Storage::disk('local')->get('temp.mp3'), 'public');

        //Check if file exists on the S3 server before deleting the temporary local copy
        if (Storage::disk('s3')->exists("post-process/".$filename)) {
            Storage::disk('local')->delete("temp.mp3");
        } else {
            Log::warning("temp.mp3 was not found on the S3 server, file not deleted");
        }

        //Load the transcription into an object we can modify and save the S3 file path
        $updateObject = Transcription::find($this->transcription->id);
        $updateObject->s3_id = "post-process/".$filename;
        $updateObject->save();

        //Send a notification to the user on completion
        $this->sendNotification();
    }

    protected function sendNotification()
    {
        //AWS SNS Configuration
        $config = [
            'version' => 'latest',
            'region' => 'us-east-1'
        ];

        //Create the SNS Client to send the text message
        try {
            $snsClient = new SnsClient($config);
        }
        catch(Exception $e) {
            Log::error($e); 
            exit;
        }

        //Format our message for the user
        $messageString = "Your transcription is ready at http://localhost/public/view/".$this->transcription->id;

        //Data array for the message
        $message = array(
            "MessageAttributes" => [
                        'AWS.SNS.SMS.SenderID' => [
                            'DataType' => 'String',
                            'StringValue' => 'TIGTEST'
                        ],
                        'AWS.SNS.SMS.SMSType' => [
                            'DataType' => 'String',
                            'StringValue' => 'Transactional'
                        ]
                    ],
            "Message" => $messageString,
            "PhoneNumber" => $this->transcription->contact
        );

        //Publish the message to the user
        $result = $snsClient->publish($message);
    }

    // /**
    //  * Create a new 10 character random string.
    //  *
    //  * @return string
    //  */
    // protected function randomString($length = 10)
    // {
    //     $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    //     $charactersLength = strlen($characters);
    //     $randomString = '';
    //     for ($i = 0; $i < $length; $i++) {
    //         $randomString .= $characters[rand(0, $charactersLength - 1)];
    //     }
    //     return $randomString;
    // }

}
