<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function scopeActive($query)
    {

        //type => 'TopUpCredit','OrderService','JoiningBonus','InvitationBonus'
        //status => 'pending','approved','received','declined','canceled'
        return $query->wherein('status',['received']);
    }
    public function title()
    {

        switch ($this->type){
            case 'OrderService':
                $service = $this->service;
                $text=$service?$service->title:'';
                break;
            case 'InvitationBonus':
                $type_model = $this->friend;
                $text=$type_model?$type_model->name:'';
                break;
            default:
                $text='';
                break;
        }
        return __('messages.des_transaction_'.$this->type,['amount'=>$this->final_total,'text'=>$text]);
    }
    public function title_provider()
    {

        switch ($this->type){
            case 'OrderService':
                $service = $this->service;
                $text=$service?$service->title:'';
                break;
            case 'InvitationBonus':
                $type_model = $this->friend;
                $text=$type_model?$type_model->name:'';
                break;
            default:
                $text='';
                break;
        }
        return __('messages.des_provider_transaction_'.$this->type,['amount'=>$this->final_total,'text'=>$text]);
    }




    public function transporter()
    {

        return $this->belongsTo(Transporter::class,'type_id','id');
    }
    public function tire()
    {

        return $this->belongsTo(Tire::class,'type_id','id');
    }
    public function type_battery()
    {

        return $this->belongsTo(TypeBattery::class,'type_id','id');
    }
    public function type_gasoline()
    {

        return $this->belongsTo(TypeGasoline::class,'type_id','id');
    }
    public function friend()
    {

        return $this->belongsTo(User::class,'type_id','id');
    }
    public function service()
    {

        return $this->belongsTo(Service::class);
    }
    public function provider()
    {

        return $this->belongsTo(Provider::class);
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


    public function createdBy()
    {



//        canceled_type	enum('Admin', 'User', 'Provider')
        if($this->created_by_type == 'Admin' )
            return $this->belongsTo(Admin::class,'created_by');

        if($this->created_by_type == 'Provider' )
            return $this->belongsTo(Provider::class,'created_by');

        return $this->belongsTo(User::class,'created_by');
    }

}
