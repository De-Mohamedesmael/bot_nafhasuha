<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
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
            'body'=>$this->body,
            'type'=>$this->type,
            'type_id'=>$this->type_id,
            'order_step'=>$this->order_step?:'',
            'image'=>$this->image != null ? asset('assets/images/'.$this->image) : null,
            'is_show'=>$this->users_pov->first()?->is_show

        ];
    }
}
