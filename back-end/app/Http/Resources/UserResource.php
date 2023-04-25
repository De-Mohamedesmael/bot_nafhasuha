<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'id'=>$this->id,
            'name'=>$this->name,
            'email'=>$this->email,
            'phone'=>$this->phone,
            'is_activation'=>$this->is_activation(),
            'image'=>$this->getFirstMedia('images') != null ? $this->getFirstMedia('images')->getUrl() : null,
            'address'=>$this->address,
            'lat'=>$this->lat,
            'lang'=>$this->lang,
            'country'=>new CountryResource($this->country),
            'city'=>new CityResource($this->city),
            'area'=>new AreaResource($this->area),


        ];
    }
}
