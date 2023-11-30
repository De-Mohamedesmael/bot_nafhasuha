<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BackEnd\Auth\LoginController;
use App\Http\Controllers\BackEnd\HomeController;
use App\Http\Controllers\BackEnd\CustomerController;
use App\Http\Controllers\BackEnd\ProviderController;
use App\Http\Controllers\BackEnd\OrderController;

use App\Http\Controllers\BackEnd\TransactionPaymentController;
use App\Http\Controllers\BackEnd\CategoryController;
use App\Http\Controllers\BackEnd\ServiceController;
use App\Http\Controllers\BackEnd\CityController;
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

Route::group(['prefix'=>'city','as'=>'city.'], function () {
    Route::get('/', [CustomerController::class,'index'])->name('index');
    Route::post('/', [CustomerController::class,'index'])->name('index');
    Route::get('create', [CustomerController::class,'create'])->name('create');
    Route::post('create', [CustomerController::class,'store'])->name('store');

    Route::get('areas', [CityController::class,'areas'])->name('get-area');
});




    /// admin

    Route::post('admins/check-password/{id}', [AdminController::class ,'checkPassword'])->name('checkPassword');
    Route::post('admins/check-admin-password/{id}', [AdminController::class ,'checkAdminPassword'])->name('checkPassword');
    Route::get('admins/get-dropdown', [AdminController::class ,'getDropdown'])->name('getDropdown');
    Route::get('admins/get-profile', [AdminController::class ,'getProfile'])->name('getProfile');
    Route::put('admins/update-profile', [AdminController::class ,'updateProfile'])->name('updateProfile');
    Route::resource('admins', AdminController::class);
});
