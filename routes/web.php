<?php

use App\Http\Controllers\FavoriteRestaurantController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageControllers;
use App\Http\Controllers\Website\AccountController;
use App\Http\Controllers\Website\OrderController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [PageControllers::class, 'home'])->name('home');

Route::get('/shop', [PageControllers::class, 'shop'])->name('shop');

Route::get('/restaurants', [PageControllers::class, 'restaurants'])
    ->name('restaurants');
Route::get('/restaurant/{slug}', [PageControllers::class, 'restaurant_details'])
    ->name('restaurant.details');



Route::get('/product/{slug}', [PageControllers::class, 'product_details'])
    ->name('productdetails');

Route::get('/blog', [PageControllers::class, 'blog'])->name('blog');

Route::get('/blog/{slug}', [PageControllers::class, 'blog_details'])
    ->name('blogdetails');

Route::get('/aboutus', [PageControllers::class, 'aboutus'])->name('aboutus');

Route::get('/login', [AccountController::class, 'login'])->name('login');

Route::get('/logout', [AccountController::class, 'logout'])->name('logout');

Route::post('/send-otp', [AccountController::class, 'sendOtp'])
    ->name('sendOtp');

Route::post('/verify-otp', [AccountController::class, 'verifyOtp'])
    ->name('verifyOtp');

Route::get('/wishlist', [PageControllers::class, 'wishlist'])
    ->name('wishlist');

Route::post('/wishlist/add', [AccountController::class, 'addToWishlist'])
    ->name('wishlist.add');

Route::delete('/wishlist/remove/{id}', [AccountController::class, 'removeFromWishlist'])
    ->name('wishlist.remove');

Route::get('/contactus', [PageControllers::class, 'contactus'])
    ->name('contactus');

Route::get('/shippingdelivery', [PageControllers::class, 'shippingdelivery'])
    ->name('shippingdelivery');

Route::get('/returnexchange', [PageControllers::class, 'returnexchange'])
    ->name('returnexchange');

Route::get('/privacypolicy', [PageControllers::class, 'privacypolicy'])
    ->name('privacypolicy');

Route::get('/terms', [PageControllers::class, 'terms'])
    ->name('terms');

Route::get('/faq', [PageControllers::class, 'faq'])
    ->name('faq');

Route::get('/track-order', [PageControllers::class, 'track_order'])
    ->name('track-order');

Route::get('/orders', [PageControllers::class, 'orders'])
    ->name('orders');

Route::get('/order-details', [PageControllers::class, 'order_details'])
    ->name('order-details');

Route::get('/addresses', [PageControllers::class, 'addresses'])
    ->name('addresses');

Route::get('/account-settings', [PageControllers::class, 'account_settings'])
    ->name('account-settings');

Route::get('/account', [PageControllers::class, 'account'])
    ->name('account');

// Customer Dine-In Bill
Route::get(
    '/dine-in-bill/{id}',
    [PageControllers::class, 'viewDineInBill']
)->name('customer.dine-in-bill');

Route::post(
    '/dine-in-bill/{id}/payment-method',
    [PageControllers::class, 'selectDineInPaymentMethod']
)->name('customer.dine-in-payment-method');

Route::get('/dine-in-order-history', [PageControllers::class, 'dineInOrderHistory'])
    ->name('customer.dine-in-order-history');

Route::post('/dine-in-order-history/review', [PageControllers::class, 'storeDineInReview'])
    ->name('customer.dine-in-order-review');

Route::get('/offers', [PageControllers::class, 'offers'])
    ->name('offers');

Route::get('/checkout', [PageControllers::class, 'checkout'])
    ->name('checkout');


