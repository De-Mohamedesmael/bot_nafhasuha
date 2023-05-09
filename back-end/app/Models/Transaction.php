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
}
