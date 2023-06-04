<?php

namespace App\Http\Controllers\Api\Providers;

use App\Http\Controllers\ApiController;
use App\Http\Resources\OrderServiceAllDataResource;
use App\Http\Resources\Providers\OrderServiceResource;
use App\Models\OrderService;
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


    public function indexCanceld(Request $request)
    {
        $count_paginate=$request->count_paginate?:$this->count_paginate;
        $orders= auth()->user()->orders()->Canceld()->latest();

        if($count_paginate == 'ALL'){
            $orders=  $orders->get();
        }else{
            $orders=  $orders->simplePaginate($count_paginate);
        }
        return  responseApi(200, translate('return_data_success'),OrderServiceResource::collection($orders));

    }
    public function show($id)
    {
        $order= auth()->user()->orders()->whereId($id)->first();
        if(!$order)
            return responseApi(404, translate("Order Not Found"));


        return  responseApi(200, translate('return_data_success'),new OrderServiceAllDataResource($order));

    }
    public function GetByInvoiceNo(Request $request)
    {
        $invoice_no=$request->invoice_no;
//        $order= auth()->user()->orders()->wherehas('transaction',function ($q) use ($invoice_no){
//            $q->where('invoice_no',$invoice_no);
//        })->first();
        $order= OrderService::wherehas('transaction',function ($q) use ($invoice_no){
            $q->where('invoice_no',$invoice_no);
        })->first();
        if(!$order)
            return responseApi(404, translate("Order Not Found"));


        return  responseApi(200, translate('return_data_success'),new OrderServiceAllDataResource($order));

    }
}
