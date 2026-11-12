<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeliveryApp\AuthController;
use App\Http\Controllers\DeliveryApp\OrderController;

// Delivery Partner Login
Route::post('login', [AuthController::class, 'login']);

Route::post('verify-otp', [AuthController::class, 'verifyOtp']);

// Protected Delivery Partner APIs
Route::middleware('auth:sanctum')->group(function () {

    // Home API
    Route::get('home', [AuthController::class, 'home']);

    // Logout
    Route::post('logout', [AuthController::class, 'logout']);

    Route::get('/new-orders', [OrderController::class, 'newOrders']);

    Route::post('/orders/{id}/accept', [OrderController::class, 'acceptOrder']);

    Route::get('/orders/{id}/details', [OrderController::class, 'orderDetails']);

    Route::post('/orders/{id}/pickup', [OrderController::class, 'pickupOrder']);

    Route::get('/earnings', [OrderController::class, 'earnings']);

    Route::get('/earning-history', [OrderController::class, 'earningHistory']);

    Route::get('/profile', [AuthController::class, 'profile']);

});