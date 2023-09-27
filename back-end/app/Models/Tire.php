<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tire extends Model implements TranslatableContract
{
    use HasFactory, Translatable;

    public $translatedAttributes = ['title'];
    protected $guarded = [];
    protected $appends=['full__path_image'];
    public function scopeActive($query)
    {
        return $query->where('status',  1);
    }

    public function getFullPathImageAttribute(){
        return  $this->image != null ? asset('assets/images/'.$this->image) :  asset('assets/images/tires/default.png');
    }
}
