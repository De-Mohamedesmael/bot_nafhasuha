<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable implements  HasMedia
{
    use HasFactory, Notifiable, HasRoles, HasPermissions, HasApiTokens,InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean'
    ];


    public function scopeNotview($query)
    {
        return $query->where('email', '!=', env( 'SYSTEM_SUPERADMIN','superadmin@nafhasuha.com'))
            ->where('is_active',true);
    }


    public static function modulePermissionArray()
    {
        return [
            'dashboard' => __('permission.dashboard'),
            'order_module' => __('permission.order_module'),
            'transactions_module' => __('permission.transactions_module'),
            'customer_module' => __('permission.customer_module'),
            'provider_module' => __('permission.provider_module'),
            'notification_module' => __('permission.notification_module'),
            'system_settings' => __('permission.system_settings'),
            'settings' => __('permission.settings'),
            'messages' => __('permission.messages'),
            'admin_module' => __('permission.admin_module'),
            'info_module' => __('permission.info_module'),
            'reports' => __('lang.reports'),
        ];
    }
    public static function subModulePermissionArray()
    {
        return [
            'dashboard' => [
                 'details' => __('permission.details'),
            ],
            'order_module' => [
                'order' => __('permission.order'),
                'send_offer' => __('permission.send_offer'),
                'accept_order' => __('permission.accept_order'),

            ],
            'transactions_module' => [
                'transactions' => __('permission.transactions'),
                'accept_transaction' => __('permission.accept_transaction'),

                //accept_transaction
            ],
            'customer_module' => [
                'customer' => __('permission.customer'),
            ],
            'provider_module' => [
                'provider' => __('permission.provider'),
            ],
            'notification_module' => [
                'notification' => __('permission.notification'),
            ],
            'system_settings' => [
                'transporters' => __('permission.transporters'),
                'cy_periodics' => __('permission.cy_periodics'),
                'tires' => __('permission.tires'),
                'type_batteries' => __('permission.type_batteries'),
                'type_gasolines' => __('permission.type_gasolines'),
                'vehicle_brands' => __('permission.vehicle_brands'),
                'vehicle_models' => __('permission.vehicle_models'),
                'vehicle_types' => __('permission.vehicle_types'),
                'vehicle_manufacture_years' => __('permission.vehicle_manufacture_years')
            ],
            'settings' => [
                'service' => __('permission.service'),
                'banks' => __('permission.banks'),
                'icons' => __('permission.icons'),
                'category' => __('permission.category'),
                'slider' => __('permission.slider'),
                'splash_screen' => __('permission.splash_screen'),
                'countries' => __('permission.countries'),
                'city' => __('permission.city'),
                'areas' => __('permission.areas'),
                'general_settings' => __('permission.general_settings'),
            ],
            'messages' => [
                'contact_us' => __('permission.contact_us'),
            ],
            'admin_module' => [
                'admins' => __('permission.admins'),
            ],
            'info_module' => [
                'infos' => __('permission.infos'),
                'category_faqs' => __('permission.category_faqs'),
                'faqs' => __('permission.faqs'),
            ],
            'reports' => [
                'daily_report' => __('lang.daily_report'),
                'monthly_report' => __('lang.monthly_report'),
                'yearly_report' => __('lang.yearly_report'),
//                'best_report' => __('lang.best_report'),

            ],

        ];
    }


    public static function specialModulePermissionArray()
    {
        return [
            'dashboard',
            'accept_order' ,
            'send_offer' ,
            'transaction' ,
            'accept_transaction' ,
            'infos' ,
            'daily_report' ,
            'monthly_report' ,
            'yearly_report' ,
//            'best_report' ,

        ];
    }
}
