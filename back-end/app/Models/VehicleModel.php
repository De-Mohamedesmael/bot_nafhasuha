<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleModel extends Model  implements TranslatableContract
{
    use HasFactory, Translatable;
    public $translatedAttributes = ['title'];

    protected $guarded= [];
    public function scopeActive($query)
    {
        return $query->where('status',  1);
    }
    public function vehicle_type()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
