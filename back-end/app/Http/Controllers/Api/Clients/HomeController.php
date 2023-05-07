<?php

namespace App\Http\Controllers\Api\Clients;

use App\Http\Controllers\ApiController;

use App\Http\Resources\PackageResource;
use App\Http\Resources\ServiceResource;
use App\Http\Resources\SliderResource;
use App\Models\Package;
use App\Models\Service;
use App\Models\Slider;
use Illuminate\Http\Request;

use function App\CPU\translate;

class HomeController extends ApiController
{
     public function __construct()
    {

    }

    public function index()
    {
        $data['sliders']= SliderResource::collection(Slider::Active()->orderBy('sort', 'Asc')->get());
        $data['packages']= PackageResource::collection(Package::Active()->orderBy('sort', 'Asc')->get());
        $data['services']= ServiceResource::collection(Service::Active()->orderBy('sort', 'Asc')->get());
        return  responseApi(200, translate('return_data_success'),$data);

    }
     public function count(Request $request)
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
        return $this->apiResponse($request, trans('language.message'),$data, true);

    }

}
