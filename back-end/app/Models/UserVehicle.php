<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class UserVehicle extends Model implements HasMedia
{
    use HasFactory,InteractsWithMedia;
    protected $guarded= ['id'];
    public function scopeActive($query)
    {
        return $query->where('status',1);
    }
    public function vehicle_type()
    {
        return $this->belongsTo(VehicleType::class);
    }

    public function vehicle_model()
    {
        return $this->belongsTo(VehicleModel::class);
    }
    public function vehicle_manufacture_year()
    {
        return $this->belongsTo(VehicleManufactureYear::class);
    }
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

}
