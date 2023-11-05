<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotificationResource;
use App\Models\AdsImage;
use App\Models\FcmToken;
use App\Models\FcmTokenProvider;
use App\Models\Order;
use App\Models\UserRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Notification as ModelNotification;
use App\Models\UserNotification;
use App\Models\NotificationNote;
use App\Models\NotificationTranslation;
use App\Models\NotificationNoteTranslation;

class ApiController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function files($files, $position, $id)
    {
        foreach ($files as $file) {
            $type = explode('/', $file->getClientMimeType())[0];
            if ($type == 'image' || $type == 'video') {
                AdsImage::create([
                    'ads_id' => $id,
                    'file' => $file,
                    'position' => $position,
                    'type' => $type == 'image' ? 'img' : 'video',
                ]);
            }
        }
    }

    public function pushNotof($type,$type_item,$user_id,$step=null){
        // type=> ,'FriendRequest'
        // type_item
        $image=null;
        $type_number=3;
        $not=[];
        switch ($type){
            case 'Order':
                $code=$type_item->transaction?->invoice_no;
                $type_id=$type_item->id;
                $type_number=1;
                if($step == 1){
                    $not['ar']['title']=__('notifications.order_step1.title',[],'ar');
                    $not['en']['title']=__('notifications.order_step1.title',[],'en');
                    $not['ar']['body']=__('notifications.order_step1.body',['code'=>$code],'ar');
                    $not['en']['body']=__('notifications.order_step1.body',['code'=>$code],'en');
                    $notarray=[
                        'type_model'=>'Provider',
                        'type_id'=>$type_id,
                        'type'=>$type_number,
                        'image'=>null
                    ];
                    $notarray['ar']['title']=__('notifications.order_step1_provider.title',[],'ar');
                    $notarray['en']['title']=__('notifications.order_step1_provider.title',[],'en');
                    $notarray['ar']['body']=__('notifications.order_step1_provider.body',['code'=>$code],'ar');
                    $notarray['en']['body']=__('notifications.order_step1_provider.body',['code'=>$code],'en');
                   $user_req= UserRequest::where('order_service_id',$type_id)->first();
                   if($user_req){
                       $ids=json_decode($user_req->providers_id, true);
                       $this->pushNotofarray($notarray,$ids);
                   }

                }elseif($step == 2){
                    $not['ar']['title']=__('notifications.order_step2.title',[],'ar');
                    $not['en']['title']=__('notifications.order_step2.title',[],'en');
                    $not['ar']['body']=__('notifications.order_step2.body',['code'=>$code],'ar');
                    $not['en']['body']=__('notifications.order_step2.body',['code'=>$code],'en');
                }else{
                    $not['ar']['title']=__('notifications.order_step3.title',[],'ar');
                    $not['en']['title']=__('notifications.order_step3.title',[],'en');
                    $not['ar']['body']=__('notifications.order_step3.body',['code'=>$code],'ar');
                    $not['en']['body']=__('notifications.order_step3.body',['code'=>$code],'en');
                }

                break;


            default:

        }
        $not['type_model']='User';
        $not['type_id']=$type_id;
        $not['type']=$type_number;
        $not['image']=$image;


        $Notification = ModelNotification::create($not);
        if(! is_array($user_id)){
            UserNotification::create([
            'user_id'=> $user_id,
            'notification_id'=>$Notification->id,
            ]);
            $FcmToken=FcmToken::where('user_id',$user_id)->pluck('token');
            $this->sendNotification($Notification,$FcmToken);
        }else{
            $Notification->users()->sync($user_id);
            $FcmToken=FcmToken::wherein('user_id',$user_id)->pluck('token');
            $this->sendNotification($Notification,$FcmToken);
        }


        return true;


    }


    public function pushNotofarray($notarray,$ids){

        $not_p=ModelNotification::create($notarray);
        $not_p->providers()->sync($ids);
        $FcmToken=FcmTokenProvider::wherein('provider_id',$ids)->pluck('token');

       $this->sendNotification($not_p,$FcmToken);
       return true;

    }



    public function sendNotification($Notif,$FcmToken)
    {
        $url = env('FIREBASE_URL');
        $serverKey = env('FIREBASE_SERVER_KEY');
        $Notification= new NotificationResource($Notif);
        $notification = [ "title" => $Notification->title, "body" => $Notification->body, "badge" => 1];
        $data = [
            "registration_ids" => $FcmToken,
            "notification" => $notification,
            "data" => $Notification
        ];


        $encodedData = json_encode($data);

        $headers = [
            'Authorization:key=' . $serverKey,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        // Close connection
        curl_close($ch);
        // FCM response
    }


    public function sendNotificationNat($title,$body,$type,$type_id,$FcmToken)
    {
        $url = env('FIREBASE_URL');
        $serverKey = env('FIREBASE_SERVER_KEY');
        $Notification=  [
            'id'=>1,
            'title'=>$title,
            'body'=>$body,
            'type'=>$type,
            'type_id'=>$type_id,
            'image'=> null ,
            'is_show'=>0
            ];
        $notification = [ "title" => $title, "body" => $body, "badge" => 1];
        $data = [
            "registration_ids" => $FcmToken,
            "notification" => $notification,
            "data" => $Notification
        ];

        $encodedData = json_encode($data);

        $headers = [
            'Authorization:key=' . $serverKey,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        // Close connection
        curl_close($ch);
        // FCM response
    }
}
