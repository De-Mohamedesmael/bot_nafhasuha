<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Provider extends Authenticatable implements JWTSubject, HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, InteractsWithMedia;

    protected $guarded = [];
    public function setPasswordAttribute($value)
    {
        return $this->attributes['password'] = bcrypt($value);
    }
    public function scopeActive($query)
    {
        return $query->where('is_active',  1);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }


    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function is_activation()
    {
        return $this->activation_at != null;
    }
    public function city()
    {
        return $this->belongsTo(City::class);
    }
    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function rates()
    {
        return $this->hasMany(ProviderRate::class);
    }
    public function categories()
    {
        return $this->belongsToMany(Category::class,'provider_categories');
    }
    public function getType()
    {
        if($this->categories->count() > 1){
            return 'ProviderCenter';
        }
        return 'Provider';
    }


}
