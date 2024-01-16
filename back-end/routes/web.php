<?php

use Illuminate\Support\Facades\Route;
use  App\Http\Controllers\GeneralController;
use  App\Http\Controllers\Front\HomeController;
use  App\Http\Controllers\Front\InfoController;
/**
 * as  => front
 */
Route::get('general/switch-language/{lang}', [GeneralController::class ,'switchLanguage'])->name('switchLanguage');
Route::group(['middleware' => ['language']], function () {
    Route::get('/', [HomeController::class ,'indexUser'])->name('user.home');
    Route::get('/provider', [HomeController::class ,'indexProvider'])->name('provider.home');
    Route::Post('/subscribe', [InfoController::class ,'storeSubscribe'])->name('subscribe.store');
    Route::Post('/contact_us', [InfoController::class ,'storeContactUs'])->name('contact_us.store');
    Route::get('/info/{slug}', [InfoController::class,'show'])->name('info.show');

//    Route::view('/', )->name('user.home');
//    Route::view('/provider', 'front-end.provider.home')->name('provider.home');
});
