<?php

namespace App\Http\Resources\Providers;

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
        $if_in=in_array($this->type,['OrderService','Withdrawal']);

        $date=$if_in ? date('Y-m-d', strtotime($this->completed_at)):$this->created_at->format('Y-m-d');
        $time=$if_in ?  date('h:i A', strtotime($this->completed_at)):$this->created_at->format('h:i A');
        return [
            'id'=>$this->id,
            'invoice_no'=>$this->invoice_no,
            'type'=>$this->type,
            'type_id'=>$this->type_id,
            'title'=>$this->title_provider(),
            'final_total'=>(int)$this->final_total ,
            'date'=>  $date,
            'time'=>$time  ,
        ];
    }
}
