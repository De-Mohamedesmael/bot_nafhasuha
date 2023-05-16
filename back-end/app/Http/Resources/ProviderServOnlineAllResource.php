<?php

namespace App\Http\Resources;

use App\Models\Category;
use Illuminate\Http\Resources\Json\JsonResource;

class ProviderServOnlineAllResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "phone" =>$this->phone ,
            "email" =>$this->email,
            'image'=>$this->getFirstMedia('images') != null ? $this->getFirstMedia('images')->getUrl() : null,
            "provider_type" =>$this->getType(),
            "address" => $this->address,
             "lat" => $this->lat,
            "long" =>$this->long ,
            "avg_rate" =>round($this->totalRate, 2) ,
            "rates_count" => $this->rates_count,
            "distance" => round($this->distance, 2),
            "estimated_time" => round($this->estimated_time, 2),
            'categories'=>CategoryResource::collection($this->categories),
            'country'=>new CountryResource($this->country),
            'city'=>new CityResource($this->city),
            'area'=>new AreaResource($this->area),
        ];
    }
}
