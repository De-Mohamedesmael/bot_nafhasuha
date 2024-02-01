<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BackEnd\Auth\LoginController;
use App\Http\Controllers\BackEnd\HomeController;
use App\Http\Controllers\BackEnd\CustomerController;
use App\Http\Controllers\BackEnd\ProviderController;
use App\Http\Controllers\BackEnd\OrderController;

use App\Http\Controllers\BackEnd\TransactionController;

use App\Http\Controllers\BackEnd\TransactionPaymentController;
use App\Http\Controllers\BackEnd\CategoryController;
use App\Http\Controllers\BackEnd\ServiceController;

use App\Http\Controllers\BackEnd\SliderController;
use App\Http\Controllers\BackEnd\SplashScreenController;

use App\Http\Controllers\BackEnd\NotificationController;
use App\Http\Controllers\BackEnd\IconController;
use App\Http\Controllers\BackEnd\BankController;

use App\Http\Controllers\BackEnd\CountryController;
use App\Http\Controllers\BackEnd\CityController;
use App\Http\Controllers\BackEnd\AreaController;
use App\Http\Controllers\BackEnd\VehicleTypeController;
use App\Http\Controllers\BackEnd\VehicleManufactureYearController;
use App\Http\Controllers\BackEnd\VehicleModelController;
use App\Http\Controllers\BackEnd\VehicleBrandController;

use App\Http\Controllers\BackEnd\TypeGasolineController;
use App\Http\Controllers\BackEnd\TypeBatteryController;
use App\Http\Controllers\BackEnd\TireController;
use App\Http\Controllers\BackEnd\CyPeriodicController;
use App\Http\Controllers\BackEnd\TransporterController;
use App\Http\Controllers\BackEnd\ContactUsController;
use App\Http\Controllers\BackEnd\SmsController;

use App\Http\Controllers\BackEnd\ReportController;

use App\Http\Controllers\BackEnd\InfoController;

use App\Http\Controllers\BackEnd\CategoryFaqController;
use App\Http\Controllers\BackEnd\FaqController;

use App\Http\Controllers\BackEnd\SettingController;


use App\Http\Controllers\BackEnd\GeneralController;

use App\Http\Controllers\BackEnd\AdminController;

/**
 * as & prefix => admin
 */

