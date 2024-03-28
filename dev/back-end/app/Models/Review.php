<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Review extends Model implements HasMedia
{
    use HasFactory ,InteractsWithMedia;
    protected $guarded = [];

    protected $appends=['full_path_image'];
    public function getFullPathImageAttribute(){
        return  $this->getFirstMedia('images') != null ? $this->getFirstMedia('images')->getUrl()  :  asset('assets/images/settings/logo.svg');
    }
}
