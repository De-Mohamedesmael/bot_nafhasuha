<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceRequest extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }
    public function order()
    {
        return $this->belongsTo(OrderService::class,'order_service_id');
    }
}
