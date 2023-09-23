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

class ServiceController extends ApiController
{
    protected $ServiceUtil,$TransactionUtil, $count_paginate = 10;

    public function __construct(ServiceUtil $ServUtil,TransactionUtil $trnUtil)
    {
        $this->ServiceUtil=$ServUtil;
        $this->TransactionUtil=$trnUtil;

    }

    public function index(Request $request)
    {

        $services= Service::Active()->orderBy('sort', 'Asc')->get();
        return  responseApi(200, translate('return_data_success'),ServiceResource::collection($services));

    }


    public function indexCategories(Request $request)
    {

        $validator = validator($request->all(), [
            'service_id' => 'nullable|integer|exists:services,id'
        ]);
        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        $count_paginate=$request->count_paginate?:$this->count_paginate;
        $service_id= $request->service_id;
        $categories= Category::Active();
        if($service_id){
            $categories=$categories->wherehas('services',function ($q_serv) use($service_id){
                $q_serv->where('services.id',$service_id);
            });
        }
        if($count_paginate == 'ALL'){
            $categories=  $categories->orderBy('sort', 'Asc')->get();
        }else {
            $categories = $categories->orderBy('sort', 'Asc')->simplePaginate($count_paginate);
        }
        return  responseApi(200, translate('return_data_success'),CategoryResource::collection($categories));

    }
    public function transportVehicles(Request $request)
    {
        $count_paginate=$request->count_paginate?:$this->count_paginate;
        $transporters= Transporter::Active()->orderBy('sort', 'Asc');
        if($count_paginate == 'ALL'){
            $transporters=  $transporters->get();
        }else {
            $transporters = $transporters->simplePaginate($count_paginate);
        }
        return  responseApi(200, translate('return_data_success'),TransporterResource::collection($transporters));

    }
    public function ProviderMapAll(Request $request)
    {
        $validator = validator($request->all(), [
            'service_id' => 'nullable|integer|exists:services,id',
            'lat' => 'required|string',
            'long' => 'required|string',
//            'count_paginate' => 'nullable|integer',
            'sort_type' => 'nullable|in:Asc,Desc',
            'sort_by' => 'nullable|string|in:distance,totalRate,id',
        ]);
        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        $count_paginate=$request->count_paginate?:$this->count_paginate;
        $sortBy=$request->sort_by??'totalRate';
        $sortType=$request->sort_type??'Desc';
        $service_id=$request->service_id;
        $max_distance=$request->max_distance;
        $min_distance=$request->min_distance;
        if(!auth()->check())
            return responseApi(403, translate('Unauthenticated user'));

        $providers = $this->ServiceUtil->getProviderForMap($request->lat,$request->long,$sortBy,$sortType,
            $count_paginate,$service_id,$max_distance,$min_distance);

        return  responseApi(200, translate('return_data_success'),ProviderServOnlineResource::collection($providers));

    }

    public function ProviderMap(Request $request)
    {
        $validator = validator($request->all(), [
            'service_id' => 'nullable|integer',
            'lat' => 'required|string',
            'long' => 'required|string',
        ]);
        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        $lat=$request->lat;
        $long=$request->long;
        $service_id=$request->service_id;
        if(!auth()->check())
            return responseApi(403, translate('Unauthenticated user'));


        $max_distance = \Settings::get('max_distance',500);
        $sqlDistance = DB::raw('( 111.045 * acos( cos( radians(' .$lat . ') )
       * cos( radians( `lat` ) )
       * cos( radians( `long` )
       - radians(' . $long  . ') )
       + sin( radians(' . $lat  . ') )
       * sin( radians( `lat` ) ) ) )');
        $providers = Provider::Active()->select('id','name','provider_type','lat','long')
            ->selectRaw("{$sqlDistance} as distance")
            ->having('distance', '<=', $max_distance);
        if($service_id){
            $providers=  $providers->wherehas('categories',function ($q) use($service_id){
                $q->wherehas('services',function ($q_serv) use($service_id){
                    $q_serv->where('services.id',$service_id);
                });
            });
        }
        $providers= $providers
            ->get();

