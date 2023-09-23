<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model implements TranslatableContract
{
    use HasFactory,  Translatable;

    public $translatedAttributes = ['title', 'description'];
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
        'limit',
        'limit_user',
        'use',
        'start_date',
        'end_date',
    ];


    public function setStoreIdAttribute()
    {
        return $this->attributes['store_id'] = auth('store')->id();
    }
    public function scopeActive($query)
    {
        return $query->where('is_active',  1);
    }



    public function users()
    {
        return $this->belongsToMany(User::class,'coupon_user');
    }

    public function coupon_users()
    {
        return $this->hasMany(CouponUser::class);
    }
    public function services()
    {
        return $this->belongsToMany(Service::class,'coupon_services');
    }
}
