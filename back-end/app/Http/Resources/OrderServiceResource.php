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
        return [
            'id'=>$this->id,
            'status'=>$this->status,
            'type'=>$this->type,
            'type_from'=>$this->type_from,
            'type_from_value'=>$this->type_from == null ? null :translate($this->type_from),
            'position'=>$this->position,
            'date_at'=>$this->date_at,
            'time_at' => $this->time_at == null ? null :  Carbon::createFromFormat('H:i', $this->time_at)->format('h:i A'),
            'address'=>$this->address,
            'lat'=>$this->lat,
            'long'=>$this->long,
            'address_to'=>$this->address_to,
            'lat_to'=>$this->lat_to,
            'long_to'=>$this->long_to,
            'details'=>$this->details,
            'provider'=>new ProviderOrderServiceResource($this->provider_with_rate),
            'service'=>new ServiceResource($this->service),
            'category'=>new CategoryResource($this->category),
            'transaction_id'=>$this->transaction_id,
            'invoice_no'=>$transaction->invoice_no??null,
            'discount_amount'=>$transaction->discount_amount??0,
            'grand_total'=>$transaction->grand_total??0,
            'final_total'=>$transaction->final_total??0,
            'canceled_type'=>$this->canceled_type,
            'reason'=>$this->reason,
            'canceled_by'=>new CanceledByResource($this->canceledBy),
        ];
    }
}
