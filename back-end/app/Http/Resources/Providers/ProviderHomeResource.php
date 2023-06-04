<?php

namespace App\Http\Resources\Providers;

use App\Http\Resources\AreaResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CityResource;
use App\Http\Resources\CountryResource;
use Illuminate\Http\Resources\Json\JsonResource;

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
        return [
            "id" => $this->id,
            "name" => $this->name,
            'image'=>$this->getFirstMedia('images') != null ? $this->getFirstMedia('images')->getUrl() : null,
            "avg_rate" =>number_format($this->totalRate, 2, '.', '') ,
            "rates_count" => $this->rates_count,
            "count_orders_completed"=> $this->orders_count

        ];
    }
}
