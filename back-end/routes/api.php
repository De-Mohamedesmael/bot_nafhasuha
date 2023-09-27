<?php

use App\Http\Controllers\Api\Clients\AuthController;

use App\Http\Controllers\Api\Clients\HomeController;
use App\Http\Controllers\Api\Clients\PackageController;


use App\Http\Controllers\Api\Clients\TransactionController;
use App\Http\Controllers\Api\Clients\NotificationController;
use App\Http\Controllers\Api\Clients\ServiceController;
use App\Http\Controllers\Api\Clients\OrderController;
use App\Http\Controllers\Api\Clients\ServiceEmergencyController;


use App\Http\Controllers\Api\Clients\GeneralController;
use App\Http\Controllers\Api\Clients\VehicleController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;


////////////////////////////////// start  auth /////////////////////////////////////////////
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::get('logout', [AuthController::class, 'logout']);
Route::get('refresh', [AuthController::class, 'refresh']);
Route::get('profile', [AuthController::class, 'userProfile']);
Route::post('edit-profile', [AuthController::class, 'editProfile']);
Route::post('upload-image', [AuthController::class, 'uploadImage']);
Route::post('change-password', [AuthController::class, 'changePassword']);
Route::post('check-phone', [AuthController::class, 'checkPhone']);
Route::post('send-code', [AuthController::class, 'SendCode']);
Route::post('check-code', [AuthController::class, 'checkCode']);
Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
Route::delete('remove-account', [AuthController::class, 'removeAccount']);
Route::post('custom-remove-account', [AuthController::class, 'customRemoveAccount']);

Route::get('home', [HomeController::class, 'index']);


Route::middleware('auth.guard:api')->group(function () {


    #############################################
    ###          wallet & Transactions          ###
    #############################################
    Route::prefix('transactions')->group(function () {
        Route::get('/my-wallet', [TransactionController::class, 'myWallet']);
        Route::get('/', [TransactionController::class, 'index']);
        Route::get('/show', [TransactionController::class, 'show']);
        Route::post('/store', [TransactionController::class, 'StorePackageUser']);
        Route::get('/my-package', [TransactionController::class, 'MyPackage']);
    });




    #############################################
    ###                 Packages              ###
    #############################################
    Route::prefix('packages')->group(function () {
        Route::get('/', [PackageController::class, 'index']);
        Route::get('/show', [PackageController::class, 'show']);
        Route::post('/store', [PackageController::class, 'StorePackageUser']);
        Route::get('/my-package', [PackageController::class, 'MyPackage']);

    });
    Route::prefix('services')->group(function () {
        Route::post('/get-provides-map-all', [ServiceController::class, 'ProviderMapAll']);
        Route::post('/get-provides-map', [ServiceController::class, 'ProviderMap']);
        Route::Put('/view-provide-map/{id}', [ServiceController::class, 'ViewProviderMap']);

    });
    #############################################
    ###          vehicles  user               ###
    #############################################
    Route::prefix('vehicles')->group(function () {
        Route::get('/my-vehicles', [VehicleController::class, 'MyVehicle']);
        Route::get('/my-vehicle', [VehicleController::class, 'MyVehicleOne']);
        Route::Post('/store', [VehicleController::class, 'StoreUserVehicle']);
        Route::Post('/edit', [VehicleController::class, 'EditUserVehicle']);
        Route::delete('/delete/{id}', [VehicleController::class, 'DeleteUserVehicle']);

    });

    #############################################
    ###          Order  Service               ###
    #############################################
    Route::prefix('services/store')->group(function () {
        Route::Post('maintenance', [ServiceController::class, 'StoreOrderServiceMaintenance']);
        Route::Post('vehicle-barriers', [ServiceController::class, 'StoreOrderServiceVehicleBarriers']);
        Route::Post('periodic-inspection', [ServiceController::class, 'StoreOrderServicePeriodicInspection']);
        Route::Post('consultation', [ServiceController::class, 'StoreOrderServiceConsultation']);
        Route::Post('transporter', [ServiceController::class, 'StoreOrderServiceTransportVehicle']);
        Route::Post('transporter/cost', [ServiceController::class, 'CostServiceTransportVehicle']);
        Route::prefix('emergency')->group(function () {
            Route::Post('transporter', [ServiceEmergencyController::class, 'StoreOrderServiceTransportVehicle']);
            Route::get('battery/data', [ServiceEmergencyController::class, 'GetDataServiceBattery']);
            Route::Post('battery', [ServiceEmergencyController::class, 'StoreOrderServiceBattery']);


            Route::Post('petrol', [ServiceEmergencyController::class, 'StoreOrderServicePetrol']);
            Route::get('petrol/data', [ServiceEmergencyController::class, 'GetDataServicePetrol']);


            Route::get('tires/data', [ServiceEmergencyController::class, 'GetDataServiceTire']);
            Route::Post('tires', [ServiceEmergencyController::class, 'StoreOrderServiceTire']);

        });
    });
    Route::prefix('orders')->group(function () {
        Route::get('pending', [OrderController::class, 'indexPending']);
        Route::get('completed', [OrderController::class, 'indexCompleted']);
        Route::get('canceld', [OrderController::class, 'indexCanceld']);
        Route::get('show_by_code', [OrderController::class, 'GetByInvoiceNo']);

        Route::get('show/{id}', [OrderController::class, 'show']);
        Route::get('quotes/{id}', [OrderController::class, 'quotes']);
        Route::Post('reject-quotes', [OrderController::class, 'rejectQuotes']);

    });

    Route::Post('check-coupon', [ServiceController::class, 'getCoupon']);

});

