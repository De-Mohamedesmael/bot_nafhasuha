<?php

namespace App\Http\Controllers\Api\Providers;

use App\Http\Controllers\ApiController;
use App\Http\Resources\OrderServiceAllDataResource;
use App\Http\Resources\Providers\OrderServiceResource;
use App\Models\FcmToken;
use App\Models\OrderService;
use App\Models\PriceQuote;
use App\Models\Provider;
use App\Models\UserRequest;
use App\Utils\ServiceUtil;
use App\Utils\TransactionUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use function App\CPU\translate;

class OrderController extends ApiController
{
    protected $ServiceUtil,$TransactionUtil, $count_paginate = 10;

    public function __construct(ServiceUtil $ServUtil,TransactionUtil $trnUtil)
    {
        Config::set( 'jwt.user', 'App\Models\Provider' );
        Config::set( 'auth.providers.users.model', Provider::class );
        $this->ServiceUtil=$ServUtil;
        $this->TransactionUtil=$trnUtil;

    }

    public function OngoingOrders(Request $request)
    {
        if(!auth()->check())
            return responseApi(403, translate('Unauthenticated user'));

        $count_paginate=$request->count_paginate?:$this->count_paginate;
        $provider=auth()->user();
        $sqlDistance = DB::raw('( 111.045 * acos( cos( radians(' .$provider->lat . ') )
       * cos( radians( `lat` ) )
       * cos( radians( `long` )
       - radians(' . $provider->long  . ') )
       + sin( radians(' . $provider->lat  . ') )
       * sin( radians( `lat` ) ) ) )');
        $orders= auth()->user()->orders()
            ->wherein('status',  ['approved'])->selectRaw("*,{$sqlDistance} as distance")->latest();
        if($count_paginate == 'ALL'){
            $orders=  $orders->get();
        }else{
            $orders=  $orders->simplePaginate($count_paginate);
        }
        return  responseApi(200, translate('return_data_success'),OrderServiceResource::collection($orders));

    }

    public function CompletedOrders(Request $request)
    {
        if(!auth()->check())
            return responseApi(403, translate('Unauthenticated user'));

        $count_paginate=$request->count_paginate?:$this->count_paginate;
        $provider=auth()->user();
        $sqlDistance = DB::raw('( 111.045 * acos( cos( radians(' .$provider->lat . ') )
       * cos( radians( `lat` ) )
       * cos( radians( `long` )
       - radians(' . $provider->long  . ') )
       + sin( radians(' . $provider->lat  . ') )
       * sin( radians( `lat` ) ) ) )');

        $orders= auth()->user()->orders()->Completed()
            ->selectRaw("*,{$sqlDistance} as distance")
            ->latest();

        if($count_paginate == 'ALL'){
            $orders=  $orders->get();
        }else{
            $orders=  $orders->simplePaginate($count_paginate);
        }
        return  responseApi(200, translate('return_data_success'),OrderServiceResource::collection($orders));

    }

    public function getOrderOne($order_id)
    {
        if(!auth()->check())
            return responseApi(403, translate('Unauthenticated user'));

        $provider=auth()->user();
        $sqlDistance = DB::raw('( 111.045 * acos( cos( radians(' .$provider->lat . ') )
           * cos( radians( `lat` ) )
           * cos( radians( `long` )
           - radians(' . $provider->long  . ') )
           + sin( radians(' . $provider->lat  . ') )
           * sin( radians( `lat` ) ) ) )');
        $order= OrderService::where('id',$order_id)
            ->selectRaw("*,{$sqlDistance} as distance")
            ->first();
        if(!$order){
            return responseApi(405, translate('The order is no longer available'));
        }
        $order->getEstimatedTime=$this->ServiceUtil->getEstimatedTime($order->lat,$order->long,$provider->lat,$provider->long);

        return  responseApi(200, translate('return_data_success'),new OrderServiceResource($order));

    }

    public function getMyOrderOne($order_id)
    {
        if(!auth()->check())
            return responseApi(403, translate('Unauthenticated user'));

        $provider=auth()->user();
        $sqlDistance = DB::raw('( 111.045 * acos( cos( radians(' .$provider->lat . ') )
           * cos( radians( `lat` ) )
           * cos( radians( `long` )
           - radians(' . $provider->long  . ') )
           + sin( radians(' . $provider->lat  . ') )
           * sin( radians( `lat` ) ) ) )');
        $order= auth()->user()->orders()
            ->where('id',$order_id)
            ->selectRaw("*,{$sqlDistance} as distance")
            ->first();



        if(!$order){
            return responseApi(405, translate('The order is no longer available'));
        }

        return  responseApi(200, translate('return_data_success'),new OrderServiceResource($order));

    }
    public function submitPrice(Request $request)
    {

        $validator = validator($request->all(), [
            'order_id' => 'required|integer|exists:order_services,id',
            'price' => 'required|numeric'
        ]);
        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

     if(!auth()->check())
         return responseApi(403, translate('Unauthenticated user'));

        $provider=auth()->user();
        $order=OrderService::where('id',$request->order_id)
                    ->where('status','pending')->first();
        $user=$order->user;

        if($user->is_notification){
            $this->pushNotof('Order',$order,$user->id,4);
//           $lang= $user->default_language;
//            $code=$order->transaction?->invoice_no;
//            $FcmToken=FcmToken::where('user_id',$user->id)->pluck('token');
//            $title=__('notifications.PriceRequest.title',[],$lang);
//            $body=__('notifications.PriceRequest.body',['code'=>$code],$lang);
//
//            $type='PriceRequest';
//            $type_id=$request->order_id;
//
//            $this->sendNotificationNat($title,$body,$type,$type_id,$FcmToken);


        }
        if(!$order){
            return responseApi(405, translate('The order is no longer available'));
        }

        return  responseApi(200, translate('return_data_success'));

    }
    public function acceptOrder(Request $request)
    {

        $validator = validator($request->all(), [
            'order_id' => 'required|integer|exists:order_services,id',
        ]);
        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        if(!auth()->check())
            return responseApi(403, translate('Unauthenticated user'));

        $order=OrderService::where('id',$request->order_id)
            ->where('status','pending')->first();

        if(!$order){
            return responseApi(405, translate('The order is no longer available'));
        }
        if($order->type == 'PeriodicInspection'){
            $order->provider_id=auth()->id();
            $order->status="approved";
            $order->save();
            $transaction= $order->transaction;
            if($transaction){
                $transaction->provider_id=auth()->id();
                $transaction->status="approved";

                $transaction->save();
            }
        }


        $this->pushNotof('Order',$order,$order->user_id,2);
        return  responseApi(200, translate('return_data_success'),$order->id);

    }
    public function storeCompletedOrder(Request $request)
    {
        $validator = validator($request->all(), [
            'order_id' => 'required|integer|exists:order_services,id',
        ]);
        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        if(!auth()->check())
            return responseApi(403, translate('Unauthenticated user'));

        $order=OrderService::where('id',$request->order_id)
            ->where('status','!=','pending')->first();

        if(!$order){
            return responseApi(405, translate('The order is no longer available'));
        }
            $order->status="completed";
            $order->save();
            $transaction= $order->transaction;
            if($transaction){
                $transaction->provider_id=auth()->id();
                $transaction->status="completed";
                $transaction->completed_at=now();

                $transaction->save();
            }



        $this->pushNotof('Order',$order,$order->user_id,3);
        return  responseApi(200, translate('return_data_success'),$order->id);

    }
    public function CancelOrdersOngoing(Request $request)
    {
        $validator = validator($request->all(), [
            'order_id' => 'required|integer|exists:order_services,id',
        ]);
        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());
        DB::beginTransaction();
        try {

            $request_order= UserRequest::where('order_service_id',$request->order_id)->first();
            if($request_order){

                $providers = json_decode($request_order->providers_id, true);

                $index = array_search(auth()->id(), $providers);
                if ($index !== false) {
                    unset($providers[$index]);
                }

                $new_providers_id = json_encode($providers);

                $request_order->providers_id = $new_providers_id;
                $request_order->save();
                DB::commit();
                return  responseApi(200, translate('The request has been successfully cancelled'));
            }



            DB::rollBack();

            return responseApiFalse(500, translate('Something went wrong'));

        }catch (\Exception $exception){
            DB::rollBack();
            Log::emergency('File: ' . $exception->getFile() . 'Line: ' . $exception->getLine() . 'Message: ' . $exception->getMessage());
            return responseApiFalse(500, translate('Something went wrong'));
        }
    }
    public function CancelOrdersAccept(Request $request)
    {
        $validator = validator($request->all(), [
            'order_id' => 'required|integer|exists:order_services,id',
            'cancel_reason_id' => 'required|integer|exists:cancel_reasons,id',
        ]);
        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());
        DB::beginTransaction();
        try {
            $action=$this->ServiceUtil->CanceledOrderServiceByProvider($request->order_id,$request->cancel_reason_id,'Provider',auth()->id());
            if($action['status']){
                DB::commit();
                $data=[
                    'is_block'=>$action['is_block'],
                ];
                return  responseApi(200, translate('The request has been successfully cancelled'),$data);

            }
            DB::rollBack();

            return responseApiFalse(500, translate('Something went wrong'));

        }catch (\Exception $exception){
            DB::rollBack();
            Log::emergency('File: ' . $exception->getFile() . 'Line: ' . $exception->getLine() . 'Message: ' . $exception->getMessage());
            return responseApiFalse(500, translate('Something went wrong'));
        }
    }




}
