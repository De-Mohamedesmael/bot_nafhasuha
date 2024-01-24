<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use function App\CPU\translate;
use Carbon\Carbon;

class MyInvoiceResource extends JsonResource
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
            $tax_number=\Settings::get('tax_number','5677894225677');
            $value_tax=\Settings::get('value_tax',15);
            $amount_tax = (int)($transaction->final_total *$value_tax /100);
            $createdAt = Carbon::parse($this->created_at);
            $qrcode=generate_qr_code($transaction->invoice_no);
        return [
            'id'=>$this->id,
            'type'=>$this->type,
            'type_from'=>$this->type_from,
            'type_from_value'=>$this->type_from == null ? null :translate($this->type_from),
            'address'=>$this->address,
            'date_at'=>$createdAt->format('j F Y'),

            'provider'=>new ProviderOrderServiceResource($this->provider_with_rate),
            'type_battery'=>$this->type =="ChangeBattery"? new TypeBatteryResource($transaction->type_battery) :null,
            'type_gasoline'=>$this->type =="Petrol"? $transaction->type_gasoline?->title :null,
            'service'=>new ServiceResource($this->service),
            'category'=>new CategoryResource($this->category),
            'sub_category'=>$sub_category,
            'transaction_id'=>$this->transaction_id,
            'invoice_no'=>$transaction->invoice_no??null,
//            'discount_amount'=>(int)$transaction->discount_amount??0,
//            'grand_total'=>$transaction->grand_total,
            'price_without_tax'=>(int)($transaction->final_total-$amount_tax),
            'value_tax'=>(int)$value_tax,
            'qrcode'=>$qrcode,
            'amount_tax'=>(int)$amount_tax,
            'final_total'=>(int)$transaction->final_total??0,
            'tax_number'=>$tax_number,
        ];
    }
}
