<?php

namespace App\Providers;

use App\Models\Assay;
use App\Models\Category;
use App\Models\Company;
use App\Models\Department;
use App\Models\Equipment;
use App\Models\Item;
use App\Models\Manufacture;
use App\Models\OrderService;
use App\Models\PackageType;
use App\Models\Procedure;
use App\Models\PurchaseOrder;
use App\Models\Section;
use App\Models\Store;
use App\Models\Supplier;
use App\Models\City2;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer([
            'back-end.layouts.partials.sidebar',
        ], function ($view) {
            $side_counts_orders=OrderService::getStatusSummary()->pluck('count', 'status')->toArray();
            $view->with([
                'side_counts_orders' => $side_counts_orders,
            ]);
        });

    }
}
