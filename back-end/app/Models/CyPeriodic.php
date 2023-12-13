<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CyPeriodic extends Model implements TranslatableContract
{
    use HasFactory,  Translatable;

    public $translatedAttributes = ['title'];
    protected $guarded=[];

    public function scopeActive($query)
    {
        return $query->where('status',  1);
    }
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function providers()
    {
        return $this->belongsToMany(Provider::class,'cy_periodic_providers');
    }
}
