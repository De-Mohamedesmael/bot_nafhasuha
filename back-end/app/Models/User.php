<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject, HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, InteractsWithMedia;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function setPasswordAttribute($value)
    {
        return $this->attributes['password'] = bcrypt($value);
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
    public function notifications()
    {
        return $this->belongsToMany(Notification::class, 'user_notifications');
    }
    public function vehicles()
    {
        return $this->hasMany(UserVehicle::class);
    }
    public function generateInviteCode()
    {
        $inviteCode = substr(md5(uniqid()), 0, 8);
        $this->invite_code = $inviteCode;
        $this->save();
        return $inviteCode;
    }



    public function InviteBy()
    {
        return $this->belongsTo(self::class, 'invite_by');

    }

}
