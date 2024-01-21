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
            'image'=>$this->getFirstMedia('images') != null ? $this->getFirstMedia('images')->getUrl() : null,
            'type_discount'=>$this->type_discount,
            'discount'=>(float)$this->discount,
            'is_multi_use'=>$this->is_multi_use,
            'type'=>$this->type,
            'type_id'=>$this->type_id,
            'title_type'=>$this->type_model? $this->type_model->title:'',

        ];
    }
}
