<?php

namespace App\Http\Controllers\Api\Clients;

use App\Http\Controllers\ApiController;

use App\Http\Resources\ComponentListWithimageResource;
use App\Http\Resources\DietHomeResource;
use App\Http\Resources\DietItemResource;
use App\Http\Resources\EaterResource;
use App\Http\Resources\EaterWithComponentResource;
use App\Http\Resources\MealResource;
use App\Http\Resources\SubscribeDietResource;
use App\Models\Component;
use App\Models\ComponentUser;
use App\Models\Coupon;
use App\Models\Diet;
use App\Models\Duration;
use App\Models\Eater;
use App\Models\Meal;
use App\Models\SubscribeDiet;
use Illuminate\Http\Request;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use function App\CPU\translate;

class PaymentController extends ApiController
{
    protected $count_paginate = 10;

    public function __construct()
    {

    }
    public function GetCheckouts(Request $request)
    {
        $validator = validator($request->all(), [
            'amount' => 'required',
            'currency' => 'required',
        ]);

        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        $amount=$request->amount;
        $currency=$request->currency;
        $url=env('HYPERPAY_URL',"https://eu-test.oppwa.com/v1");
        $entityId=env('HYPERPAY_entityId',"8a8294174b7ecb28014b9699220015ca");
        $paymentType=env('HYPERPAY_paymentType',"DB");


        $url = "{$url}/checkouts";
        $data = "entityId={$entityId}" .
            "&amount={$amount}" .
            "&currency={$currency}" .
            "&paymentType={$paymentType}";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization:Bearer OGE4Mjk0MTc0YjdlY2IyODAxNGI5Njk5MjIwMDE1Y2N8c3k2S0pzVDg='));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);
        if(curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        $response=json_decode($responseData, true);
        if($response['result']['code']=="000.200.100"){
            $data=[
                'checkoutId'=>$response['id'],
            ];
            return responseApi(200,\App\CPU\translate('return_data_success'),$data);

        }

        return responseApiFalse(500, translate('Something went wrong'));


    }

}
