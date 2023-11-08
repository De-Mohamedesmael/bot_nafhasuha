<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

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
            'product_module' => __('permission.product_module'),
            'raw_material_module' => __('permission.raw_material_module'),
            'customer_module' => __('permission.customer_module'),
            'supplier_module' => __('permission.supplier_module'),
            'service_provider' => __('permission.service_provider'),
            'hr_management' => __('permission.hr_management'),
            'purchase_order' => __('permission.purchase_order'),
            'sale' => __('permission.sale'),
            'return' => __('permission.return'),
            'expense' => __('permission.expense'),
            'stock' => __('permission.stock'),
            'cash' => __('permission.cash'),
            'adjustment' => __('permission.adjustment'),
            'reports' => __('permission.reports'),
            'quotation_for_customers' => __('permission.quotation_for_customers'),
            'coupons_and_gift_cards' => __('permission.coupons_and_gift_cards'),
            'loyalty_points' => __('permission.loyalty_points'),
            'sp_module' => __('permission.sp_module'),
            'notification_module' => __('permission.notification_module'),
            'sms_module' => __('permission.sms_module'),
            'email_module' => __('permission.email_module'),
            'settings' => __('permission.settings'),
        ];
    }
    public static function subModulePermissionArray()
    {
        return [
            'dashboard' => [
                'profit' => __('permission.sales_and_returns'),
                // 'details' => __('permission.details'),
            ],
            'hr_management' => [
                'employee' => __('permission.employee'),
                'employee_commission' => __('permission.employee_commission'),
                'suspend' => __('permission.suspend'),
            ],
            'notification_module' => [
                'notification' => __('permission.notification'),
                'setting' => __('permission.setting'),
            ],
            'sms_module' => [
                'sms' => __('permission.sms'),
                'setting' => __('permission.setting'),
            ],

            'email_module' => [
                'email' => __('permission.email'),
                'setting' => __('permission.setting'),
            ],
            'settings' => [

                'general_settings' => __('permission.general_settings'),
            ],

        ];
    }
}
