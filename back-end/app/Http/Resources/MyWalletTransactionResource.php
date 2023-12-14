<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MyWalletTransactionResource extends JsonResource
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
            'invoice_no'=>$this->invoice_no,
            'type'=>$this->type,
            'type_id'=>$this->type_id,
            'title'=>$this->title(),
            'final_total'=>(int)$this->final_total ,
            'date'=>$this->created_at->format('Y-m-d')  ,
            'time'=>$this->created_at->format('h:i A')  ,
        ];
    }
}
