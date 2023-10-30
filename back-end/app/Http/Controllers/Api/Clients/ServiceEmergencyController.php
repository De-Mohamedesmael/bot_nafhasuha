<?php

namespace App\Http\Controllers\Api\Clients;

use App\Http\Controllers\ApiController;
use App\Http\Resources\GasolineResource;
use App\Http\Resources\TireResource;
use App\Http\Resources\TypeBatteryResource;
use App\Http\Resources\UserVehicleSimpleResource;
use App\Models\Coupon;
use App\Models\Tire;
use App\Models\Transporter;
use App\Models\TypeBattery;
use App\Models\TypeGasoline;
use App\Models\UserVehicle;
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

    /** Store Order Service Transport Vehicle
     * @param Request $request
     * @return \Exception|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|Response
     */
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
            'details' => 'nullable|string|max:1000',
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
            $this->pushNotof('Order',$OrderService,auth()->id(),1);

            DB::commit();
            return  responseApi(200, translate('return_data_success'));

        }catch (\Exception $exception){
            DB::rollBack();
//            return$exception ;
            Log::emergency('File: ' . $exception->getFile() . 'Line: ' . $exception->getLine() . 'Message: ' . $exception->getMessage());
            return responseApiFalse(500, translate('Something went wrong'));
        }


    }

    /**
     * Get Data For Service Battery
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function GetDataServiceBattery()
    {
        if(!auth()->check())
            return responseApi(403, translate('Unauthenticated user'));

        $User_vehicles= auth()->user()->vehicles()->Active()->latest()->get();
        $types=TypeBattery::Active()->get();
        $data['change_price']=\Settings::get('change_price');
        $data['subscription_price']=\Settings::get('subscription_price');
        $data['types']=TypeBatteryResource::collection($types);
        $data['user_vehicles']=UserVehicleSimpleResource::collection($User_vehicles);

        return responseApi(200,\App\CPU\translate('return_data_success'), $data);

    }

    public function StoreOrderServiceBattery(Request $request)
    {
        if(!auth()->check())
            return responseApi(403, translate('Unauthenticated user'));

        $validator = validator($request->all(), [
            'type' => 'required|string|in:Change,Subscription',
//            'vehicle_id' => 'required_if:type,==,Change|integer|exists:user_vehicles,id',
//            'type_battery_id' => 'required_if:type,==,Change|integer|exists:type_batteries,id',
            'address' => 'required|string|max:300',
            'lat' => 'required|string',
            'long' => 'required|string',
            'details' => 'nullable|string|max:1000',
        ]);
        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        $vehicle= UserVehicle::where('id',$request->vehicle_id)
            ->where('user_id',auth()->id())
            ->first();



        if(!$vehicle && $request->type == 'Change')
            return responseApi(404, translate("Page Not Found.If error persists,contact info@gmail.com"));


        //**

        DB::beginTransaction();
        try {
            $cost= $request->type == 'Change' ?  \Settings::get('change_price') : \Settings::get('subscription_price');
            $request->merge([
                'service_id'=>6,
                'category_id'=>7,
                'is_emergency'=>1,
                'cost'=>$cost,
            ]);

            $discount['discount_value'] = 0;
            $discount['discount_type'] = null;

            $OrderService = $this->ServiceUtil->StoreOrderService($request,$vehicle,$request->type.'Battery', $request->service_id);

            $transaction = $this->TransactionUtil->saveTransactionForOrderService($OrderService,$discount,$request->type_battery_id,$cost);
            $OrderService->transaction_id=$transaction->id;
            $OrderService->save();
            $this->pushNotof('Order',$OrderService,auth()->id(),1);

            DB::commit();
            return  responseApi(200, translate('return_data_success'));

        }catch (\Exception $exception){
            DB::rollBack();
//            return$exception ;
            Log::emergency('File: ' . $exception->getFile() . 'Line: ' . $exception->getLine() . 'Message: ' . $exception->getMessage());
            return responseApiFalse(500, translate('Something went wrong'));
        }


    }






    /**
     * Get Data For Service Petrol
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function GetDataServicePetrol()
    {
        $types=TypeGasoline::Active()->get();
        $data['Petrol']=GasolineResource::collection($types);
        return responseApi(200,\App\CPU\translate('return_data_success'), $data);

    }


    public function StoreOrderServicePetrol(Request $request)
    {
        if(!auth()->check())
            return responseApi(403, translate('Unauthenticated user'));

        $validator = validator($request->all(), [
            'amount' => 'required|numeric',
            'type_id' => 'required|integer|exists:type_gasolines,id',
            'address' => 'required|string|max:300',
            'lat' => 'required|string',
            'long' => 'required|string',
            'details' => 'nullable|string|max:1000',
        ]);
        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        $vehicle= null;



        DB::beginTransaction();
        try {
            $cost= $request->amount;
            $request->merge([
                'service_id'=>6,
                'category_id'=>10,
                'is_emergency'=>1,
                'cost'=>$cost,
            ]);

            $discount['discount_value'] = 0;
            $discount['discount_type'] = null;

            $OrderService = $this->ServiceUtil->StoreOrderService($request,$vehicle,'Petrol', $request->service_id);

            $transaction = $this->TransactionUtil->saveTransactionForOrderService($OrderService,$discount,$request->type_id,$cost);
            $OrderService->transaction_id=$transaction->id;
            $OrderService->save();
            $this->pushNotof('Order',$OrderService,auth()->id(),1);

            DB::commit();
            return  responseApi(200, translate('return_data_success'));

        }catch (\Exception $exception){
            DB::rollBack();
//            return$exception ;
            Log::emergency('File: ' . $exception->getFile() . 'Line: ' . $exception->getLine() . 'Message: ' . $exception->getMessage());
            return responseApiFalse(500, translate('Something went wrong'));
        }


    }


    /**
     * Get Data For Service Tire
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function GetDataServiceTire()
    {
        $types=Tire::Active()->get();
        $data['tires']=TireResource::collection($types);
        return responseApi(200,\App\CPU\translate('return_data_success'), $data);

    }


    public function StoreOrderServiceTire(Request $request)
    {
        if(!auth()->check())
            return responseApi(403, translate('Unauthenticated user'));

        $validator = validator($request->all(), [
            'type_id' => 'required|integer|exists:tires,id',
            'address' => 'required|string|max:300',
            'lat' => 'required|string',
            'long' => 'required|string',
            'details' => 'nullable|string|max:1000',
        ]);
        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        $vehicle= null;

        $tire=Tire::whereId($request->type_id)->first();
        if(!$tire)
            return responseApi(404, translate("tire Not Found.If error persists,contact info@gmail.com"));


        DB::beginTransaction();
        try {

            $cost= $tire->price;
            $request->merge([
                'service_id'=>6,
                'category_id'=>9,
                'is_emergency'=>1,
                'cost'=>$cost,
            ]);

            $discount['discount_value'] = 0;
            $discount['discount_type'] = null;

            $OrderService = $this->ServiceUtil->StoreOrderService($request,$vehicle,'Tire', $request->service_id);

            $transaction = $this->TransactionUtil->saveTransactionForOrderService($OrderService,$discount,$request->type_id,$cost);
            $OrderService->transaction_id=$transaction->id;
            $OrderService->save();
            $this->pushNotof('Order',$OrderService,auth()->id(),1);

            DB::commit();
            return  responseApi(200, translate('return_data_success'));

        }catch (\Exception $exception){
            DB::rollBack();
            //    return$exception ;
            Log::emergency('File: ' . $exception->getFile() . 'Line: ' . $exception->getLine() . 'Message: ' . $exception->getMessage());
            return responseApiFalse(500, translate('Something went wrong'));
        }


    }



    public function checkCoupon($code,$category_id,$service_id)
    {
        $date = Carbon::now()->format('Y-m-d');
        $item = Coupon::where('code' , $code)->first();


        if ($item) {
            if(!$item->is_multi_use){
                $type_id= $item->type_model == 'Category' ? $category_id:$service_id;
                    if(!$item->where('type_id',$type_id)->first())
                        return ['status' => false, 'msg' => translate('coupon not active in this service')];


            }


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
