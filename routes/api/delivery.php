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

    Route::post('/profile/update', [AuthController::class, 'updateProfile']);

    Route::post('/vehicle-details/update', [AuthController::class, 'updateVehicleDetails']);

    Route::post('/bank-details/update', [AuthController::class, 'updateBankDetails']);

    Route::post('/documents/upload', [AuthController::class, 'uploadDocuments']);

    Route::post('/orders/{id}/send-delivery-otp', [
        OrderController::class,
        'sendDeliveryOtp'
    ]);

    Route::post('/orders/{id}/verify-delivery-otp', [
        OrderController::class,
        'verifyDeliveryOtp'
    ]);
});