<?php

namespace App\Utils;

use App\Models\OrderService;
use App\Models\Provider;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Geocoder\Facades\Geocoder;
use Location\Coordinate;

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
//        $apiKey = env('GOOGLE_MAPS_API_KEY'); // استبدل YOUR_API_KEY بمفتاح الواجهة البرمجية الخاص بك
//        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins={$originLat},{$originLng}&destinations={$destinationLat},{$destinationLng}&mode=driving&units=metric&key={$apiKey}";
//
//        $response = file_get_contents($url);
//
//        if ($response) {
//            $data = json_decode($response, true);
//
//            if ($data['status'] === 'OK') {
//                $duration = $data['rows'][0]['elements'][0]['duration']['text'];
//                // الوقت المقدر للوصول بالسيارة
//                return $duration;
//            }
//
//        }

        return 10;
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
        return$order ;
    }

}
