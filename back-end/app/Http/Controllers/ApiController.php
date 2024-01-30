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
        $type_id=$type_item->id;
        $order_step=null;
        $type_number=3;
        $not=[];
        switch ($type){
            case 'Order':
                $code=$type_item->transaction?->invoice_no;
                $type_number=1;
                if ($step == 10){
                    $order_step = 'New';
                    $not['ar']['title'] = __('notifications.order_step10.title', [], 'ar');
                    $not['en']['title'] = __('notifications.order_step10.title', [], 'en');
                    $not['ar']['body'] = __('notifications.order_step10.body', ['code' => $code], 'ar');
                    $not['en']['body'] = __('notifications.order_step10.body', ['code' => $code], 'en');
                    $notarray = [
                        'type' => $type_number,
                        'order_step' => $order_step,
                        'image' => null
                    ];
                    //'New','Price','Accept','Complete'
                    $type_model = 'Provider';
                    $notarray['ar']['title'] = __('notifications.order_step1_provider.title', [], 'ar');
                    $notarray['en']['title'] = __('notifications.order_step1_provider.title', [], 'en');
                    $notarray['ar']['body'] = __('notifications.order_step1_provider.body', ['code' => $code], 'ar');
                    $notarray['en']['body'] = __('notifications.order_step1_provider.body', ['code' => $code], 'en');
                    $user_req = UserRequest::where('order_service_id', $type_id)->first();
                    if ($user_req) {
                        $ids = json_decode($user_req->providers_id, true);
                        $this->pushNotofarray($notarray, $ids, $type_id, $type_model);
                    }
                }elseif($type_item ->type =="Maintenance"){
                    if ($step == 1) {
                        $order_step = 'New';
                        $not['ar']['title'] = __('notifications.order_step1.title', [], 'ar');
                        $not['en']['title'] = __('notifications.order_step1.title', [], 'en');
                        $not['ar']['body'] = __('notifications.order_step1.body', ['code' => $code], 'ar');
                        $not['en']['body'] = __('notifications.order_step1.body', ['code' => $code], 'en');
                        $notarray = [
                            'type' => $type_number,
                            'order_step' => $order_step,
                            'image' => null
                        ];
                        //'New','Price','Accept','Complete'
                        $type_model = 'Provider';
                        $notarray['ar']['title'] = __('notifications.order_step1_provider.title', [], 'ar');
                        $notarray['en']['title'] = __('notifications.order_step1_provider.title', [], 'en');
                        $notarray['ar']['body'] = __('notifications.order_step1_provider.body', ['code' => $code], 'ar');
                        $notarray['en']['body'] = __('notifications.order_step1_provider.body', ['code' => $code], 'en');
                        $user_req = UserRequest::where('order_service_id', $type_id)->first();
                        if ($user_req) {
                            $ids = json_decode($user_req->providers_id, true);
                            $this->pushNotofarray($notarray, $ids, $type_id, $type_model);
                        }

                    }elseif ($step == 2) {

                        $order_step = 'Accept';
                        $not['ar']['title'] = __('notifications.order_step_transporter0.title', [], 'ar');
                        $not['en']['title'] = __('notifications.order_step_transporter0.title', [], 'en');
                        $not['ar']['body'] = __('notifications.order_step_transporter0.body', ['code' => $code], 'ar');
                        $not['en']['body'] = __('notifications.order_step_transporter0.body', ['code' => $code], 'en');
                    } else {
                        $order_step = 'Complete';
                        $not['ar']['title'] = __('notifications.order_step_transporter1-2.title', [], 'ar');
                        $not['en']['title'] = __('notifications.order_step_transporter1-2.title', [], 'en');
                        $not['ar']['body'] = __('notifications.order_step_transporter1-2.body', ['code' => $code], 'ar');
                        $not['en']['body'] = __('notifications.order_step_transporter1-2.body', ['code' => $code], 'en');

                    }
                }
                elseif ($type_item ->type =="TransportVehicle" && $type_item ->parent_id != null){
                    $type_id=$type_item ->parent_id;

                    $parent=$type_item->parent;
                    $code=$parent?->transaction?->invoice_no;
                    $code_=$type_item->transaction?->invoice_no;

                    if ($step == 1) {

                        if($parent -> status == "completed"){
                            $order_step = 'Complete';
                            $not['ar']['title'] = __('notifications.order_step_transporter1-2.title', [], 'ar');
                            $not['en']['title'] = __('notifications.order_step_transporter1-2.title', [], 'en');
                            $not['ar']['body'] = __('notifications.order_step_transporter1-2.body', ['code' => $code], 'ar');
                            $not['en']['body'] = __('notifications.order_step_transporter1-2.body', ['code' => $code], 'en');


                        }
                        elseif($parent -> status == "canceled"){
                            $order_step = 'canceled';
                            $not['ar']['title'] = __('notifications.order_step_transporter_canceled.title', [], 'ar');
                            $not['en']['title'] = __('notifications.order_step_transporter_canceled.title', [], 'en');
                            $not['ar']['body'] = __('notifications.order_step_transporter_canceled.body', ['code' => $code], 'ar');
                            $not['en']['body'] = __('notifications.order_step_transporter_canceled.body', ['code' => $code], 'en');


                        }
                        else{
                            $order_step = 'Accept';
                            $not['ar']['title'] = __('notifications.order_step_transporter0.title', [], 'ar');
                            $not['en']['title'] = __('notifications.order_step_transporter0.title', [], 'en');
                            $not['ar']['body'] = __('notifications.order_step_transporter0.body', ['code' => $code], 'ar');
                            $not['en']['body'] = __('notifications.order_step_transporter0.body', ['code' => $code], 'en');

                        }
                        $notarray = [
                            'type' => $type_number,
                            'order_step' => 'New',
                            'image' => null
                        ];

                        //'New','Price','Accept','Complete'
                        $type_model = 'Provider';
                        $notarray['ar']['title'] = __('notifications.order_step1_provider.title', [], 'ar');
                        $notarray['en']['title'] = __('notifications.order_step1_provider.title', [], 'en');
                        $notarray['ar']['body'] = __('notifications.order_step1_provider.body', ['code' => $code_], 'ar');
                        $notarray['en']['body'] = __('notifications.order_step1_provider.body', ['code' => $code_], 'en');
                        $user_req = UserRequest::where('order_service_id', $type_item ->id)->first();
                        if ($user_req) {
                            $ids = json_decode($user_req->providers_id, true);
                            $this->pushNotofarray($notarray, $ids, $type_item ->id , $type_model);
                        }





                    }
                    elseif ($step == 2) {
                        if(in_array($parent -> status ,[ "canceled","completed"])){
                            $order_step = 'Accept';
                            $not['ar']['title'] = __('notifications.order_step_transporter1-3.title', [], 'ar');
                            $not['en']['title'] = __('notifications.order_step_transporter1-3.title', [], 'en');
                            $not['ar']['body'] = __('notifications.order_step_transporter1-3.body', ['code' => $code], 'ar');
                            $not['en']['body'] = __('notifications.order_step_transporter1-3.body', ['code' => $code], 'en');

                        }else{
                            $order_step = 'Accept';
                            $not['ar']['title'] = __('notifications.order_step_transporter1.title', [], 'ar');
                            $not['en']['title'] = __('notifications.order_step_transporter1.title', [], 'en');
                            $not['ar']['body'] = __('notifications.order_step_transporter1.body', ['code' => $code], 'ar');
                            $not['en']['body'] = __('notifications.order_step_transporter1.body', ['code' => $code], 'en');

                        }

                    }elseif ($step == 5) {
                        $order_step = 'Accept';
                        $not['ar']['title'] = __('notifications.order_step_transporter2.title', [], 'ar');
                        $not['en']['title'] = __('notifications.order_step_transporter2.title', [], 'en');
                        $not['ar']['body'] = __('notifications.order_step_transporter2.body', ['code' => $code], 'ar');
                        $not['en']['body'] = __('notifications.order_step_transporter2.body', ['code' => $code], 'en');

                    } else{
                        $order_step = $parent->status;
                        if($order_step == "completed"){

                            $not['ar']['title'] = __('notifications.order_step_transporter3.title', [], 'ar');
                            $not['en']['title'] = __('notifications.order_step_transporter3.title', [], 'en');
                            $not['ar']['body'] = __('notifications.order_step_transporter3.body', ['code' => $code], 'ar');
                            $not['en']['body'] = __('notifications.order_step_transporter3.body', ['code' => $code], 'en');

                        }else {
                            $not['ar']['title'] = __('notifications.order_step_transporter4.title', [], 'ar');
                            $not['en']['title'] = __('notifications.order_step_transporter4.title', [], 'en');
                            $not['ar']['body'] = __('notifications.order_step_transporter4.body', ['code' => $code], 'ar');
                            $not['en']['body'] = __('notifications.order_step_transporter4.body', ['code' => $code], 'en');
                        }
                    }
                }else {


                    if ($step == 1) {
                        $order_step = 'New';
                        $not['ar']['title'] = __('notifications.order_step1.title', [], 'ar');
                        $not['en']['title'] = __('notifications.order_step1.title', [], 'en');
                        $not['ar']['body'] = __('notifications.order_step1.body', ['code' => $code], 'ar');
                        $not['en']['body'] = __('notifications.order_step1.body', ['code' => $code], 'en');
                        $notarray = [
                            'type' => $type_number,
                            'order_step' => $order_step,
                            'image' => null
                        ];
                        //'New','Price','Accept','Complete'
                        $type_model = 'Provider';
                        $notarray['ar']['title'] = __('notifications.order_step1_provider.title', [], 'ar');
                        $notarray['en']['title'] = __('notifications.order_step1_provider.title', [], 'en');
                        $notarray['ar']['body'] = __('notifications.order_step1_provider.body', ['code' => $code], 'ar');
                        $notarray['en']['body'] = __('notifications.order_step1_provider.body', ['code' => $code], 'en');
                        $user_req = UserRequest::where('order_service_id', $type_id)->first();
                        if ($user_req) {
                            $ids = json_decode($user_req->providers_id, true);
                            $this->pushNotofarray($notarray, $ids, $type_id, $type_model);
                        }

                    } elseif ($step == 2) {
                        $order_step = 'Accept';
                        $not['ar']['title'] = __('notifications.order_step2.title', [], 'ar');
                        $not['en']['title'] = __('notifications.order_step2.title', [], 'en');
                        $not['ar']['body'] = __('notifications.order_step2.body', ['code' => $code], 'ar');
                        $not['en']['body'] = __('notifications.order_step2.body', ['code' => $code], 'en');
                    } elseif ($step == 4) {
                        $order_step = 'Price';
                        $not['ar']['title'] = __('notifications.order_step4.title', [], 'ar');
                        $not['en']['title'] = __('notifications.order_step4.title', [], 'en');
                        $not['ar']['body'] = __('notifications.order_step4.body', ['code' => $code], 'ar');
                        $not['en']['body'] = __('notifications.order_step4.body', ['code' => $code], 'en');

                    }elseif ($step == 5) {
                        $order_step = 'PuckUp';
                        $not['ar']['title'] = __('notifications.order_step_transporter2.title', [], 'ar');
                        $not['en']['title'] = __('notifications.order_step_transporter2.title', [], 'en');
                        $not['ar']['body'] = __('notifications.order_step_transporter2.body', ['code' => $code], 'ar');
                        $not['en']['body'] = __('notifications.order_step_transporter2.body', ['code' => $code], 'en');

                    } else {
                        $order_step = 'Complete';
                        $not['ar']['title'] = __('notifications.order_step3.title', [], 'ar');
                        $not['en']['title'] = __('notifications.order_step3.title', [], 'en');
                        $not['ar']['body'] = __('notifications.order_step3.body', ['code' => $code], 'ar');
                        $not['en']['body'] = __('notifications.order_step3.body', ['code' => $code], 'en');

                    }
                }

                break;

            case 'MaintenanceReport':
                $code=$type_item->transaction?->invoice_no;
                $type_number=1;
                if($step == 1){
                    $order_step='MaintenanceReport';
                    $not['ar']['title']=__('notifications.order_step_MaintenanceReport0.title',[],'ar');
                    $not['en']['title']=__('notifications.order_step_MaintenanceReport0.title',[],'en');
                    $not['ar']['body']=__('notifications.order_step_MaintenanceReport0.body',['code'=>$code],'ar');
                    $not['en']['body']=__('notifications.order_step_MaintenanceReport0.body',['code'=>$code],'en');

                }else{

                }
                break;
            default:

        }
//        $not['type_model']='User';
//        $not['type_id']=$type_id;
        $not['type']=$type_number;
        $not['order_step']=$order_step;
        $not['image']=$image;


        $Notification = ModelNotification::updateorcreate(['type_id'=>$type_id,'type_model'=>'User'],$not);
        if(! is_array($user_id)){
            if (!$Notification->users->contains($user_id)) {
                UserNotification::create([
                    'user_id' => $user_id,
                    'notification_id' => $Notification->id,
                ]);
            }
            $FcmToken=FcmToken::where('user_id',$user_id)->pluck('token');
            $this->sendNotification($Notification,$FcmToken);
        }else{
                $Notification->users()->sync($user_id);

            $FcmToken=FcmToken::wherein('user_id',$user_id)->pluck('token');
            $this->sendNotification($Notification,$FcmToken);
        }


        return true;


    }


    public function pushNotofarray($notarray,$ids,$type_id,$type_model){

        $not_p=ModelNotification::updateorcreate(['type_id'=>$type_id,'type_model'=>$type_model],$notarray);
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