Route::prefix('services')->group(function () {
    Route::get('/', [ServiceController::class, 'index']);
    Route::get('/categories', [ServiceController::class, 'indexCategories']);
    Route::get('/transport-vehicles', [ServiceController::class, 'transportVehicles']);

});
    #############################################
    ###          vehicles & Data              ###
    #############################################
Route::prefix('vehicles')->group(function () {
    Route::get('/manufacture-years', [VehicleController::class, 'manufactureYears']);
    Route::get('/types', [VehicleController::class, 'VehicleTypes']);
    Route::get('/models', [VehicleController::class, 'VehicleModels']);
    Route::get('/brands', [VehicleController::class, 'VehicleBrand']);

});

    /////////////////////////////////////////////
    ///                     info              ///
    /// /////////////////////////////////////////
Route::get('splash-screens', [GeneralController::class, 'splashScreen']);
Route::get('update-version',[GeneralController::class, 'updateVersion']);
Route::get('countries', [GeneralController::class, 'countries']);
Route::get('cities', [GeneralController::class, 'cities']);
Route::get('areas', [GeneralController::class, 'areas']);
Route::get('icons', [GeneralController::class, 'icons']);
Route::get('faqs', [GeneralController::class, 'faqs']);
Route::get('infos', [GeneralController::class, 'infos']);
Route::get('get-home-or-center', [GeneralController::class, 'GetHomeOrCenter']);
Route::post('contact-us', [GeneralController::class, 'contactUs']);
Route::post('get-periodic-inspection', [GeneralController::class, 'GetPeriodicInspection']);
Route::get('get-offers', [GeneralController::class, 'GetOffers']);

///////////////////////////////// start notifications //////////////////////////////////////////
Route::get('notifications', [NotificationControlleR::class, 'index']);
Route::post('notifications/save_token' , [NotificationController::class , 'save_token']);
Route::get('notifications/count' , [NotificationController::class , 'count']);
Route::get('notifications/show' , [NotificationController::class , 'show']);
Route::post('notifications/status' , [NotificationController::class , 'changeStatus']);

///////////////////////////////// end notifications ///////////////////////////////////////////////



// Fail Api
Route::fallback(function (Request $request) {
    $response = \App\CPU\translate("Page Not Found.If error persists,contact info@gmail.com");

    return responseApiFalse(404, $response);
});
