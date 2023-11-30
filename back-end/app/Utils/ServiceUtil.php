<?php

namespace App\Utils;

use App\Models\CyPeriodicProvider;
use App\Models\OrderService;
use App\Models\PriceQuote;
use App\Models\PriceRequest;
use App\Models\Provider;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Spatie\Geocoder\Facades\Geocoder;
use Location\Coordinate;
use GuzzleHttp\Client;
use function App\CPU\translate;

class ServiceUtil
{
    /**
     * Get Providers & Get the distance in kilometers and the time of arrival via Google Maps by lat lang
     *
     * @param string $lat
     * @param string $long
     * @param string $sortBy
     * @param string $sortType
     * @param int $counPaginate
     * @param int $service_id
     * @param int $max_distance
     * @param int $min_distance
     * @return object
     */
    public function getProviderForMap($lat, $long, $sortBy= 'Desc' , $sortType = 'Desc',$counPaginate=10,
                                      $service_id=null,$max_distance=null,$min_distance=null): object
    {
        $sqlDistance = DB::raw('( 111.045 * acos( cos( radians(' .$lat . ') )
       * cos( radians( `lat` ) )
       * cos( radians( `long` )
       - radians(' . $long  . ') )
       + sin( radians(' . $lat  . ') )
       * sin( radians( `lat` ) ) ) )');
        // Get providers
        $providers = Provider::Active()->withAvg('rates as totalRate', 'rate')
            ->withCount('rates')->selectRaw("{$sqlDistance} as distance");
            if($service_id){
                $providers=  $providers->wherehas('categories',function ($q) use($service_id){
                    $q->wherehas('services',function ($q_serv) use($service_id){
                        $q_serv->where('services.id',$service_id);
                    });
                });
            }

            if($max_distance){
                $providers=  $providers->having('distance', '<', $max_distance);
            }
            if($min_distance){
                $providers=  $providers->having('distance', '>', $min_distance);
            }
        $providers=     $counPaginate !='ALL'?
                 $providers->orderBy($sortBy,$sortType)->paginate($counPaginate):

                $providers->orderBy($sortBy,$sortType)->get();

//        $providers->each(function ($provider) use ($lat,$long){
//            $provider->estimated_time=$this->getEstimatedTime($lat,$long,$provider->lat,$provider->long);
//        });

        return $providers;
    }



    /**
     * Get Providers & Get the distance in kilometers and the time of arrival via Google Maps by lat lang
     *
     * @param string $lat
     * @param string $long
     * @param object $provider
     * @return object
     */
    public function getOneProviderForMap($lat, $long,$provider_id): object
    {
        $sqlDistance = DB::raw('( 111.045 * acos( cos( radians(' .$lat . ') )
       * cos( radians( `lat` ) )
       * cos( radians( `long` )
       - radians(' . $long  . ') )
       + sin( radians(' . $lat  . ') )
       * sin( radians( `lat` ) ) ) )');
        // Get providers
        $provider = Provider::where('id',$provider_id)->withAvg('rates as totalRate', 'rate')
            ->withCount('rates')->selectRaw("{$sqlDistance} as distance")->first();

//        $providers->each(function ($provider) use ($lat,$long){
            $provider->estimated_time=$this->getEstimatedTime($lat,$long,$provider->lat,$provider->long);
