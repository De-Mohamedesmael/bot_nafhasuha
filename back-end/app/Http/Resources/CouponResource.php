<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
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
            'description'=>$this->description,
            'code'=>$this->code,
            'type_discount'=>$this->type_discount,
            'discount'=>(float)$this->discount,
            'min_price'=>(float)$this->min_price,
            'start_date'=>$this->start_date,
            'end_date'=>$this->end_date,
        ];
    }
}
