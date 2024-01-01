<?php

namespace App\Http\Resources\Providers;

use App\Http\Resources\AreaResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CityResource;
use App\Http\Resources\CountryResource;
use App\Http\Resources\TransporterResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProviderResource extends JsonResource
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
            "is_active" =>$this->is_active,
            'is_activation'=>$this->is_activation(),
            'image'=>$this->getFirstMedia('images') != null ? $this->getFirstMedia('images')->getUrl() : null,
            'commercial_register'=>$this->getFirstMedia('commercial_register') != null ? $this->getFirstMedia('commercial_register')->getUrl() : null,
            "provider_type" =>$this->provider_type,
            "services_from_home" =>$this->services_from_home,
            "address" => $this->address,
            "lat" => $this->lat,
            "long" =>$this->long ,
            'default_language'=>$this->default_language,
            'is_notification'=>$this->is_notification,
            'categories'=>CategoryResource::collection($this->categories),
            'country'=>new CountryResource($this->country),
            'city'=>new CityResource($this->city),
            'area'=>new AreaResource($this->area),
            'transporter'=>new TransporterResource($this->transporter),
        ];
    }
}
