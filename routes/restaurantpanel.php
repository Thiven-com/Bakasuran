
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RestaurantPanel\AuthController;

// Restaurant Panel Authentication
Route::get('login', [AuthController::class, 'showLoginForm'])
    ->name('restaurant.login');

Route::post('login', [AuthController::class, 'login'])
    ->name('restaurant.login.submit');

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

    Route::get('/dashboard', function () {
        return view('restaurantpanel.auth.dashboard');
    })->name('restaurant.dashboard');

});