        return  responseApi(200, translate('return_data_success'),ProviderServOnlineResource::collection($providers));

    }
    public function ViewProviderMap($provider_id,Request $request)
    {
        $validator = validator($request->all(), [
            'lat' => 'required|string',
            'long' => 'required|string',
        ]);
        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

//        dd($provider);
        if(!auth()->check())
            return responseApi(403, translate('Unauthenticated user'));


        $provider = Provider::find($provider_id);
            if(!$provider)
                return responseApi(404, translate("Page Not Found.If error persists,contact info@gmail.com"));


        $provider = $this->ServiceUtil->getOneProviderForMap($request->lat,$request->long,$provider_id);

        return  responseApi(200, translate('return_data_success'),new ProviderServOnlineAllResource($provider));

    }


    /** store Order Maintenance Service
     * @param Request $request
     * @return Response
     */
    public function StoreOrderServiceMaintenance(Request $request)
    {
        if(!auth()->check())
            return responseApi(403, translate('Unauthenticated user'));

        $validator = validator($request->all(), [
//            'service_id' => 'required|integer|exists:services,id',
            'category_id' => 'required|integer|exists:categories,id',
            'vehicle_id' => 'required|integer|exists:user_vehicles,id',
            'type_from' => 'required|string|in:Home,Center',
            'date_at' => 'required|Date',
            'time_at' => 'required|string',
            'address' => 'required|string|max:300',
            'lat' => 'required|string',
            'long' => 'required|string',
            'details' => 'required|string|max:1000',
            'coupon_code' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'nullable|Image|mimes:jpeg,jpg,png,gif',
        ]);
        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        $vehicle= UserVehicle::where('id',$request->vehicle_id)
            ->where('user_id',auth()->id())
            ->first();



        if(!$vehicle)
            return responseApi(404, translate("Page Not Found.If error persists,contact info@gmail.com"));

        DB::beginTransaction();
        try {
            $request->merge([
                'service_id'=>3
            ]);
            $discount['discount_value'] = 0;
            $discount['discount_type'] = null;
            if ($request->coupon_code) {
                $coupon = $this->checkCoupon($request->coupon_code, $request->service_id);

                if ($coupon['status'] == false)
                    return responseApi('false', $coupon['msg']);

                $item= $coupon['item'];
                $discount['discount_value'] = $item->discount;
                $discount['discount_type'] = $item->type_discount;

                $item->use=$item->use+1;
                $item->users()->attach($item->id);
                $item->save();

            }

            $OrderService = $this->ServiceUtil->StoreOrderService($request,$vehicle,'Maintenance', $request->service_id);
            $transaction = $this->TransactionUtil->saveTransactionForOrderService($OrderService,$discount);
            $OrderService->transaction_id=$transaction->id;
            $OrderService->save();
            DB::commit();
            return  responseApi(200, translate('return_data_success'));

        }catch (\Exception $exception){
            DB::rollBack();
            Log::emergency('File: ' . $exception->getFile() . 'Line: ' . $exception->getLine() . 'Message: ' . $exception->getMessage());
            return responseApiFalse(500, translate('Something went wrong'));
        }


    }



    /** store Order  Vehicle Barriers Service
     * @param Request $request
     * @return Response
     */
    public function StoreOrderServiceVehicleBarriers(Request $request)
    {
        if(!auth()->check())
            return responseApi(403, translate('Unauthenticated user'));

        $validator = validator($request->all(), [
            'vehicle_id' => 'required|integer|exists:user_vehicles,id',
            'date_at' => 'required|Date',
            'time_at' => 'required|string',
            'city_id' => 'required|integer|exists:cities,id',
            'position' => 'required|array',
            'position.*' =>'required|string|in:ALL,Left,Right,Front,Behind',
            'coupon_code' => 'nullable|string',

        ]);
        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        $vehicle= UserVehicle::where('id',$request->vehicle_id)
            ->where('user_id',auth()->id())
            ->first();



        if(!$vehicle)
            return responseApi(404, translate("Page Not Found.If error persists,contact info@gmail.com"));

        DB::beginTransaction();
        try {

            $request->merge([
                'service_id'=>5,
                'category_id'=>6,
            ]);
            $discount['discount_value'] = 0;
            $discount['discount_type'] = null;
            if ($request->coupon_code) {
                $coupon = $this->checkCoupon($request->coupon_code, $request->service_id);

                if ($coupon['status'] == false)
                    return responseApi('false', $coupon['msg']);

                $item= $coupon['item'];
                $discount['discount_value'] = $item->discount;
                $discount['discount_type'] = $item->type_discount;

                $item->use=$item->use+1;
                $item->users()->attach($item->id);
                $item->save();

            }

            $OrderService = $this->ServiceUtil->StoreOrderService($request,$vehicle,'VehicleBarrier', $request->service_id);
            $transaction = $this->TransactionUtil->saveTransactionForOrderService($OrderService,$discount);
            $OrderService->transaction_id=$transaction->id;
            $OrderService->save();
            DB::commit();
            return  responseApi(200, translate('return_data_success'));

        }catch (\Exception $exception){
            DB::rollBack();
           //return$exception ;
            Log::emergency('File: ' . $exception->getFile() . 'Line: ' . $exception->getLine() . 'Message: ' . $exception->getMessage());
            return responseApiFalse(500, translate('Something went wrong'));
        }


    }




    /** store Order Periodic Inspection Service
     * @param Request $request
     * @return Response
     */
    public function StoreOrderServicePeriodicInspection(Request $request)
    {
        if(!auth()->check())
            return responseApi(403, translate('Unauthenticated user'));

        $validator = validator($request->all(), [
            'vehicle_id' => 'required|integer|exists:user_vehicles,id',
            'cy_periodic_id' => 'required|integer|exists:cy_periodics,id',
            'city_id' => 'required|integer|exists:cities,id',
            'address' => 'required|string|max:300',
            'lat' => 'required|string',
            'long' => 'required|string',
            'payment_method' => 'required|string|in:Online,Wallet',
            'coupon_code' => 'nullable|string',

        ]);
        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());



        $vehicle= UserVehicle::where('id',$request->vehicle_id)
            ->where('user_id',auth()->id())
            ->first();



        if(!$vehicle)
            return responseApi(404, translate("Page Not Found.If error persists,contact info@gmail.com"));

        DB::beginTransaction();
        try {
            if($request->payment_method == 'Wallet'){
                $wallet_user=$this->TransactionUtil->getWalletBalance(auth()->user());
                $price_=CyPeriodic::whereId($request->cy_periodic_id)->first()->price;
                if($wallet_user < $price_){
                    return responseApiFalse(405, translate('Your wallet balance is insufficient'));
                }
            }
            $request->merge([
                'service_id'=>1,
                'category_id'=>4,
            ]);
            $discount['discount_value'] = 0;
            $discount['discount_type'] = null;
            if ($request->coupon_code) {
                $coupon = $this->checkCoupon($request->coupon_code, $request->service_id);

                if ($coupon['status'] == false)
                    return responseApi('false', $coupon['msg']);

                $item= $coupon['item'];
                $discount['discount_value'] = $item->discount;
                $discount['discount_type'] = $item->type_discount;

                $item->use=$item->use+1;
                $item->users()->attach($item->id);
                $item->save();

            }

            $OrderService = $this->ServiceUtil->StoreOrderService($request,$vehicle,'PeriodicInspection', $request->service_id);
            $transaction = $this->TransactionUtil->saveTransactionForOrderService($OrderService,$discount);
            $OrderService->transaction_id=$transaction->id;
            $OrderService->save();
            DB::commit();
            return  responseApi(200, translate('return_data_success'));

        }catch (\Exception $exception){
            DB::rollBack();
//           return$exception ;
            Log::emergency('File: ' . $exception->getFile() . 'Line: ' . $exception->getLine() . 'Message: ' . $exception->getMessage());
            return responseApiFalse(500, translate('Something went wrong'));
        }


    }
    /** store Order consultation Service
     * @param Request $request
     * @return Response
     */
    public function StoreOrderServiceConsultation(Request $request)
    {
        if(!auth()->check())
            return responseApi(403, translate('Unauthenticated user'));

        $validator = validator($request->all(), [
            'vehicle_id' => 'required|integer|exists:user_vehicles,id',
            'category_id' => 'required|integer',
            'city_id' => 'required|integer|exists:cities,id',
            'details' => 'required|string|max:1000',
            'coupon_code' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'nullable|Image|mimes:jpeg,jpg,png,gif',

        ]);
        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        $vehicle= UserVehicle::where('id',$request->vehicle_id)
            ->where('user_id',auth()->id())
            ->first();



        if(!$vehicle)
            return responseApi(404, translate("Page Not Found.If error persists,contact info@gmail.com"));

        DB::beginTransaction();
        try {

            $request->merge([
                'service_id'=>4,

            ]);
            if($request->category_id == 0){

                $request->merge([  'category_id'=>null,
                    ]);
            }
            $discount['discount_value'] = 0;
            $discount['discount_type'] = null;
            if ($request->coupon_code) {
                $coupon = $this->checkCoupon($request->coupon_code, $request->service_id);

                if ($coupon['status'] == false)
                    return responseApi('false', $coupon['msg']);

                $item= $coupon['item'];
                $discount['discount_value'] = $item->discount;
                $discount['discount_type'] = $item->type_discount;

                $item->use=$item->use+1;
                $item->users()->attach($item->id);
                $item->save();

            }

            $OrderService = $this->ServiceUtil->StoreOrderService($request,$vehicle,'Consultation', $request->service_id);
            $transaction = $this->TransactionUtil->saveTransactionForOrderService($OrderService,$discount);
            $OrderService->transaction_id=$transaction->id;
            $OrderService->save();
            DB::commit();
            return  responseApi(200, translate('return_data_success'));

        }catch (\Exception $exception){
            DB::rollBack();
//            return$exception ;
            Log::emergency('File: ' . $exception->getFile() . 'Line: ' . $exception->getLine() . 'Message: ' . $exception->getMessage());
            return responseApiFalse(500, translate('Something went wrong'));
        }


    }
    /** store Order Transport Vehicle Service
     * @param Request $request
     * @return Response
     */
    public function StoreOrderServiceTransportVehicle(Request $request)
    {
        if(!auth()->check())
            return responseApi(403, translate('Unauthenticated user'));

        $validator = validator($request->all(), [
            'transporter_id' => 'required|integer|exists:transporters,id',
            'date_at' => 'required|Date',
            'time_at' => 'required|string',
            'address' => 'required|string|max:300',
            'lat' => 'required|string',
            'long' => 'required|string',
            'address_to' => 'required|string|max:300',
            'lat_to' => 'required|string',
            'long_to' => 'required|string',
            'details' => 'required|string|max:1000',
            'coupon_code' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'nullable|Image|mimes:jpeg,jpg,png,gif',

        ]);
        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        $vehicle=null;
        DB::beginTransaction();
        try {

            $request->merge([
                'service_id'=>2,
                'category_id'=>5,
            ]);

            $discount['discount_value'] = 0;
            $discount['discount_type'] = null;
            if ($request->coupon_code) {
                $coupon = $this->checkCoupon($request->coupon_code, $request->service_id);

                if ($coupon['status'] == false)
                    return responseApi('false', $coupon['msg']);

                $item= $coupon['item'];
                $discount['discount_value'] = $item->discount;
                $discount['discount_type'] = $item->type_discount;

                $item->use=$item->use+1;
                $item->users()->attach($item->id);
                $item->save();

            }

            $OrderService = $this->ServiceUtil->StoreOrderService($request,$vehicle,'TransportVehicle', $request->service_id);
            $transaction = $this->TransactionUtil->saveTransactionForOrderService($OrderService,$discount,$request->transporter_id);
            $OrderService->transaction_id=$transaction->id;
            $OrderService->save();
            DB::commit();
            return  responseApi(200, translate('return_data_success'));

        }catch (\Exception $exception){
            DB::rollBack();
            //return$exception ;
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
