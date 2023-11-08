<?php

use Illuminate\Support\Facades\Route;

/**
 * as & prefix => front
 */
Route::view('/', 'front-end.home')->name('front.user.home');
Route::view('/provider', 'front-end.provider.home')->name('provider.home');
