<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageUser extends Model
{
    use HasFactory;
    public $timestamps=false;
    protected $guarded=[];
    public function scopeActive($query)
    {
        $now = date('Y-m-d');
        return $query->whereDate('start_at','<=',$now )
            ->whereDate('end_at','>',$now );
    }
    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
