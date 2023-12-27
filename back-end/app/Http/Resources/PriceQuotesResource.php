<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use function App\CPU\translate;
use Carbon\Carbon;

class PriceQuotesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $lat=$this->order->lat??auth()->user()->lat;
        $long=$this->order->long??auth()->user()->long;
        $provider=$this->provider;
        $distance=$provider->getDistanceFromCoordinates($lat,$long);
        return [
            'id'=>$this->id,
            'price'=>(int)$this->price,
            'price_type'=>(int)$this->order?->transaction?->price_type,
            'order_service_id'=>$this->order_service_id,
            'provider_id'=>$provider->id,
            'provider_name'=>$provider->name,
            'provider_image'=>$provider->getFirstMedia('images') != null ? $provider->getFirstMedia('images')->getUrl() : null,
            "provider_type" =>$provider->provider_type,
            "avg_rate" =>number_format($provider->totalRate, 2, '.', '') ,
            'provider_rates_count'=>$provider->id,
            "provider_distance" => number_format($distance, 2, '.', ''),//round(, 2),
            "provider_lat" => $provider->lat,
            "provider_long" =>$provider->long

        ];
    }
}
