<?php

use App\Http\Controllers\Api\Providers\AuthController;


use App\Http\Controllers\Front\GeneralController;
use App\Http\Controllers\Front\SplashScreenController;
use App\Http\Controllers\Front\ReviewController;




use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;








Route::get('splash-screen', [SplashScreenController::class, 'index']);
Route::post('splash-screen/store', [SplashScreenController::class, 'store']);

Route::get('reviews', [ReviewController::class, 'index']);
Route::post('reviews/store', [ReviewController::class, 'store']);

Route::get('settings-data/{type}', [GeneralController::class, 'SettingData']);
Route::get('icons', [GeneralController::class, 'icons']);
Route::get('faqs', [GeneralController::class, 'faqs']);
Route::get('infos', [GeneralController::class, 'infos']);
Route::post('contact-us', [GeneralController::class, 'contactUs']);
Route::post('store-subscribe', [GeneralController::class, 'storeSubscribe']);

Route::get('categories', [GeneralController::class, 'Categories']);
Route::get('services', [GeneralController::class, 'services']);



// Fail Api
Route::fallback(function (Request $request) {
    $response = \App\CPU\translate("Page Not Found.If error persists,contact info@gmail.com");

    return responseApiFalse(404, $response);
});
