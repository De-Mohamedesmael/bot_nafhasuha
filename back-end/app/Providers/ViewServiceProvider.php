<?php

namespace App\Providers;

use App\Models\Assay;
use App\Models\Category;
use App\Models\Company;
use App\Models\Department;
use App\Models\Equipment;
use App\Models\Item;
use App\Models\Manufacture;
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
            'store.view.options.fields',
            'store.view.products.fields',
            'store.view.attributes.fields',
        ], function ($view) {
            $view->with([
                'sections' => Section::where('store_id', auth('store')->id())->pluck('name', 'id'),
            ]);
        });
        view()->composer([
            'booth.view.attributes.fields',
            'booth.view.products.fields',
            'booth.view.options.fields',
        ], function ($view) {
            $view->with([
                'sections' => Section::where('store_id', auth('booth')->id())->pluck('name', 'id'),
            ]);
        });
        view()->composer('dashboard.view.categories.fields', function ($view){
            $view->with([
                'categories' => Category::where('id', '<=', 13)->pluck('name', 'id'),
            ]);
        });

        view()->composer(['dashboard.view.stores.fields', 'dashboard.view.booths.fields'], function ($view){
            $view->with([
                'categories' => Category::pluck('name', 'id'),
            ]);
        });

        view()->composer('dashboard.view.orders.index', function ($view){
            $view->with([
                'stores' => Store::all()->pluck('name', 'id'),
            ]);
        });

        view()->composer('dashboard.view.coins.fields', function ($view){
            $view->with([
                'users' => User::latest('total_orders_cost')
                    ->select(DB::raw('CONCAT(name," ( ",coins," ) ") as name'), 'id')
                ->pluck('name', 'id'),
            ]);
        });

        view()->composer(['dashboard.view.giftBoxes.fields', 'dashboard.view.dailyAds.fields'], function ($view){
            $view->with([
                'halls' => [1=>1, 2=>2, 3=>3, 4=>4, 5=>5, 6=>6, 7=>7, 8=>8, 9=>9, 10=>10],
                'floors' => [0 => trans('dashboard.all'), 1=>1, 2=>2, 3=>3],
                'stores' => Store::active()->get()->pluck('name', 'id'),
            ]);
        });

        view()->composer([
            'booth.view.areas.fields',
            'store.view.areas.fields',
        ], function ($view) {
            $view->with([


                'cities' => City2::where('store_id', auth('store')->id())->pluck('name_def as name', 'id'),
            ]);
        });
    }
}
