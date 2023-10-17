<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use function App\CPU\translate;
use Carbon\Carbon;

class OrderServiceAllDataResource extends JsonResource
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
        $sub_category='';
        if($transaction){
            switch ($this->type){
                case 'Tire':
                    $sub_category=$transaction->tire?->title;
                    break;


            }

        }
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
            'images'=> $this->getMedia('images')->map(function ($item) {
                return [
                    'id' => $item->id,
                    'url' => $item->getUrl(),
                ];
            }),
//            'videos'=> $this->getMedia('videos')->map(function ($item) {
//                return [
//                    'id' => $item->id,
//                    'url' => $item->getUrl(),
//                ];
//            }),
            'user'=>new UserResource($this->user),
            'user_vehicle'=>new UserVehicleResource($this->vehicle),
            'provider'=>new ProviderOrderServiceResource($this->provider_with_rate),
            'vehicle_transporter'=>new TransporterResource($transaction->transporter),
            'type_battery'=>$this->type =="ChangeBattery"? new TypeBatteryResource($transaction->type_battery) :null,
            'type_gasoline'=>$this->type =="Petrol"? $transaction->type_gasoline?->title :null,
            'service'=>new ServiceResource($this->service),
            'category'=>new CategoryResource($this->category),
            'sub_category'=>$sub_category,
            'transaction_id'=>$this->transaction_id,
            'invoice_no'=>$transaction->invoice_no??null,
            'suggested_price'=>$transaction->suggested_price,
            'discount_amount'=>$transaction->discount_amount??0,
            'grand_total'=>$transaction->grand_total??0,
            'final_total'=>$transaction->final_total??0,
            'city'=>new CityResource($this->city),
            'canceled_type'=>$this->canceled_type,
            'reason'=>$this->reason,
            'canceled_by'=>new CanceledByResource($this->canceledby),
        ];
    }
}
