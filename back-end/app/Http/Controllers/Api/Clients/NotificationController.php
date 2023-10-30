<?php

namespace App\Http\Controllers\Api\Clients;

use App\Http\Controllers\ApiController;
use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use App\Models\ShowNotification;

use App\Models\FcmToken as FcmTokenModel;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use function App\CPU\translate;

class NotificationController extends ApiController
{
     public function __construct()
    {

    }

    public function index(Request $request)
    {
        if(!auth()->check())
            return responseApi(403, translate('Unauthenticated user'));

        $notifications= Notification::wherehas('users',function ($q) {
                $q->where('users.id',auth()->id());
            })->with('users_pov',function ($q) {
                $q->where('user_notifications.user_id',auth()->id());
            })->latest()->get();
        return  responseApi(200, translate('return_data_success'),NotificationResource::collection($notifications));

    }
     public function count()
    {
        if(!auth()->check())
            return responseApi(403, translate('Unauthenticated user'));

        $notificationsCount = auth()->user()->notifications()
            ->wherePivot('is_show',0)
            ->count();
        return  responseApi(200, translate('return_data_success'),$notificationsCount);


    }
     public function show(Request $request)
    {
        if(!auth()->check())
            return responseApi(403, translate('Unauthenticated user'));

        $validator = validator($request->all(), [
            'notification_id' => 'nullable|integer|exists:notifications,id',
        ]);
        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());



        $notification= auth()->user()->notifications()->where('notification_id',$request->notification_id)->first();
        if(!$notification){
            return  responseApiFalse(500, translate('not found'));
        }

        UserNotification::where([
            'notification_id'=>$request->notification_id,
            'user_id'=>auth()->id()
        ])->update([
            'is_show'=>1
        ]);
        return  responseApi(200, translate('return_data_success'),new NotificationResource($notification));


    }
    public function save_token(Request $request)
    {
        if(!auth()->check())
            return responseApi(403, translate('Unauthenticated user'));

        $notifications= FcmTokenModel::where('token',$request->fcm_token)->first();
        if(!$notifications){
          $notifications=  FcmTokenModel::create([
                                "token"=>$request->fcm_token,
                              ]);

            if(auth()->user()){
                FcmTokenModel::where('user_id',auth()->user()->id)
                    ->delete();

                $notifications->user_id = auth()->user()->id;
                $notifications->save();

            }
        }else{
            if(auth()->user()){
                $notificationsauth= FcmTokenModel::where('user_id',auth()->user()->id)->first();
                if($notificationsauth){
                    $notificationsauth->token =$request->fcm_token;
                    $notificationsauth->save();
                }else{
                    $notifications->user_id = auth()->user()->id;
                    $notifications->save();
                }


            }
        }
        return responseApi(200,\App\CPU\translate('return_data_success'));
    }
    public function changeStatus(Request $request)
    {
        auth()->user()->is_notification=(auth()->user()->is_notification * (-1) )+ 1;
        auth()->user()->save();
        $data['is_notification']=auth()->user()->is_notification;
        return  responseApi(200, translate('change_Status_success'),$data);

    }

}
