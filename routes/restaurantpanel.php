<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RestaurantPanel\AuthController;
use App\Http\Controllers\RestaurantPanel\DashboardController;
use App\Http\Controllers\RestaurantPanel\RestaurantTableController;
use App\Http\Controllers\RestaurantPanel\RestaurantBookingController;

// Restaurant Panel Authentication
Route::get('login', [AuthController::class, 'showLoginForm'])
    ->name('restaurant.login');

Route::post('login', [AuthController::class, 'login'])
    ->name('restaurant.login.submit');

// Restaurant Registration
Route::get('register', [AuthController::class, 'showRegisterForm'])
    ->name('restaurant.register');

Route::post('register', [AuthController::class, 'register'])
    ->name('restaurant.register.submit');

Route::post('logout', [AuthController::class, 'logout'])
    ->middleware('auth:restaurant')
    ->name('restaurant.logout');

// Restaurant Panel Forgot Password
Route::get('forgot-password', [AuthController::class, 'showForgotForm'])
    ->name('restaurant.password.request');

Route::post('send-otp', [AuthController::class, 'sendOtp'])
    ->name('restaurant.password.sendOtp');

Route::get('verify-otp', [AuthController::class, 'showVerifyForm'])
    ->name('restaurant.password.verifyForm');

Route::post('verify-otp', [AuthController::class, 'verifyOtp'])
    ->name('restaurant.password.verifyOtp');

Route::post('reset-password-otp', [AuthController::class, 'resetPassword'])
    ->name('restaurant.password.resetOtp');

// Restaurant Panel Dashboard
Route::group(['middleware' => 'auth:restaurant'], function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('restaurant.dashboard');

    Route::get('/tables', [RestaurantTableController::class, 'index'])
        ->name('restaurant.tables.index');

    Route::get('/tables/create', [RestaurantTableController::class, 'create'])
        ->name('restaurant.tables.create');

    Route::post('/tables', [RestaurantTableController::class, 'store'])
        ->name('restaurant.tables.store');

    Route::get('/tables/{id}/edit', [RestaurantTableController::class, 'edit'])
        ->name('restaurant.tables.edit');

    Route::put('/tables/{id}', [RestaurantTableController::class, 'update'])
        ->name('restaurant.tables.update');

        Route::delete('/tables/{id}', [RestaurantTableController::class, 'destroy'])
    ->name('restaurant.tables.destroy');


   Route::get('/table-bookings', [RestaurantBookingController::class, 'index'])
    ->name('restaurant.table-bookings.index');

Route::get('/table-bookings/{id}', [RestaurantBookingController::class, 'show'])
    ->name('restaurant.table-bookings.show');

Route::post('/table-bookings/{id}/status', [RestaurantBookingController::class, 'quickUpdateStatus'])
    ->name('restaurant.table-bookings.status');

});
