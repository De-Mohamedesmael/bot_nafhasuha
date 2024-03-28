<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    public static $rules = [
        'name' => 'required|max:225',
        'email' => 'required|max:225|email|unique:admins',
        'image' => 'nullable|image',
        'password' => 'required|min:6|max:225',
    ];

    public $path = 'assets/images/admins/';

    protected $fillable = [
        'name',
        'email',
        'image',
        'type',
        'password',
    ];

    /*protected $hidden = [
        'password',
    ];*/

    public function setPasswordAttribute($value)
    {
        if ($value) return $this->attributes['password'] = bcrypt($value);
    }

    function setImageAttribute($value)
    {
        if ($value) return $this->attributes['image'] = uploadFile($value, $this->path);
    }

    public function getImageAttribute($value)
    {
        if ($value) return asset($this->path . $value);
        return asset('assets/back/images/layout_img/user_img.jpg');
    }
}
