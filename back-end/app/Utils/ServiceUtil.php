<?php

namespace App\Utils;

use App\Models\Provider;
use App\Models\Transaction;
use App\Models\User;
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
    public function getProviderForMap($lat, $long, $sortBy = 'totalRate', $sortType = 'Desc',$counPaginate=10,
                                      $service_id=null,$max_distance=null,$min_distance=null): object
    {
        $sqlDistance = DB::raw('( 111.045 * acos( cos( radians(' .$lat . ') )
       * cos( radians( `lat` ) )
       * cos( radians( `long` )
       - radians(' . $long  . ') )
       + sin( radians(' . $lat  . ') )
       * sin( radians( `lat` ) ) ) )');
        // Get providers
        $providers = Provider::withAvg('rates as totalRate', 'rate')
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
        $providers=  $providers->orderBy($sortBy,$sortType)
                ->paginate($counPaginate);

        $providers->each(function ($provider) use ($lat,$long){
            $provider->estimated_time=$this->getEstimatedTime($lat,$long,$provider->lat,$provider->long);
        });

        return $providers;
    }

    /**
     * get Estimated Time
     *
     */
    public function getEstimatedTime($originLat, $originLng, $destinationLat, $destinationLng)
    {
        $apiKey = env('GOOGLE_MAPS_API_KEY'); // استبدل YOUR_API_KEY بمفتاح الواجهة البرمجية الخاص بك
        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins={$originLat},{$originLng}&destinations={$destinationLat},{$destinationLng}&mode=driving&units=metric&key={$apiKey}";

        $response = file_get_contents($url);

        if ($response) {
            $data = json_decode($response, true);

            if ($data['status'] === 'OK') {
                $duration = $data['rows'][0]['elements'][0]['duration']['text'];
                // الوقت المقدر للوصول بالسيارة
                return $duration;
            }

        }

        return 10;
    }



}
