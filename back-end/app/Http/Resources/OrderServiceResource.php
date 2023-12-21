<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use function App\CPU\translate;
use Carbon\Carbon;

class OrderServiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $transaction = $this->transaction;
       $is_tracking= ($this->status=='pending'||$this->status=='approved')&& ($this->category_id==5||$this->category_id==8) ? 1:0;
        return [
            'id'=>$this->id,
            'status'=>$this->status,
            'type'=>$this->type,
            'lat'=>$this->lat,
            'long'=>$this->long,
            'provider'=>new ProviderOrderServiceResource($this->provider_with_rate),
            'category'=>new CategoryResource($this->category),
            'invoice_no'=>$transaction->invoice_no??null,
            'price_requests_count' => $this->price_requests_count?:0,
            'price_type' => $this->price_type?:0,
            'is_tracking'=>$is_tracking,
            'canceled_type'=>$this->canceled_type,
            'reason'=>$this->cancel_reason? $this->cancel_reason->title:'',
            'canceled_by'=>new CanceledByResource($this->canceledby),
        ];
    }
}
