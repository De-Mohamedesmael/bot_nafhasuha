<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CouponUser extends Model
{
    protected $table="coupon_user";
    protected $fillable = [
        'user_id',
        'coupon_id',
    ];
}
