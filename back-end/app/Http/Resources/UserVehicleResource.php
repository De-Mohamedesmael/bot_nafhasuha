<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserVehicleResource extends JsonResource
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
            'title'=>$this->title,
            'letters_ar'=>$this->letters_ar,
            'letters_en'=>$this->letters_en,
            'numbers_ar'=>$this->numbers_ar,
            'numbers_en'=>$this->numbers_en,
            'periodic_inspection'=>$this->periodic_inspection,
            'description'=>$this->description,
            'status'=>$this->status,
            'first_image'=>$this->getFirstMedia('images') != null ? $this->getFirstMedia('images')->getUrl() : null,
            'vehicle_type'=>new VehicleTypeResource($this->vehicle_type),
            'vehicle_model'=>new VehicleModelResource($this->vehicle_model),
            'vehicle_manufacture_year'=>new VehicleManufactureYearResource($this->vehicle_manufacture_year),
            'vehicle_default'=>new VehicleDefaultResource($this->vehicle),
            'images'=> $this->getMedia('images')->map(function ($item) {
                return [
                    'id' => $item->id,
                    'url' => $item->getUrl(),
                ];
            }),


        ];
    }
}
