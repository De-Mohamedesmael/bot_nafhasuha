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
       $is_tracking= ($this->status=='pending'||$this->status=='approved' || $this->status=='PickUp')&& ($this->category_id==5||$this->category_id==8) ? 1:0;

       $first_children=$this->children()->wherein('status',['pending', 'PickUp','approved'])->first() ?:null;
       if($first_children){
           $is_tracking= $first_children->status == 'approved' || $first_children->status == 'PickUp';
       }
       return [
            'id'=>$this->id,
            'status'=>$this->status,
            'type'=>$this->type,
            'lat'=>$this->lat,
            'long'=>$this->long,
            'status_vehicle'=>$this->status_vehicle,
            'provider'=>new ProviderOrderServiceResource($this->provider_with_rate),
            'is_have_second_provider'=> (bool)$first_children,
            'second_status'=> $first_children ? $first_children->status : '',
            'second_provider'=>$first_children ? new ProviderOrderServiceResource($first_children->provider_with_rate) : '',
            'category'=>new CategoryResource($this->category),
            'invoice_no'=>$transaction->invoice_no??'',
            'price_requests_count' => $this->price_requests_count?:0,
            'price_type' => $this->price_type?:0,
            'is_tracking'=>$is_tracking,
            'canceled_type'=>$this->canceled_type,
            'reason'=>$this->cancel_reason? $this->cancel_reason->title:'',
            'canceled_by'=>new CanceledByResource($this->canceledby),
        ];
    }
}
