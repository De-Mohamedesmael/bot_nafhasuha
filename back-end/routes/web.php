<?php

use App\Http\Controllers\Dashboard\AdminController;
use App\Http\Controllers\Dashboard\AuthController;
use App\Http\Controllers\Dashboard\BoothController;
use App\Http\Controllers\Dashboard\BoothNumberController;
use App\Http\Controllers\Dashboard\CategoryController;
use App\Http\Controllers\Dashboard\CoinController;
use App\Http\Controllers\Dashboard\CoinPackageController;
use App\Http\Controllers\Dashboard\CoinPackageOrderController;
use App\Http\Controllers\Dashboard\ContactController;
use App\Http\Controllers\Dashboard\DailyAdsController;
use App\Http\Controllers\Dashboard\GiftBoxController;
use App\Http\Controllers\Dashboard\HomeController;
use App\Http\Controllers\Dashboard\OrderController;
use App\Http\Controllers\Dashboard\ScreenAdsController;
use App\Http\Controllers\Dashboard\SettingController;
use App\Http\Controllers\Dashboard\StandAdsController;
use App\Http\Controllers\Dashboard\StoreController;
use App\Http\Controllers\Dashboard\TrashedStoreController;
use App\Http\Controllers\Dashboard\UserController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'front-end.home');
