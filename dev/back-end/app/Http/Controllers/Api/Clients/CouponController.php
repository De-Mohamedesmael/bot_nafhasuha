<?php

namespace App\Http\Controllers\Api\Clients;

use App\Http\Controllers\ApiController;
use App\Models\Coupon;
use App\Utils\Util;
use Carbon\Carbon;
use Illuminate\Http\Request;


class CouponController extends ApiController
{
    protected $commonUtil;
    protected $count_paginate = 10;

    public function __construct(Util $commonUtil)
    {
        $this->commonUtil = $commonUtil;
    }


    public function countries(Request $request){

        return responseApi(200,\App\CPU\translate('return_data_success'), CountryResource::collection($countries));
    }


    public function getDetails($coupon_code, $customer_id)
    {
        $store_id = request()->get('store_id');
        $customer = Customer::find($customer_id);
        $customer_type_id = (string) $customer->customer_type_id;
        $coupon_details = Coupon::where('coupon_code', $coupon_code)->whereJsonContains('customer_type_ids', $customer_type_id)->whereJsonContains('store_ids', $store_id)->where('used', 0)->first();

        if (empty($coupon_details)) {
            return [
                'success' => false,
                'msg' => __('lang.invalid_coupon_code')
            ];
        }
        if ($coupon_details->active == 0) {
            return [
                'success' => false,
                'msg' => __('lang.coupon_suspended')
            ];
        }
        if (!empty($coupon_details->expiry_date)) {
            if (Carbon::now()->gt(Carbon::parse($coupon_details->expiry_date))) {
                return [
                    'success' => false,
                    'msg' => __('lang.coupon_expired')
                ];
            }
        }

        return [
            'success' => true,
            'data' => $coupon_details->toArray()
        ];
    }

}

