
<?php

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

    // Customer order history.
    Route::get('/my-orders', [OrderController::class, 'orders'])
        ->name('customer.orders');

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
});

/*
|--------------------------------------------------------------------------
| Cart Add
|--------------------------------------------------------------------------
*/

Route::post('/cart/add', [AccountController::class, 'addToCart'])
    ->name('cart.add');
