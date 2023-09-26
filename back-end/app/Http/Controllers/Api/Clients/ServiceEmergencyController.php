<?php

namespace App\Http\Controllers\Api\Clients;

use App\Http\Controllers\ApiController;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CountryResource;
use App\Http\Resources\PackageResource;
use App\Http\Resources\ProviderServOnlineAllResource;
use App\Http\Resources\ProviderServOnlineResource;
use App\Http\Resources\ServiceResource;
use App\Http\Resources\TransporterResource;
use App\Models\Category;
use App\Models\CategoryService;
use App\Models\Coupon;
use App\Models\CyPeriodic;
use App\Models\Provider;
use App\Models\Service;
use App\Models\Transporter;
use App\Models\UserVehicle;
use App\Models\VehicleModel;
use App\Utils\ServiceUtil;
use App\Utils\TransactionUtil;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use function App\CPU\translate;

class ServiceEmergencyController extends ApiController
{
    protected $ServiceUtil,$TransactionUtil, $count_paginate = 10;

    public function __construct(ServiceUtil $ServUtil,TransactionUtil $trnUtil)
    {
        $this->ServiceUtil=$ServUtil;
        $this->TransactionUtil=$trnUtil;

    }

    public function StoreOrderServiceTransportVehicle(Request $request)
    {
        if(!auth()->check())
            return responseApi(403, translate('Unauthenticated user'));

        $validator = validator($request->all(), [
            'transporter_id' => 'required|integer|exists:transporters,id',
            'address' => 'required|string|max:300',
            'lat' => 'required|string',
            'long' => 'required|string',
            'address_to' => 'required|string|max:300',
            'lat_to' => 'required|string',
            'long_to' => 'required|string',
            'details' => 'required|string|max:1000',
        ]);
        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        $vehicle=null;
        $transporter=Transporter::whereId($request->transporter_id)->first();
        if(!$transporter){
            return responseApiFalse(405, translate('transporter not found'));

        }
        DB::beginTransaction();
        try {
            $cost = $this->ServiceUtil->CostTransportVehicle($request,$transporter);

            $request->merge([
                'service_id'=>6,
                'category_id'=>8,
                'is_emergency'=>1,
                'cost'=>$cost,
            ]);

            $discount['discount_value'] = 0;
            $discount['discount_type'] = null;

            $OrderService = $this->ServiceUtil->StoreOrderService($request,$vehicle,'TransportVehicle', $request->service_id);
            $transaction = $this->TransactionUtil->saveTransactionForOrderService($OrderService,$discount,$request->transporter_id,$cost);
            $OrderService->transaction_id=$transaction->id;
            $OrderService->save();
            DB::commit();
            return  responseApi(200, translate('return_data_success'));

        }catch (\Exception $exception){
            DB::rollBack();
            return$exception ;
            Log::emergency('File: ' . $exception->getFile() . 'Line: ' . $exception->getLine() . 'Message: ' . $exception->getMessage());
            return responseApiFalse(500, translate('Something went wrong'));
        }


    }

    public function getCoupon(Request $request)
    {
        $validator = validator($request->all(), [
            'service_id' => 'required|integer|exists:services,id',
            'coupon_code' => 'required|string',
        ]);
        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        $response = $this->checkCoupon($request->coupon_code,$request->service_id);
        if ($response['status'])
            return responseApi(200, translate('return_data_success'), ['discount' => $response['item']->discount]);

        return responseApiFalse(405 , $response['msg']);
    }
    public function checkCoupon($code,$service_id)
    {
        $date = Carbon::now()->format('Y-m-d');
        $item = Coupon::where('code' , $code)->first();


        if ($item) {
            if(!$item->services()->where('services.id',$service_id)->first())
                return ['status' => false, 'msg' => translate('coupon not active in this service')];

            if ($item->start_date > $date)
                return ['status' => false, 'msg' => translate('coupon not started')];

            if ($item->start_date > $date || $item->end_date < $date || $item->is_active == 0)
                return ['status' => false, 'msg' => translate('coupon expired')];

            if ($item->coupon_users()->where('user_id', auth('api')->id())->count() >= $item->limit_user)
                return ['status' => false, 'msg' => translate('coupon used before')];

            if ($item->limit == $item->use)
                return ['status' => false, 'msg' => translate('coupon expired')];



            return ['status' => true,
                'item' => $item ];
        }
        return ['status' => false, 'msg' => translate('coupon not found')];
    }


}
