<?php

namespace App\Http\Controllers\Api\Providers;

use App\Http\Controllers\ApiController;
use App\Http\Resources\Providers\OrderServiceResource;
use App\Http\Resources\Providers\ProviderHomeResource;

use App\Models\OrderService;
use App\Models\Provider;
use App\Models\UserRequest;
use App\Utils\TransactionUtil;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use function App\CPU\translate;

class HomeController extends ApiController
{
    protected $TransactionUtil;
    protected $count_paginate = 10;
    public function __construct(TransactionUtil $trnUtil)
    {
        Config::set( 'jwt.user', 'App\Models\Provider' );
        Config::set( 'auth.providers.users.model', Provider::class );
        $this->TransactionUtil=$trnUtil;

    }

    public function index()
    {
        if(!auth()->check())
            return responseApi(403, translate('Unauthenticated user'));

        $provider = Provider::where('id',auth()->id())
            ->withAvg('rates as totalRate', 'rate')
            ->withCount(['rates','orders'=>function ($q) {
                $q->wherein('status',['completed']);
            }])
            ->firstOrFail();

        $data['provider']=new ProviderHomeResource($provider);
        $data['my_wallet']=$this->TransactionUtil->getWalletProviderBalance(auth()->user());
        $data['new_orders']=[];
        if($provider->is_activation()){
            $order_service_id=  UserRequest::whereJsonContains('providers_id',auth()->id())->pluck('order_service_id');
//            $transactions= $this->TransactionUtil->getProviderPendingOrderServiceByCategories($categories_ids,$provider);
            $sqlDistance = DB::raw('( 111.045 * acos( cos( radians(' .$provider->lat . ') )
               * cos( radians( `lat` ) )
               * cos( radians( `long` )
               - radians(' . $provider->long  . ') )
               + sin( radians(' . $provider->lat  . ') )
               * sin( radians( `lat` ) ) ) )');
            $orders=OrderService::where('status','pending')
                ->wherein('id',$order_service_id)
                ->selectRaw("*,{$sqlDistance} as distance")->get();

            $data['new_orders']=OrderServiceResource::collection($orders);
        }

        return  responseApi(200, translate('return_data_success'),$data);

    }


}
