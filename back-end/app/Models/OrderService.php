<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class OrderService extends Model implements HasMedia
{
    use HasFactory,InteractsWithMedia;
    protected $guarded= [];


    protected $casts = [
        'position' => 'json',//['Left','Right','Front','Behind']
    ];

    public function scopeNotCompleted($query)
    {
        return $query->wherein('status',  ['pending', 'approved','received']);
    }

    public function scopeCompleted($query)
    {
        return $query->wherein('status',  ['completed','declined','canceled']);
    }
    public function scopeCanceld($query)
    {

        return $query->wherein('status', ['declined','canceled'] );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function price_requests()
    {
        return $this->hasMany(PriceRequest::class);
    }
    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }
    public function provider_with_rate()
    {
        return $this->belongsTo(Provider::class,'provider_id')->withAvg('rates as totalRate', 'rate')
            ->withCount('rates');
    }
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function vehicle()
    {
        return $this->belongsTo(UserVehicle::class,'vehicle_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }
    public function transaction()
    {
        return $this->belongsTo(Transaction::class,'transaction_id');
    }
    public function maintenance_report()
    {
        return $this->belongsTo(MaintenanceReport::class,'id','order_service_id');
    }
    public function children()
    {
        return $this->hasMany(self::class,'parent_id');
    }
    public function cancel_reason()
    {
        return $this->belongsTo(CancelReason::class,'cancel_reason_id');
    }
    public function canceledBy()
    {



//        canceled_type	enum('Admin', 'User', 'Provider')
        if($this->canceled_type == 'Admin' )
            return $this->belongsTo(Admin::class,'canceled_by');

        if($this->canceled_type == 'Provider' )
            return $this->belongsTo(Provider::class,'canceled_by');

        return $this->belongsTo(User::class,'canceled_by');
    }
    public static function getStatusSummary()
    {
        return self::groupBy('status')
            ->selectRaw('status, count(*) as count');
    }

    public function isOfferPrice()
    {
        $not_request_price=['PeriodicInspection'];
        //Tire&TransportVehicle&ChangeBattery&Petrol&SubscriptionBattery
        $is_offer_price=true;
        if(in_array($this->type,$not_request_price)){
            $is_offer_price=false;
        }
        return$is_offer_price;
    }
}
