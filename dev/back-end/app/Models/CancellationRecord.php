<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CancellationRecord extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function cancel_reason()
    {
        return $this->belongsTo(CancelReason::class,'cancel_reason_id');
    }
}
