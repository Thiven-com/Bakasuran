<?php

use App\Http\Controllers\CustomerApp\CheckoutController;
use App\Http\Controllers\CustomerApp\ProductController;
use App\Http\Controllers\CustomerApp\ProductRatingController;
use App\Http\Controllers\CustomerApp\ProductVariantController;
use App\Http\Controllers\CustomerApp\RestaurantController;
use App\Http\Controllers\CustomerApp\RestaurantFoodOrderController;
use App\Http\Controllers\CustomerApp\RestaurantReviewController;
use App\Http\Controllers\CustomerApp\RestaurantTableBookingController;
use App\Http\Controllers\CustomerApp\SiteSettingController;
use App\Http\Controllers\CustomerApp\TableBookingController;
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
Route::get('product/{id}', [ProductController::class, 'productDetails']);
Route::get('states', 'AddressController@states');

Route::any('webhook/razorpay/capture', 'WebhookController@razorpay');

Route::group(['middelware' => 'auth:sanctum'], function () {
    // Route::group(['middleware' => 'verification'], function () {
    Route::post('logout', 'AccountController@logout');
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

    //Site Settings
    Route::get('/site-settings', [SiteSettingController::class, 'index']);

    //RestaurantTableBooking

    // Get food menu by table
    Route::get('/menu/{tableId}', [
        RestaurantFoodOrderController::class,
        'menu'
    ]);
    Route::get('/restaurant/{restaurantId}/menu', [RestaurantFoodOrderController::class, 'restaurantMenu']);

    // Place food order
    Route::post('/order', [
        RestaurantFoodOrderController::class,
        'store'
    ]);

    // Customer order list
    Route::get('/orders', [
        RestaurantFoodOrderController::class,
        'index'
    ]);

    // Order details
    Route::get('/orders/{id}', [
        RestaurantFoodOrderController::class,
        'show'
    ]);

    // Update products / quantities
    Route::put('/orders/{id}/items', [
        RestaurantFoodOrderController::class,
        'updateItems'
    ]);

    // Cancel order
    Route::post('/orders/{id}/cancel', [
        RestaurantFoodOrderController::class,
        'cancel'
    ]);

    // Add restaurant review
    Route::post('restaurant-review', [RestaurantReviewController::class, 'store']);

    // Get restaurant reviews
    Route::get('restaurant/{restaurant_id}/reviews', [RestaurantReviewController::class, 'index']);

    // View single review
    Route::get('restaurant-review/{id}', [RestaurantReviewController::class, 'show']);

    // Add product rating
    Route::post('product-rating', [ProductRatingController::class, 'store']);

    // Get product ratings
    Route::get('product/{product_id}/ratings', [ProductRatingController::class, 'index']);

    // View single rating
    Route::get('product-rating/{id}', [ProductRatingController::class, 'show']);

    // Update rating
    Route::put('product-rating/{id}', [ProductRatingController::class, 'update']);

    // Delete rating
    Route::delete('product-rating/{id}', [ProductRatingController::class, 'destroy']);

    // Step 3: Check available tables for the selected date/time
    Route::get(
        'restaurant-available-tables',
        [RestaurantTableBookingController::class, 'availableTables']
    );
    Route::post('restaurant-table-bookings', [
        RestaurantTableBookingController::class,
        'store'
    ]);

    Route::get('restaurant-table-bookings', [
        RestaurantTableBookingController::class,
        'index'
    ]);

    Route::get('restaurant-table-bookings/{id}', [
        RestaurantTableBookingController::class,
        'show'
    ]);

    Route::get(
        'restaurant-tables',
        [RestaurantTableBookingController::class, 'tables']
    );

    Route::get('restaurant-categories', [
        ProductVariantController::class,
        'categories'
    ]);

    Route::get('categories/{categoryId}/restaurants', [
        ProductVariantController::class,
        'restaurantsByCategory'
    ]);

    Route::get('restaurant-category-products', [
        ProductVariantController::class,
        'productsByCategoryAndRestaurant'
    ]);

    Route::get(
        'products-by-category-and-restaurant',
        [ProductVariantController::class, 'productsByCategoryAndRestaurant']
    );


    Route::get('locations', 'AddressController@index');

    Route::post('location/store', 'AddressController@store');
    Route::get('my_locations', 'ProfileController@my_locations');
    Route::get('location/{id}/delete', 'AddressController@destroy');
    Route::any('firebase_token', 'ProfileController@firebase_token');

    Route::get('restaurants', [RestaurantController::class, 'index']);
    Route::get('restaurants/{id}', [RestaurantController::class, 'show']);
    Route::post(
        'restaurant-table-bookings/{id}/cancel',
        [RestaurantTableBookingController::class, 'cancel']
    );

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
