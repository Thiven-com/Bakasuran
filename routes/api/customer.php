<?php

use App\Http\Controllers\CustomerApp\CheckoutController;
use App\Http\Controllers\CustomerApp\WebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('home', 'HomeController@index');
Route::post('login', 'AccountController@login');
Route::post('verifyMobile', 'AccountController@verifyMobile');
Route::post('resendOtp', 'AccountController@resendOtp');

//Product
Route::get('categories', 'CategoryController@categories');
Route::get('featured_categories', 'ProductController@featured_categories');
Route::any('product', 'ProductController@product');
Route::get('states', 'AddressController@states');

Route::any('webhook/razorpay/capture', 'WebhookController@razorpay');

Route::group(['middelware' => 'auth:sanctum'], function () {
    // Route::group(['middleware' => 'verification'], function () {
    Route::get('logout', 'AccountController@logout');
    Route::get('profile', 'ProfileController@profile');
    Route::get('banners', 'ProfileController@banners');
    Route::get('notifications', 'AppNotificationController@index');
    Route::post('products', 'ProductVariantController@index');
    Route::get('product/{slug}', 'ProductVariantController@show');

    Route::get('attributes', "ProductVariantController@attributes");

    Route::post('profile/update', 'ProfileController@updateProfile');
    Route::post('updateProfilePic', 'ProfileController@updateProfilePic');

    //productRating
    Route::post('productRating', 'ProductController@productRating');
    Route::get('ratings', 'ProductController@ratings');


    Route::get('locations', 'AddressController@index');

    Route::post('location/store', 'AddressController@store');
    Route::get('my_locations', 'ProfileController@my_locations');
    Route::get('location/{id}/delete', 'AddressController@destroy');
    Route::any('firebase_token', 'ProfileController@firebase_token');

    // routes/api.php

    Route::prefix('cart')->group(function () {
        Route::get('/', 'CartController@index');                  // view cart
        Route::post('add', 'CartController@add');                 // add to cart
        Route::post('{cartItem}/quantity', 'CartController@updateQuantity');
        Route::delete('{cartItem}', 'CartController@remove');     // remove one
        Route::delete('/', 'CartController@clear');               // clear all

        // call after login to merge guest cart
        Route::post('merge-guest', 'CartController@mergeGuest')->middleware('auth:sanctum');
    });

    Route::prefix('wishlist')->group(function () {
        Route::get('/', 'WishlistController@index');
        Route::post('add', 'WishlistController@store');
        Route::delete('{item}', 'WishlistController@destroy');
        Route::post('toggle', 'WishlistController@toggle');
    });

    // Route::post('checkout', 'CheckoutController@store');
    Route::post('checkout', [CheckoutController::class, 'store']);
    Route::post('checkout/preview', 'CheckoutController@show');
    Route::post('payments/{payment}/capture', 'PaymentController@capture');
    Route::get('orders', 'OrderController@index');           // list (minimal)
    Route::get('order/{order}', 'OrderController@show');    // detail (full)
    Route::patch('orders/{order}/cancel', 'OrderController@cancel'); // cancel

    //Blogs
    Route::get('blogCategories', 'BlogController@blogCategories');
    Route::get('blogs', 'BlogController@blogs');

    //Coupons
    Route::get('coupons', 'CouponController@coupons');
    Route::post('applyCoupon', 'CouponController@applyCoupon');
});
//Faqs
Route::get('faqs', 'HomeController@faqs');
//setting
// Route::get('pages', 'SettingController@pages');
// Route::get('settings', 'SettingController@settings');
Route::get('sitesettings', 'HomeController@sitesettings');
// Route::get('states', 'SettingController@states');
// Route::get('cities', 'SettingController@cities');

Route::any('/webhook/delhivery', [WebhookController::class, 'delhivery']);
Route::any('/webhook/dtdc', [WebhookController::class, 'dtdcWebhook']);
