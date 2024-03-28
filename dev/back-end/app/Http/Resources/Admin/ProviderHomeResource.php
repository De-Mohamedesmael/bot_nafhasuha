<?php

namespace App\Http\Resources\Admin;

use App\Http\Resources\AreaResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CityResource;
use App\Http\Resources\CountryResource;
use App\Models\Transaction;
use Illuminate\Http\Resources\Json\JsonResource;
use function App\CPU\translate;

class ProviderHomeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $html=' <div id="rate" class="rate">';
        $rate=(int)$this->totalRate;
        for($i=0; $i<=5; $i++){
            $calss= $i <= $rate ? 'star-mark':'';
            $html.='<div data-score="'.$i.'" class="star '.$calss.' "></div>';
        }
        $html.='</div>';
        $amountCredit = Transaction::Active()
            ->whereIn('type', ['OrderService','TopUpCredit'])
            ->where('provider_id', $this->id)
            ->sum('final_total');

        $amountDebit = Transaction::Active()
            ->whereIn('type', ['Withdrawal'])
            ->where('provider_id', $this->id)
            ->sum('final_total');
        $wallet=$amountCredit - $amountDebit;
        return [
            "id" => $this->id,
            "name" => $this->name,
            "lat" =>$this->lat ,
            "long" =>$this->long ,
            'image_url'=>$this->getFirstMedia('images') != null ? $this->getFirstMedia('images')->getUrl() : null,
            "pending" =>$this->orders->first()?($this->orders->first()->transaction? $this->orders->first()->transaction->invoice_no :translate('nothing')):translate('nothing') ,
            "rate"=> $html,
            "count_order"=> (int)$this->orders_count,
            "wallet"=> (int)$wallet,

        ];
    }
}
