<?php

namespace App\Http\Resources\Providers;

use App\Http\Resources\CanceledByResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProviderOrderServiceResource;
use App\Http\Resources\ServiceResource;
use App\Http\Resources\TransporterResource;
use App\Http\Resources\UserResource;
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
            'payment_type'=>'Cash',
            "distance" => number_format($this->distance, 2, '.', ''),//round(, 2),
            'user'=>new UserResource($this->user),
            'provider'=>new ProviderResource($this->provider),
            'service'=>new ServiceResource($this->service),
            'category'=>new CategoryResource($this->category),
            'vehicle_transporter'=>new TransporterResource($transaction->transporter),
            'images'=>$this->getMedia('images')->map(function ($item) {
                return $item->getUrl();
            })->all(),
            'videos'=>$this->getMedia('videos')->map(function ($item) {
                return $item->getUrl();
            })->all(),
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
