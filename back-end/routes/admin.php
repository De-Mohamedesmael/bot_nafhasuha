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


    Route::post('logout', 'BackEnd\Auth\LoginController@logout')->name('logout');

// Registration Routes...
    Route::get('register', 'BackEnd\Auth\RegisterController@showRegistrationForm')->name('register');
    Route::post('register', 'BackEnd\Auth\RegisterController@register');

// Password Reset Routes...
    Route::get('password/reset', 'BackEnd\Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('password/email', 'BackEnd\Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::get('password/reset/{token}', 'BackEnd\Auth\ResetPasswordController@showResetForm');
    Route::post('password/reset', 'BackEnd\Auth\ResetPasswordController@reset');
});
Route::get('general/switch-language/{lang}', [GeneralController::class ,'switchLanguage'])->name('switchLanguage');

Route::group(['middleware' => ['auth:admin', 'SetSessionData', 'language', 'timezone']], function () {

    Route::get('/home', [HomeController::class, 'index'])->name('home');

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
    /// admin

    Route::post('admins/check-password/{id}', [AdminController::class ,'checkPassword'])->name('checkPassword');
    Route::post('admins/check-admin-password/{id}', [AdminController::class ,'checkAdminPassword'])->name('checkPassword');
    Route::get('admins/get-dropdown', [AdminController::class ,'getDropdown'])->name('getDropdown');
    Route::get('admins/get-profile', [AdminController::class ,'getProfile'])->name('getProfile');
    Route::put('admins/update-profile', [AdminController::class ,'updateProfile'])->name('updateProfile');
    Route::resource('admins', AdminController::class);
});
