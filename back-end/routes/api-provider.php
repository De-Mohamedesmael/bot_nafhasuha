<?php

use App\Http\Controllers\Api\Providers\AuthController;


use App\Http\Controllers\Api\Providers\HomeController;
use App\Http\Controllers\Api\Providers\GeneralController;
use App\Http\Controllers\Api\Providers\TransactionController;
use App\Http\Controllers\Api\Providers\OrderController;
use App\Http\Controllers\Api\Providers\NotificationController;




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
Route::post('change-default-language', [AuthController::class, 'ChangeDefaultLanguage']);

Route::middleware('auth.guard:api')->group(function () {

    Route::get('home', [HomeController::class, 'index']);



    Route::get('completed-orders', [OrderController::class, 'CompletedOrders']);
    Route::get('ongoing-orders', [OrderController::class, 'OngoingOrders']);
    Route::get('order/{id}', [OrderController::class, 'getOrderOne']);
    Route::get('my-order/{id}', [OrderController::class, 'getMyOrderOne']);

    Route::POST('submit-price', [OrderController::class, 'submitPrice']);
    Route::post('accept-order', [OrderController::class, 'acceptOrder']);
    Route::post('maintenance-report', [OrderController::class, 'StoreMaintenanceReport']);
    Route::Post('store-complete-order', [OrderController::class, 'storeCompletedOrder']);

    Route::post('cancel-orders-accept', [OrderController::class, 'CancelOrdersAccept']);

    Route::post('cancel-orders-ongoing', [OrderController::class, 'CancelOrdersOngoing']);
    Route::get('change-status-get-orders', [OrderController::class, 'ChangeStatusGetOrders']);



    Route::get('my-wallet', [TransactionController::class, 'myWallet']);
    Route::POST('request-withdrawal', [TransactionController::class, 'StoreWithdrawalRequest']);

    Route::post('transactions/recharge-wallet', [TransactionController::class, 'RechargeMyWallet']);

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
Route::get('banks', [GeneralController::class, 'banks']);
Route::get('canceled-reasons', [GeneralController::class, 'GetCanceledReasons']);

///////////////////////////////// start notifications //////////////////////////////////////////
Route::get('notifications', [NotificationController::class, 'index']);
Route::post('notifications/save_token' , [NotificationController::class , 'save_token']);
Route::get('notifications/count' , [NotificationController::class , 'count']);
Route::get('notifications/show' , [NotificationController::class , 'show']);
Route::get('notifications/status' , [NotificationController::class , 'changeStatus']);

///////////////////////////////// end notifications ///////////////////////////////////////////////

// Fail Api
Route::fallback(function (Request $request) {
    $response = \App\CPU\translate("Page Not Found.If error persists,contact info@gmail.com");

    return responseApiFalse(404, $response);
});
