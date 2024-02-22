<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class UserVehicleSimpleResource extends JsonResource
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
            'first_image'=>$this->getFirstMedia('images') != null ? $this->getFirstMedia('images')->getUrl() : ($this->vehicle_brand != null ?($this->vehicle_brand->image != null ?asset('assets/images/'.$this->vehicle_brand->image): null): null),
        ];
    }
}
