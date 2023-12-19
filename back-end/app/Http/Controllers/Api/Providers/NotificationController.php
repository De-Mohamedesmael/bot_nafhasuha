<?php

namespace App\Http\Controllers\Api\Providers;

use App\Http\Controllers\ApiController;
use App\Http\Resources\Providers\NotificationResource;
use App\Models\FcmTokenProvider;
use App\Models\Notification;

use App\Models\FcmToken as FcmTokenModel;
use App\Models\Provider;
use App\Models\ProviderNotification;
use App\Models\UserNotification;
use App\Utils\TransactionUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use function App\CPU\translate;

class NotificationController extends ApiController
{
    protected $count_paginate = 10;

    public function __construct()
    {
        Config::set( 'jwt.user', 'App\Models\Provider' );
        Config::set( 'auth.providers.users.model', Provider::class );

    }
    public function index(Request $request)
    {
        if(!auth()->check())
            return responseApi(403, translate('Unauthenticated user'));
        $count_paginate=$request->count_paginate?:$this->count_paginate;

        $notifications= Notification::wherehas('providers',function ($q) {
            $q->where('providers.id',auth()->id());
        })->with('providers_pov',function ($q) {
            $q->where('provider_notifications.provider_id',auth()->id());
        })->latest()->simplePaginate($count_paginate);
        return  responseApi(200, translate('return_data_success'),NotificationResource::collection($notifications));

    }
    public function count()
    {
        if(!auth()->check())
            return responseApi(403, translate('Unauthenticated user'));

        $notificationsCount = auth()->user()->notifications()
            ->wherePivot('is_show',0)
            ->count();

        $data['count_notification'] =$notificationsCount;
        $data['get_orders'] =auth()->user()->get_orders;
        return  responseApi(200, translate('return_data_success'),$data);


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

        ProviderNotification::where([
            'notification_id'=>$request->notification_id,
            'provider_id'=>auth()->id()
        ])->update([
            'is_show'=>1
        ]);
        return  responseApi(200, translate('return_data_success'),new NotificationResource($notification));


    }
    public function save_token(Request $request)
    {
        if(!auth()->check())
            return responseApi(403, translate('Unauthenticated user'));

        $notifications= FcmTokenProvider::where('token',$request->fcm_token)->first();
        if(!$notifications){
            $notifications=  FcmTokenProvider::create([
                "token"=>$request->fcm_token,
            ]);

            if(auth()->user()){
                FcmTokenProvider::where('provider_id',auth()->user()->id)
                    ->delete();

                $notifications->provider_id = auth()->user()->id;
                $notifications->save();

            }
        }else{
            if(auth()->user()){
                $notificationsauth= FcmTokenProvider::where('provider_id',auth()->user()->id)->first();
                if($notificationsauth){
                    $notificationsauth->token =$request->fcm_token;
                    $notificationsauth->save();
                }else{
                    $notifications->provider_id = auth()->user()->id;
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
