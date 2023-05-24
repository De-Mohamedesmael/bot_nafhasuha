<?php

use App\Http\Controllers\Api\Providers\AuthController;
use App\Http\Controllers\Api\Providers\GeneralController;

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;


////////////////////////////////// start  auth /////////////////////////////////////////////
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::get('logout', [AuthController::class, 'logout']);
Route::get('refresh', [AuthController::class, 'refresh']);
Route::get('profile', [AuthController::class, 'ProviderProfile']);
Route::post('edit-profile', [AuthController::class, 'editProfile']);
Route::post('upload-image', [AuthController::class, 'uploadImage']);
Route::post('change-password', [AuthController::class, 'changePassword']);
Route::post('check-phone', [AuthController::class, 'checkPhone']);
Route::post('send-code', [AuthController::class, 'SendCode']);
Route::post('check-code', [AuthController::class, 'checkCode']);
Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
Route::delete('remove-account', [AuthController::class, 'removeAccount']);

Route::middleware('auth.guard:api')->group(function () {



});





Route::get('update-version',[GeneralController::class, 'updateVersion']);
Route::get('countries', [GeneralController::class, 'countries']);
Route::get('cities', [GeneralController::class, 'cities']);
Route::get('areas', [GeneralController::class, 'areas']);
Route::get('icons', [GeneralController::class, 'icons']);
Route::get('faqs', [GeneralController::class, 'faqs']);
Route::get('infos', [GeneralController::class, 'infos']);
Route::get('get-home-or-center', [GeneralController::class, 'GetHomeOrCenter']);
Route::post('contact-us', [GeneralController::class, 'contactUs']);
Route::get('all-categories', [GeneralController::class, 'indexCategories']);

// Fail Api
Route::fallback(function (Request $request) {
    $response = \App\CPU\translate("Page Not Found.If error persists,contact info@gmail.com");

    return responseApiFalse(404, $response);
});