Route::get('/', function () {
    if (Auth::guard('admin')->user()) {
        return redirect('/home');
    } else {
        return redirect('/login');
    }
});
Route::group(['middleware' => ['language','admin.guest']], function () {
    // Authentication Routes...
    Route::get('login', [LoginController::class,'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class,'login']);





// Password Reset Routes...
    Route::get('password/reset', 'BackEnd\Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('password/email', 'BackEnd\Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::get('password/reset/{token}', 'BackEnd\Auth\ResetPasswordController@showResetForm');
    Route::post('password/reset', 'BackEnd\Auth\ResetPasswordController@reset');
});
Route::get('general/switch-language/{lang}', [GeneralController::class ,'switchLanguage'])->name('switchLanguage');

Route::group(['middleware' => ['auth:admin', 'SetSessionData', 'language', 'timezone']], function () {

    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/get-chart-data', [HomeController::class, 'getChartData'])->name('getChartData');
    Route::get('/get-counter-data', [HomeController::class, 'getCounterData'])->name('getCounterData');

    Route::group(['prefix'=>'customer','as'=>'customer.'], function () {
        Route::get('/', [CustomerController::class,'index'])->name('index');
        Route::post('/', [CustomerController::class,'index'])->name('index');
        Route::get('create', [CustomerController::class,'create'])->name('create');
        Route::post('create', [CustomerController::class,'store'])->name('store');
        Route::get('edit/{id}',[CustomerController::class, 'edit'])->name('edit');
        Route::put('update/{id}', [CustomerController::class,'update'])->name('update');
        Route::delete('delete/{id}', [CustomerController::class,'destroy'])->name('delete');
        Route::get('get-pay/{customer_id}', [CustomerController::class,'getPay'])->name('pay');
        Route::post('get-pay/{customer_id}', [CustomerController::class,'postPay'])->name('postPay');
        Route::post('update_status', [CustomerController::class,'update_status'])->name('update_status');
        Route::post('delete-image', [CustomerController::class,'deleteImage'])->name('deleteImage');
    });

    Route::group(['prefix'=>'provider','as'=>'provider.'], function () {
        Route::get('/', [ProviderController::class,'index'])->name('index');
        Route::post('/', [ProviderController::class,'index'])->name('index');
        Route::get('create', [ProviderController::class,'create'])->name('create');
        Route::post('create', [ProviderController::class,'store'])->name('store');
        Route::get('edit/{id}',[ProviderController::class, 'edit'])->name('edit');
        Route::put('update/{id}', [ProviderController::class,'update'])->name('update');
        Route::delete('delete/{id}', [ProviderController::class,'destroy'])->name('delete');
        Route::get('get-pay/{customer_id}', [ProviderController::class,'getPay'])->name('pay');
        Route::post('get-pay/{customer_id}', [ProviderController::class,'postPay'])->name('postPay');
        Route::post('update_status', [ProviderController::class,'update_status'])->name('update_status');
        Route::post('delete-image', [ProviderController::class,'deleteImage'])->name('deleteImage');
        Route::get('get_wallet', [ProviderController::class,'getWallet'])->name('get_wallet');
        Route::get('view_rate/{provider_id}', [ProviderController::class,'viewRate'])->name('view_rate');
        Route::delete('rate_delete/{rate_id}', [ProviderController::class,'rateDelete'])->name('rate_delete');
    });

    Route::group(['prefix'=>'orders','as'=>'order.'], function () {
        Route::get('/canceled-provider', [OrderController::class,'indexCanceledProvider'])->name('canceled-provider');
        Route::get('/{status?}', [OrderController::class,'index'])->name('index');
        Route::post('/{status?}', [OrderController::class,'index'])->name('index');
        Route::get('create', [OrderController::class,'create'])->name('create');
        Route::post('create', [OrderController::class,'store'])->name('store');
        Route::get('edit/{id}',[OrderController::class, 'edit'])->name('edit');
        Route::put('update/{id}', [OrderController::class,'update'])->name('update');
        Route::delete('delete/{id}', [OrderController::class,'destroy'])->name('delete');
        Route::get('send-offer/{order_id}', [OrderController::class,'getSendOffer'])->name('get-send-offer');
        Route::post('send-offer/{order_id}', [OrderController::class,'SendOffer'])->name('send-offer');
        Route::post('update_status', [OrderController::class,'update_status'])->name('update_status');
        Route::post('delete-image', [OrderController::class,'deleteImage'])->name('deleteImage');
    });
    Route::group(['prefix'=>'transactions','as'=>'transaction.'], function () {
        Route::get('create', [TransactionController::class,'create'])->name('create');
        Route::post('create', [TransactionController::class,'store'])->name('store');
        Route::get('accept/{id}', [TransactionController::class,'accept'])->name('accept');
        Route::delete('delete/{id}', [TransactionController::class,'destroy'])->name('delete');
        Route::get('/{type?}', [TransactionController::class,'index'])->name('index');
        Route::post('/{type?}', [TransactionController::class,'index'])->name('index');

    });




    Route::group(['prefix'=>'service','as'=>'service.'], function () {
        Route::get('/', [ServiceController::class,'index'])->name('index');
        Route::get('create', [ServiceController::class,'create'])->name('create');
        Route::post('create', [ServiceController::class,'store'])->name('store');
        Route::get('edit/{id}',[ServiceController::class, 'edit'])->name('edit');
        Route::put('update/{id}', [ServiceController::class,'update'])->name('update');
        Route::delete('delete/{id}', [ServiceController::class,'destroy'])->name('delete');
        Route::post('update_status', [ServiceController::class,'update_status'])->name('update_status');
    });
    Route::group(['prefix'=>'category','as'=>'category.'], function () {
        Route::get('/', [CategoryController::class,'index'])->name('index');
        Route::get('create', [CategoryController::class,'create'])->name('create');
        Route::post('create', [CategoryController::class,'store'])->name('store');
        Route::get('edit/{id}',[CategoryController::class, 'edit'])->name('edit');
        Route::put('update/{id}', [CategoryController::class,'update'])->name('update');
        Route::delete('delete/{id}', [CategoryController::class,'destroy'])->name('delete');
        Route::post('update_status', [CategoryController::class,'update_status'])->name('update_status');
    });

    Route::group(['prefix'=>'icons','as'=>'icons.'], function () {
        Route::get('/', [IconController::class,'index'])->name('index');
        Route::get('create', [IconController::class,'create'])->name('create');
        Route::post('create', [IconController::class,'store'])->name('store');
        Route::get('edit/{id}',[IconController::class, 'edit'])->name('edit');
        Route::put('update/{id}', [IconController::class,'update'])->name('update');
        Route::delete('delete/{id}', [IconController::class,'destroy'])->name('delete');

    });
    Route::group(['prefix'=>'banks','as'=>'banks.'], function () {
        Route::get('/', [BankController::class,'index'])->name('index');
        Route::get('create', [BankController::class,'create'])->name('create');
        Route::post('create', [BankController::class,'store'])->name('store');
        Route::get('edit/{id}',[BankController::class, 'edit'])->name('edit');
        Route::put('update/{id}', [BankController::class,'update'])->name('update');
        Route::delete('delete/{id}', [BankController::class,'destroy'])->name('delete');

    });
    Route::group(['prefix'=>'countries','as'=>'countries.'], function () {
        Route::get('/', [CountryController::class,'index'])->name('index');
        Route::get('create', [CountryController::class,'create'])->name('create');
        Route::post('create', [CountryController::class,'store'])->name('store');
        Route::get('edit/{id}',[CountryController::class, 'edit'])->name('edit');
        Route::put('update/{id}', [CountryController::class,'update'])->name('update');
        Route::delete('delete/{id}', [CountryController::class,'destroy'])->name('delete');
        Route::post('update_status', [CountryController::class,'update_status'])->name('update_status');
        Route::get('cities', [CountryController::class,'cities'])->name('get-cities');

    });
Route::group(['prefix'=>'city','as'=>'city.'], function () {
    Route::get('/', [CityController::class,'index'])->name('index');
    Route::get('create', [CityController::class,'create'])->name('create');
    Route::post('create', [CityController::class,'store'])->name('store');
    Route::get('edit/{id}',[CityController::class, 'edit'])->name('edit');
    Route::put('update/{id}', [CityController::class,'update'])->name('update');
    Route::delete('delete/{id}', [CityController::class,'destroy'])->name('delete');
    Route::post('update_status', [CityController::class,'update_status'])->name('update_status');

    Route::get('areas', [CityController::class,'areas'])->name('get-area');
});

    Route::group(['prefix'=>'areas','as'=>'areas.'], function () {
        Route::get('/', [AreaController::class,'index'])->name('index');
        Route::get('create', [AreaController::class,'create'])->name('create');
        Route::post('create', [AreaController::class,'store'])->name('store');
        Route::get('edit/{id}',[AreaController::class, 'edit'])->name('edit');
        Route::put('update/{id}', [AreaController::class,'update'])->name('update');
        Route::delete('delete/{id}', [AreaController::class,'destroy'])->name('delete');
        Route::post('update_status', [AreaController::class,'update_status'])->name('update_status');
    });
    Route::group(['prefix'=>'vehicle_manufacture_years','as'=>'vehicle_manufacture_years.'], function () {
        Route::get('/', [VehicleManufactureYearController::class,'index'])->name('index');
        Route::get('create', [VehicleManufactureYearController::class,'create'])->name('create');
        Route::post('create', [VehicleManufactureYearController::class,'store'])->name('store');
        Route::get('edit/{id}',[VehicleManufactureYearController::class, 'edit'])->name('edit');
        Route::put('update/{id}', [VehicleManufactureYearController::class,'update'])->name('update');
        Route::delete('delete/{id}', [VehicleManufactureYearController::class,'destroy'])->name('delete');
        Route::post('update_status', [VehicleManufactureYearController::class,'update_status'])->name('update_status');
    });
    Route::group(['prefix'=>'vehicle_types','as'=>'vehicle_types.'], function () {
        Route::get('/', [VehicleTypeController::class,'index'])->name('index');
        Route::get('create', [VehicleTypeController::class,'create'])->name('create');
        Route::post('create', [VehicleTypeController::class,'store'])->name('store');
        Route::get('edit/{id}',[VehicleTypeController::class, 'edit'])->name('edit');
        Route::put('update/{id}', [VehicleTypeController::class,'update'])->name('update');
        Route::delete('delete/{id}', [VehicleTypeController::class,'destroy'])->name('delete');
        Route::post('update_status', [VehicleTypeController::class,'update_status'])->name('update_status');
    });


    Route::group(['prefix'=>'vehicle_brands','as'=>'vehicle_brands.'], function () {
        Route::get('/', [VehicleBrandController::class,'index'])->name('index');
        Route::get('create', [VehicleBrandController::class,'create'])->name('create');
        Route::post('create', [VehicleBrandController::class,'store'])->name('store');
        Route::get('edit/{id}',[VehicleBrandController::class, 'edit'])->name('edit');
        Route::put('update/{id}', [VehicleBrandController::class,'update'])->name('update');
        Route::delete('delete/{id}', [VehicleBrandController::class,'destroy'])->name('delete');
        Route::post('update_status', [VehicleBrandController::class,'update_status'])->name('update_status');
    });
    Route::group(['prefix'=>'vehicle_models','as'=>'vehicle_models.'], function () {
        Route::get('/', [VehicleModelController::class,'index'])->name('index');
        Route::get('create', [VehicleModelController::class,'create'])->name('create');
        Route::post('create', [VehicleModelController::class,'store'])->name('store');
        Route::get('edit/{id}',[VehicleModelController::class, 'edit'])->name('edit');
        Route::put('update/{id}', [VehicleModelController::class,'update'])->name('update');
        Route::delete('delete/{id}', [VehicleModelController::class,'destroy'])->name('delete');
        Route::post('update_status', [VehicleModelController::class,'update_status'])->name('update_status');
    });
    Route::group(['prefix'=>'type_gasolines','as'=>'type_gasolines.'], function () {
        Route::get('/', [TypeGasolineController::class,'index'])->name('index');
        Route::get('create', [TypeGasolineController::class,'create'])->name('create');
        Route::post('create', [TypeGasolineController::class,'store'])->name('store');
        Route::get('edit/{id}',[TypeGasolineController::class, 'edit'])->name('edit');
        Route::put('update/{id}', [TypeGasolineController::class,'update'])->name('update');
        Route::delete('delete/{id}', [TypeGasolineController::class,'destroy'])->name('delete');
        Route::post('update_status', [TypeGasolineController::class,'update_status'])->name('update_status');
    });

    Route::group(['prefix'=>'type_batteries','as'=>'type_batteries.'], function () {
        Route::get('/', [TypeBatteryController::class,'index'])->name('index');
        Route::get('create', [TypeBatteryController::class,'create'])->name('create');
        Route::post('create', [TypeBatteryController::class,'store'])->name('store');
        Route::get('edit/{id}',[TypeBatteryController::class, 'edit'])->name('edit');
        Route::put('update/{id}', [TypeBatteryController::class,'update'])->name('update');
        Route::delete('delete/{id}', [TypeBatteryController::class,'destroy'])->name('delete');
        Route::post('update_status', [TypeBatteryController::class,'update_status'])->name('update_status');
        Route::post('delete-image', [TypeBatteryController::class,'deleteImage'])->name('deleteImage');

    });


    Route::group(['prefix'=>'tires','as'=>'tires.'], function () {
        Route::get('/', [TireController::class,'index'])->name('index');
        Route::get('create', [TireController::class,'create'])->name('create');
        Route::post('create', [TireController::class,'store'])->name('store');
        Route::get('edit/{id}',[TireController::class, 'edit'])->name('edit');
        Route::put('update/{id}', [TireController::class,'update'])->name('update');
        Route::delete('delete/{id}', [TireController::class,'destroy'])->name('delete');
        Route::post('update_status', [TireController::class,'update_status'])->name('update_status');
        Route::post('delete-image', [TireController::class,'deleteImage'])->name('deleteImage');

    });
    Route::group(['prefix'=>'cy_periodics','as'=>'cy_periodics.'], function () {
        Route::get('/', [CyPeriodicController::class,'index'])->name('index');
        Route::get('create', [CyPeriodicController::class,'create'])->name('create');
        Route::post('create', [CyPeriodicController::class,'store'])->name('store');
        Route::get('edit/{id}',[CyPeriodicController::class, 'edit'])->name('edit');
        Route::put('update/{id}', [CyPeriodicController::class,'update'])->name('update');
        Route::delete('delete/{id}', [CyPeriodicController::class,'destroy'])->name('delete');
        Route::post('update_status', [CyPeriodicController::class,'update_status'])->name('update_status');

    });
    Route::group(['prefix'=>'transporters','as'=>'transporters.'], function () {
        Route::get('/', [TransporterController::class,'index'])->name('index');
        Route::get('create', [TransporterController::class,'create'])->name('create');
        Route::post('create', [TransporterController::class,'store'])->name('store');
        Route::get('edit/{id}',[TransporterController::class, 'edit'])->name('edit');
        Route::put('update/{id}', [TransporterController::class,'update'])->name('update');
        Route::delete('delete/{id}', [TransporterController::class,'destroy'])->name('delete');
        Route::post('update_status', [TransporterController::class,'update_status'])->name('update_status');
        Route::post('delete-image', [TransporterController::class,'deleteImage'])->name('deleteImage');

    });
    Route::group(['prefix'=>'slider','as'=>'slider.'], function () {
        Route::get('/', [SliderController::class,'index'])->name('index');
        Route::post('/', [SliderController::class,'index'])->name('index');
        Route::get('create', [SliderController::class,'create'])->name('create');
        Route::post('create', [SliderController::class,'store'])->name('store');
        Route::get('edit/{id}',[SliderController::class, 'edit'])->name('edit');
        Route::put('update/{id}', [SliderController::class,'update'])->name('update');
        Route::delete('delete/{id}', [SliderController::class,'destroy'])->name('delete');
        Route::post('update_status', [SliderController::class,'update_status'])->name('update_status');
        Route::post('delete-image', [SliderController::class,'deleteImage'])->name('deleteImage');

    });
    Route::group(['prefix'=>'notifications','as'=>'notifications.'], function () {
        Route::get('/', [NotificationController::class,'index'])->name('index');
        Route::post('/', [NotificationController::class,'index'])->name('index');
        Route::get('create', [NotificationController::class,'create'])->name('create');
        Route::post('create', [NotificationController::class,'store'])->name('store');
        Route::get('edit/{id}',[NotificationController::class, 'edit'])->name('edit');
        Route::put('update/{id}', [NotificationController::class,'update'])->name('update');
        Route::delete('delete/{id}', [NotificationController::class,'destroy'])->name('delete');
        Route::post('update_status', [NotificationController::class,'update_status'])->name('update_status');
        Route::post('delete-image', [NotificationController::class,'deleteImage'])->name('deleteImage');

    });
    Route::group(['prefix'=>'splash_screen','as'=>'splash_screen.'], function () {
        Route::get('/', [SplashScreenController::class,'index'])->name('index');
        Route::get('create', [SplashScreenController::class,'create'])->name('create');
        Route::post('create', [SplashScreenController::class,'store'])->name('store');
        Route::get('edit/{id}',[SplashScreenController::class, 'edit'])->name('edit');
        Route::put('update/{id}', [SplashScreenController::class,'update'])->name('update');
        Route::delete('delete/{id}', [SplashScreenController::class,'destroy'])->name('delete');
        Route::post('update_status', [SplashScreenController::class,'update_status'])->name('update_status');
    });
    Route::group(['prefix'=>'contact_us','as'=>'contact_us.'], function () {
        Route::get('/', [ContactUsController::class,'index'])->name('index');
        Route::delete('delete/{id}', [ContactUsController::class,'destroy'])->name('delete');
    });


    Route::group(['prefix'=>'sms','as'=>'sms.'], function () {
        Route::get('/', [SmsController::class,'index'])->name('index');
        Route::get('create/{phone?}', [SmsController::class,'create'])->name('create');
        Route::post('create', [SmsController::class,'store'])->name('store');
        Route::delete('delete/{id}', [SmsController::class,'destroy'])->name('delete');
    });
    /// admin
    Route::group(['prefix'=>'settings','as'=>'settings.'], function () {
        Route::get('general-setting', [SettingController::class,'getGeneralSetting'])->name('getGeneralSetting');
        Route::post('general-setting', [SettingController::class,'updateGeneralSetting'])->name('updateGeneralSetting');
    });
    Route::post('admins/check-password/{id}', [AdminController::class ,'checkPassword'])->name('checkPassword');
    Route::post('admins/check-admin-password/{id}', [AdminController::class ,'checkAdminPassword'])->name('checkPassword');
    Route::get('admins/get-dropdown', [AdminController::class ,'getDropdown'])->name('getDropdown');
    Route::get('admins/get-profile', [AdminController::class ,'getProfile'])->name('getProfile');
    Route::put('admins/update-profile', [AdminController::class ,'updateProfile'])->name('updateProfile');

    Route::group(['prefix'=>'admins','as'=>'admins.'], function () {
        Route::get('/', [AdminController::class,'index'])->name('index');
        Route::post('/', [AdminController::class,'index'])->name('index');
        Route::get('create', [AdminController::class,'create'])->name('create');
        Route::post('create', [AdminController::class,'store'])->name('store');
        Route::get('edit/{id}',[AdminController::class, 'edit'])->name('edit');
        Route::put('update/{id}', [AdminController::class,'update'])->name('update');
        Route::delete('delete/{id}', [AdminController::class,'destroy'])->name('delete');
        Route::post('update_status', [AdminController::class,'update_status'])->name('update_status');
        Route::post('delete-image', [AdminController::class,'deleteImage'])->name('deleteImage');

    });
    Route::group(['prefix'=>'infos','as'=>'infos.'], function () {
        Route::get('edit/{slug}',[InfoController::class, 'edit'])->name('edit');
        Route::put('update/{id}', [InfoController::class,'update'])->name('update');


    });
    Route::group(['prefix'=>'category_faqs','as'=>'category_faqs.'], function () {
        Route::get('/', [CategoryFaqController::class,'index'])->name('index');
        Route::get('create', [CategoryFaqController::class,'create'])->name('create');
        Route::post('create', [CategoryFaqController::class,'store'])->name('store');
        Route::get('edit/{id}',[CategoryFaqController::class, 'edit'])->name('edit');
        Route::put('update/{id}', [CategoryFaqController::class,'update'])->name('update');
        Route::delete('delete/{id}', [CategoryFaqController::class,'destroy'])->name('delete');
    });
    Route::group(['prefix'=>'faqs','as'=>'faqs.'], function () {
        Route::get('/', [FaqController::class,'index'])->name('index');
        Route::get('create', [FaqController::class,'create'])->name('create');
        Route::post('create', [FaqController::class,'store'])->name('store');
        Route::get('edit/{id}',[FaqController::class, 'edit'])->name('edit');
        Route::put('update/{id}', [FaqController::class,'update'])->name('update');
        Route::delete('delete/{id}', [FaqController::class,'destroy'])->name('delete');
    });

    Route::group(['prefix'=>'reports','as'=>'reports.'], function () {
        Route::get('/get-daily-report', [ReportController::class,'getDailyReport'])->name('getDailyReport');
        Route::get('/get-monthly-report', [ReportController::class,'getMonthlyReport'])->name('getMonthlyReport');
        Route::get('/get-yearly-report', [ReportController::class,'getYearlyReport'])->name('getYearlyReport');
        Route::get('/get-best-report', [ReportController::class,'getBestReport'])->name('getBestReport');

    });
    Route::post('logout', [LoginController::class,'logout'])->name('logout');

});
