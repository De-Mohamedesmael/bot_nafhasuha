<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Slider extends Model implements  HasMedia
{
    use HasFactory,InteractsWithMedia;
    protected $guarded= [];
    public function scopeActive($query)
    {
        $now = date('Y-m-d');
        return $query->where('status',  1)->where(function ($q) use ($now){
            $q->where(function ($q2) use ($now){
                $q2->whereDate('start_at','<=',$now )
                        ->whereDate('end_at','>',$now );
            })->orwhereNull('end_at');
        });

    }
}
