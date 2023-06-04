<?php

namespace App\Http\Controllers\Api\Providers;

use App\Http\Controllers\ApiController;
use App\Http\Resources\OrderServiceResource;
use App\Http\Resources\Providers\ProviderHomeResource;
use App\Http\Resources\ProviderServOnlineAllResource;
use App\Http\Resources\ProviderServOnlineResource;
use App\Models\Provider;
use App\Utils\TransactionUtil;
use Illuminate\Support\Facades\Config;
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
       $categories_ids= auth()->user()->categories->pluck('id');



        $provider = Provider::where('id',auth()->id())
            ->withAvg('rates as totalRate', 'rate')
            ->withCount(['rates','orders'=>function ($q) {
                $q->wherein('status',['completed']);
            }])
            ->firstOrFail();

        $data['provider']=new ProviderHomeResource($provider);

        $transactions= $this->TransactionUtil->getProviderPendingOrderServiceByCategories($categories_ids);

        $data['transactions']=OrderServiceResource::collection($transactions);
        return  responseApi(200, translate('return_data_success'),$data);

    }


}
