<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model implements TranslatableContract
{
    use HasFactory, Translatable;
    public $translatedAttributes = ['title', 'body'];
    protected $guarded=[];


    public function users()
    {
        return $this->belongsToMany(User::class, 'user_notifications');
    }
    public function users_pov()
    {
        return $this->hasMany(UserNotification::class);
    }
    public function providers()
    {
        return $this->belongsToMany(Provider::class, 'provider_notifications');
    }
    public function providers_pov()
    {
        return $this->hasMany(ProviderNotification::class);
    }

}
