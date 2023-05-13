<?php

namespace App\Http\Resources;

use Carbon\Carbon;
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
        $currentDate = Carbon::now();
        $oneYearFromNow = $currentDate->addYear()->format('Y');
        if($this->periodic_inspection < 10){
            $text_periodic='-'.'0'.$this->periodic_inspection.'-01';
        }else{
            $text_periodic='-'.$this->periodic_inspection.'-01';
        }
        return [
            'id'=>$this->id,
            'letters_ar'=>$this->letters_ar,
            'letters_en'=>$this->letters_en,
            'numbers_ar'=>$this->numbers_ar,
            'numbers_en'=>$this->numbers_en,
            'periodic_inspection'=>date('m') > $this->periodic_inspection || (date('m') == $this->periodic_inspection && date('d') > 1 )  ? $oneYearFromNow.$text_periodic : date('Y').$text_periodic,
            'status'=>$this->status,
            'first_image'=>$this->getFirstMedia('images') != null ? $this->getFirstMedia('images')->getUrl() : null,
            'vehicle_type'=>new VehicleTypeResource($this->vehicle_type),
            'vehicle_brand'=>new VehicleBrandResource($this->vehicle_brand),
            'vehicle_model'=>new VehicleModelResource($this->vehicle_model),
            'vehicle_manufacture_year'=>new VehicleManufactureYearResource($this->vehicle_manufacture_year),
            'images'=> $this->getMedia('images')->map(function ($item) {
                return [
                    'id' => $item->id,
                    'url' => $item->getUrl(),
                ];
            }),


        ];
    }
}
