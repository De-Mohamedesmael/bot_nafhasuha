<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Provider extends Authenticatable implements JWTSubject, HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, InteractsWithMedia;

    protected $guarded = [];
    protected $appends=['is_rate'];
    public function setPasswordAttribute($value)
    {
        return $this->attributes['password'] = bcrypt($value);
    }
    public function scopeActive($query)
    {
        return $query->where('is_active',  1)->where('is_deleted', 0);
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
    public function getIsRateAttribute()
    {
        if (\auth()->check()) {
            return ProviderRate::where('provider_id',$this->id)
                ->where('user_id',\auth()->id())->exists();
        }
        return false;

    }
    public function city()
    {
        return $this->belongsTo(City::class);
    }
    public function area()
    {
        return $this->belongsTo(Area::class);
    }
    public function transporter()
    {
        return $this->belongsTo(Transporter::class);
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
    public function orders()
    {
        return $this->hasMany(OrderService::class,'provider_id');
    }
    public function getDistanceFromCoordinates($lat, $long)
    {
        $lat1 = deg2rad((float)$lat);
        $lat2 = deg2rad((float)$this->lat);
        $long1 = deg2rad((float)$long);
        $long2 = deg2rad((float)$this->long);

        return 111.045 * acos(cos($lat1) * cos($lat2) * cos($long2 - $long1) + sin($lat1) * sin($lat2));
    }
    public function notifications()
    {
        return $this->belongsToMany(Notification::class, 'provider_notifications');
    }
}
