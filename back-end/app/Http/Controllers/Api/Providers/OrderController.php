<?php

namespace App\Http\Controllers\Api\Providers;

use App\Http\Controllers\ApiController;
use App\Http\Resources\OrderServiceAllDataResource;
use App\Http\Resources\Providers\OrderServiceResource;
use App\Models\OrderService;
use App\Models\PriceQuote;
use App\Models\Provider;
use App\Utils\ServiceUtil;
use App\Utils\TransactionUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
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

        $orders= auth()->user()->orders()->Completed()->selectRaw("*,{$sqlDistance} as distance")->latest();

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
                                ->wherein('status',  ['pending'])
                                ->selectRaw("*,{$sqlDistance} as distance")
                                ->first();
        if(!$order){
            return responseApi(405, translate('The order is no longer available'));
        }

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

        if(!$order){
            return responseApi(405, translate('The order is no longer available'));
        }
        PriceQuote::create([
            'provider_id'=>$provider->id,
            'order_service_id'=>$order->id,
            'price'=>$request->price,
        ]);
        return  responseApi(200, translate('return_data_success'));

    }

}
