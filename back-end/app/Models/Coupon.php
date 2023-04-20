<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    public static $rules = [
        'name' => 'required|max:100',
        'code' => 'required|max:100',
        'is_active' => 'required|in:0,1',
        'discount' => 'required|numeric|min:1',
        'min_price' => 'required|numeric|min:0',
        'usage_limit' => 'nullable|numeric|min:1',
        'start_date' => 'nullable',
        'end_date' => 'nullable',
    ];

    protected $fillable = [
        'name',
        'code',
        'is_active',
        'discount',
        'min_price',
        'usage_limit',
        'usage_finished',
        'start_date',
        'end_date',
        'store_id',
    ];

    public function setStoreIdAttribute()
    {
        return $this->attributes['store_id'] = auth('store')->id();
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function users()
    {
        return $this->hasMany(CouponUser::class);
    }
}
