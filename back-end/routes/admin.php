<?php


use Illuminate\Support\Facades\Route;

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
Route::group(['middleware' => ['language']], function () {
    // Authentication Routes...
    Route::get('login', [\App\Http\Controllers\BackEnd\Auth\LoginController::class,'showLoginForm'])->name('login');
    Route::post('login', 'BackEnd\Auth\LoginController@login');
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

Route::get('general/switch-language/{lang}', [\App\Http\Controllers\BackEnd\GeneralController::class ,'switchLanguage'])->name('switchLanguage');
Route::group(['middleware' => ['auth', 'SetSessionData', 'language', 'timezone']], function () {

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});
