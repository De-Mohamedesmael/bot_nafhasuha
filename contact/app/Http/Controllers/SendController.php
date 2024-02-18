<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twilio\Rest\Client;

class SendController extends Controller
{
    public function  downloadCertificate(Request $request){
        $html_content= view('certificate')->render();
        $html_content=str_replace('[name]' ,$request->name,$html_content);
        $html_content=str_replace('[project_name]' ,$request->project_name,$html_content);

        $image_name = time().'.png';
        $snappy = new \Knp\Snappy\Image(public_path('assets/images/new/'.$image_name));
        $image = $snappy->getOutput($html_content);
        file_put_contents('assets/images/new/'.$image_name, $image);

        return $image;


    }


    public function sendMessage(Request $request)
    {
        $twilio = new Client(config('services.twilio.account_sid'), config('services.twilio.auth_token'));
        $to_number="+201146469865";
        $message_text="";
        $media_url=asset('assets/images/182064903.png');
        $message = $twilio->messages->create("whatsapp:{$to_number}", array(
            'from' => 'whatsapp:' . config('services.twilio.whatsapp_from'),
            'body' => $message_text,
            'mediaUrl' => $media_url,
        ));
    }
}