/*
|--------------------------------------------------------------------------
| Customer Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:customer')->group(function () {

    // ORDER NOW: selected product goes directly to checkout.
    Route::post('/order-now', [OrderController::class, 'buyNow'])
        ->name('customer.order-now');

    // Place an order from checkout.
    Route::post('/checkout/place-order', [OrderController::class, 'store'])
        ->name('customer.order.store');

    // Razorpay payment verification.
    Route::post('/payment/success', [OrderController::class, 'paymentSuccess'])
        ->name('customer.payment.success');

    // Dine-In Razorpay payment.
    Route::get(
        '/dine-in-payment/{id}',
        [OrderController::class, 'createDineInPayment']
    )->name('customer.dine-in-payment');

    Route::post(
    '/dine-in-payment/{id}/verify',
    [OrderController::class, 'verifyDineInPayment']
)->name('customer.dine-in-payment.verify');

    // Customer order history.
    Route::get('/my-orders', [OrderController::class, 'orders'])
        ->name('customer.orders');

    // Customer restaurant table bookings.
    Route::get('/my-table-bookings', [PageControllers::class, 'myTableBookings'])
        ->name('customer.table-bookings');

    // CUSTOMER DINE-IN MENU
    Route::get(
        '/customer/dine-in/{bookingNumber}',
        [PageControllers::class, 'dineInMenu']
    )->name('customer.dine-in.menu');

    Route::post(
        '/customer/dine-in/{bookingNumber}/place-order',
        [PageControllers::class, 'placeDineInOrder']
    )->name('customer.dine-in.place-order');

    // ==========================================
// RESTAURANT TABLE BOOKING - LOGIN REQUIRED
// ==========================================

    // STEP 1: Booking Details
    Route::get(
        '/restaurant/{slug}/book-table',
        [PageControllers::class, 'table_booking']
    )
        ->name('restaurant.table-booking');

    // Check Availability
    Route::post(
        '/restaurant/{slug}/check-availability',
        [PageControllers::class, 'checkAvailability']
    )
        ->name('restaurant.check-availability');

    // STEP 2: Select Table
    Route::get(
        '/restaurant/{slug}/select-table',
        [PageControllers::class, 'selectTablePage']
    )
        ->name('restaurant.select-table');

    // STEP 3: Booking Confirmation Page
    Route::get(
        '/restaurant/{slug}/booking-confirmation',
        [PageControllers::class, 'confirmBookingPage']
    )
        ->name('restaurant.confirm-page');

    // Submit Final Booking
    Route::post(
        '/restaurant/{slug}/confirm-booking',
        [PageControllers::class, 'confirmTableBooking']
    )
        ->name('restaurant.confirm-booking');


    // STEP 4: Booking Success Page
    Route::get(
        '/restaurant/{slug}/booking-success/{bookingNumber}',
        [PageControllers::class, 'bookingSuccess']
    )->name('restaurant.booking-success');





    // Restaurant table booking - LOGIN REQUIRED
    // Route::get('/restaurant/{slug}/book-table',
    //     [PageControllers::class, 'table_booking'])
    //     ->name('restaurant.table-booking');

    // Route::post('/restaurant/{slug}/check-availability',
    //     [PageControllers::class, 'checkAvailability'])
    //     ->name('restaurant.check-availability');

    // Route::post('/restaurant/{slug}/confirm-booking',
    //     [PageControllers::class, 'confirmTableBooking'])
    //     ->name('restaurant.confirm-booking');

    // Customer addresses.
    Route::post('/addresses/store', [AccountController::class, 'storeAddress'])
        ->name('addresses.store');

    Route::put('/addresses/{id}', [AccountController::class, 'updateAddress'])
        ->name('addresses.update');

    Route::delete('/addresses/{id}', [AccountController::class, 'deleteAddress'])
        ->name('addresses.delete');

    Route::post('/addresses/{id}/default', [AccountController::class, 'setDefaultAddress'])
        ->name('addresses.default');

    // Cart.
    Route::get('/cart', [PageControllers::class, 'cart'])
        ->name('cart');

    Route::delete('/cart/remove/{id}', [AccountController::class, 'removeFromCart'])
        ->name('cart.remove');

    Route::put('/cart/update/{id}', [AccountController::class, 'updateCartQuantity'])
        ->name('cart.update');


    Route::get(
        '/favorite-restaurants',
        [FavoriteRestaurantController::class, 'index']
    )->name('favorite-restaurants');

    Route::post(
        '/favorite-restaurants/toggle',
        [FavoriteRestaurantController::class, 'toggle']
    )->name('favorite-restaurants.toggle');

    Route::delete(
        '/favorite-restaurants/{id}',
        [FavoriteRestaurantController::class, 'destroy']
    )->name('favorite-restaurants.destroy');
});

/*
|--------------------------------------------------------------------------
| Cart Add
|--------------------------------------------------------------------------
*/

Route::post('/cart/add', [AccountController::class, 'addToCart'])
    ->name('cart.add');