//        });

        return $provider;
    }


    /**
     * get Estimated Time
     *
     */
    public function getEstimatedTime($originLat, $originLng, $destinationLat, $destinationLng)
    {


        $client = new Client();
        $response = $client->get('https://maps.googleapis.com/maps/api/directions/json', [
            'query' => [
                'language'=>app()->getLocale(),
                'origin' => "$originLat".','."$originLng",
                'destination' =>"$destinationLat".','."$destinationLng",
                'key' => env('GOOGLE_MAPS_API_KEY'),
            ],
        ]);

        $data = $response->getBody();
        $result = json_decode($data);
        if($result->status=="OK"){

            return $result->routes[0]->legs[0]->duration->text;
        }

        return '';
    }




    /**
     * Store Order Service  by request data & vehicle & type
     *
     * @param Request $request
     * @param object $vehicle
     * @param string $type
     * @param int $service_id
     * @return object
     */
    public function StoreOrderService($request, $vehicle,$type,$service_id): object
    {
        $all_data=[];

        $not_request_price=['PeriodicInspection'];
        //Tire&TransportVehicle&ChangeBattery&Petrol&SubscriptionBattery
        $is_offer_price=1;
        if(in_array($type,$not_request_price)){
            $is_offer_price=0;
        }
        switch ($type){
            case "VehicleBarrier" :
//                $type= 'Maintenance'.$type;
                $all_data['city_id']=$request->city_id;
                $all_data['position']=$request->position;

                break;
            case 'TransportVehicle':
                $all_data['address_to'] =$request->address_to;
                $all_data['lat_to'] =$request->lat_to;
                $all_data['long_to'] =$request->long_to;
                break;
            case 'PeriodicInspection':
                $all_data['cy_periodic_id'] =$request->cy_periodic_id;
                break;
            default :
                break;
        }

        $data=[
            'service_id'=>$service_id,
            'user_id'=>auth()->id(),
            'category_id'=>$request->category_id,
            'vehicle_id'=>$vehicle->id??null,
            'type'=>$type,
            'status'=>'pending',
            'type_from'=>$request->type_from,
            'date_at'=>$request->date_at,
            'time_at'=>$request->time_at,
            'address'=>$request->address,
            'payment_method'=>$request->payment_method,
            'lat'=>$request->lat,
            'long'=>$request->long,
            'details'=>$request->details
        ];
        $all_data=  array_merge($all_data,$data);
       $order=OrderService::create($all_data);

        if ($request->hasFile('images')) {
            $files = $request->file('images');
            foreach ($files as $file){
                $extension = $file->getClientOriginalExtension();
                $order->addMedia($file)
                    ->usingFileName(time() . '.' . $extension)
                    ->toMediaCollection('images');
            }
        }

        if ($request->hasFile('videos')) {
            $files = $request->file('videos');
            foreach ($files as $file){
                $extension = $file->getClientOriginalExtension();
                $order->addMedia($file)
                    ->usingFileName(time() . '.' . $extension)
                    ->toMediaCollection('videos');
            }
        }


        if($type == 'PeriodicInspection'){
            $providers_id=CyPeriodicProvider::where('cy_periodic_id',$request->cy_periodic_id)
                ->pluck('provider_id')->toArray();

        }else{
            $max_distance=\Settings::get('max_distance',500);
            $sqlDistance = DB::raw('( 111.045 * acos( cos( radians(' .$request->lat . ') )
               * cos( radians( `lat` ) )
               * cos( radians( `long` )
               - radians(' . $request->long  . ') )
               + sin( radians(' . $request->lat  . ') )
               * sin( radians( `lat` ) ) ) )');
            // Get providers id
            $providers_id = Provider::Active()->withAvg('rates as totalRate', 'rate')
                ->withCount('rates')->selectRaw("{$sqlDistance} as distance")
                ->wherehas('categories',function ($q) use($request){
                    $q->where('categories.id',$request->category_id);
                })->having('distance', '<=', $max_distance)
                ->pluck('providers.id')->toArray();
        }
        if($providers_id){
            UserRequest::create([
                'order_service_id'=>$order->id,
                'user_id'=>auth()->id(),
                'is_offer_price'=>$is_offer_price,
                'suggested_price'=>$request->cost,
                'providers_id'=>json_encode($providers_id)
            ]);
        }

        return $order ;
    }
    /**
     * get Price Quotes For Order
     *
     * @param OrderService $order
     * @param  $count_paginate
     * @return object
     */
    public function getPriceQuotesForOrder($order,$count_paginate): object
    {
        $PriceQuote= PriceRequest::with(['provider'=>function ($q)  {
            $q->withAvg('rates as totalRate', 'rate')
                ->withCount('rates');
        }])->whereNull('status')
            ->where('order_service_id',$order->id);

        if($count_paginate == 'ALL'){
            $PriceQuote=  $PriceQuote->get();
        }else{
            $PriceQuote=  $PriceQuote->simplePaginate($count_paginate);
        }
        return $PriceQuote ;
    }
     /**
     * get Cost for Transport Vehicle
     *
     * @param  $request
     * @return float
     */
    public function CostTransportVehicle($request,$transporter): float
    {

        $lat1=$request->lat;
        $long1=$request->long;
        $lat2=$request->lat_to;
        $long2=$request->long_to;
        $time=$this->suggestedTimeBetweenTheTwoPoints($lat1,$long1,$lat2,$long2);
        $distance=$this->suggestedDistanceBetweenTheTwoPoints($lat1,$long1,$lat2,$long2);

        $price[]=round($transporter->price, 2);
        $price[]=round($transporter->price_for_minute*$time, 2);
        $price[]= round($transporter->price_for_kilo*$distance, 2);

        return max($price) ;
    }
    /**
     * get The suggested time between the two points
     *
     * @param  $lat1
     * @param  $long1
     * @param  $lat2
     * @param  $long2
     * @return float
     */
    public function suggestedTimeBetweenTheTwoPoints($lat1,$long1,$lat2,$long2): float
    {

//        $client = new \GuzzleHttp\Client();
//        $apiKey = env('GOOGLE_MAPS_API_KEY');
//
//        $response = $client->get("https://maps.googleapis.com/maps/api/directions/json?origin={$lat1},{$long1}&destination={$lat2},{$long2}&key={$apiKey}");
//
//        $data = json_decode($response->getBody());
//
//        if ($data->status === 'OK') {
//            $duration = $data->routes[0]->legs[0]->duration->text;
//            return (float)$duration;
//        } else {
            return 10.00;
//        }


    }
    /**
     * get The suggested distance between the two points
     *
     * @param  $lat1
     * @param  $long1
     * @param  $lat2
     * @param  $long2
     * @return float
     */
    public function suggestedDistanceBetweenTheTwoPoints($lat1,$long1,$lat2,$long2): float
    {

        $lat1 = deg2rad($lat1);
        $long1 = deg2rad($long1);
        $lat2 = deg2rad($lat2);
        $long2 = deg2rad($long2);
        $latDiff = $lat2 - $lat1;
        $longDiff = $long2 - $long1;
        $distance = 2 * 6371 * asin(sqrt(
            pow(sin($latDiff / 2), 2) +
            cos($lat1) * cos($lat2) * pow(sin($longDiff / 2), 2)
        ));

        return $distance;
    }


    /**
     * Canceled Order Service after accept provider
     *
     * @param int $order_id
     * @param  int $cancel_reason_id
     * @param  string $type
     * @param  int $canceled_by
     */
    public function CanceledOrderService($order_id,$cancel_reason_id,$type,$canceled_by)
    {
       $order= OrderService::whereId($order_id)->first();
        if(!$order){
            return false;
        }
        $order->update([
            'status'=>'canceled',
            'canceled_by'=>$canceled_by,
            'canceled_type'=>$type,
            'cancel_reason_id'=>$cancel_reason_id,
        ]);
        $order->transaction->update([
            'status'=>'canceled',
        ]);
        return true;
    }

    /**
     * Canceled Order Service after accept  By Provider
     *
     * @param int $order_id
     * @param  int $cancel_reason_id
     * @param  string $type
     * @param  int $canceled_by
     */
    public function CanceledOrderServiceByProvider($order_id,$cancel_reason_id,$type,$canceled_by)
    {
        $order= OrderService::whereId($order_id)->first();
        if(!$order){
            $data=[
                'status'=>false
            ];
            return $data;
        }
        $order->update([
            'status'=>'canceled',
            'canceled_by'=>$canceled_by,
            'canceled_type'=>$type,
            'cancel_reason_id'=>$cancel_reason_id,
        ]);
        $order->transaction->update([
            'status'=>'canceled',
        ]);
        $count_cancel_for_day=OrderService::where('status','canceled')
            ->where('canceled_type',$type)
            ->where('canceled_by',$canceled_by)
           ->whereDate('updated_at', date('Y-m-d'))->count();
        $limit_cancel=\Settings::get('limit_cancel',2);
        $is_block= $count_cancel_for_day+1 >= $limit_cancel;
        if($is_block){
            $this->BlockProvider($canceled_by);
        }
        $data=[
            'status'=>true,
            'is_block'=>$is_block
        ];
        return $data;
    }

    /**
     * Block Provider
     *
     * @param  int $provider_id
     */
    public function BlockProvider($provider_id)
    {
        Provider::whereId($provider_id)
            ->update([
               'is_active'=>0,
            ]);

    }

    /**
     * send Offer Price To user
     *
     * @param  OrderService $order
     * @param   $amount
     * @param  int $provider_id
     */
    public function sendOfferPrice($order,$amount,$provider_id)
    {
        $provider= Provider::whereId($provider_id)->first();
        if(!$provider){
            return[
                'success'=>false,
                'msg'=>translate('provider_not_found'),
                ];
        }
        return[
            'success'=>true,
            "order_id"=>$order->id,
            "price"=>$amount,
            "type_provider"=>$provider->provider_type,//ProviderCenter,Provider
            "image"=>$provider->getFirstMedia('images') != null ? $provider->getFirstMedia('images')->getUrl() : null,
            "name"=>$provider->name,
            "number_phone"=>$provider->phone
        ];

    }

    }
