<?php

use App\Http\Controllers\Admin\AbandonedCartController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\ManualOrderController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\admin\ProductController as AdminProductController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// Route::get('index', [CustomAuthController::class, 'dashboard']);
// Route::get('signin', [CustomAuthController::class, 'index'])->name('signin');
// Route::post('custom-login', [CustomAuthController::class, 'customSignin'])->name('signin.custom');
// Route::get('register', [CustomAuthController::class, 'registration'])->name('register');
// Route::post('custom-register', [CustomAuthController::class, 'customRegister'])->name('register.custom');
// Route::get('signout', [CustomAuthController::class, 'signOut'])->name('signout');

Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});


Route::get('login', "AuthController@showLoginForm")->name('admin.login');
Route::post('login', "AuthController@login")->name('admin.loginAction');
Route::post('logout', "AuthController@logout")->name('admin.logout');

Route::get('logout', "AuthController@logout")->name('admin.logout');

Route::group(['middleware' => 'admin'], function () {

    Route::get('dashboard', "AuthController@dashboard")->name('admin.dashboard');
    Route::resource('sellers', SellerController::class);
    Route::resource('categories', CategoryController::class)->names('admin.categories');
    Route::resource('customers', CustomerController::class)->names('admin.customers');
    ;
    Route::resource('units', UnitController::class)->names('admin.units');
    Route::resource('attributes', AttributeController::class)->names('admin.attributes');
    Route::resource('brands', BrandController::class)->names('admin.brands');
    Route::resource('banners', BannerController::class)->names('admin.banners');
    Route::resource('products', ProductController::class)->names('admin.products');
    // Route::resource('coupons', CouponController::class)->names('admin.coupons');
    Route::resource('orders', OrderController::class)->names('admin.orders');
    Route::resource('flash-sales', FlashSaleController::class)->names('admin.flash-sales');
    Route::delete('product-media/{id}', 'ProductController@delete_media')->name('admin.product-media.destroy');
    Route::get('settings/company', 'SiteSettingController@site')->name('admin.settings.company');
    Route::post('setting/company/update', 'SiteSettingController@company_setting_update')->name('admin.settings.company.update');

    //preorders
    Route::get('preorders', [OrderController::class, 'preorders'])->name('admin.preorders');
    Route::get('manualorders', [OrderController::class, 'manualorders'])->name('admin.manualorders');

    Route::post('/admin/orders/status', [OrderController::class, 'updateStatus'])
        ->name('admin.orders.updateStatus');
    Route::post('/admin/orders/update-order', [OrderController::class, 'updateOrder'])
        ->name('admin.orders.updateOrder');
    Route::post('/admin/orders/update-address', [OrderController::class, 'updateAddress'])
        ->name('admin.orders.updateAddress');
    //Blogs
    Route::resource('blogs', BlogController::class)->names('admin.blogs');
    Route::resource('blog/categories', BlogCategoryController::class)->names('admin.blog.categories');

    //faqs
    Route::resource('faqs', FaqController::class)->names('admin.faqs');

    Route::get('order/createParcel/delhivery', 'OrderController@createDelhiveryMPS')->name('admin.sales.createParcel.delhivery');
    Route::get('order/createParcel/dtdc', 'OrderController@createDTDCShipment')->name('admin.sales.createParcel.dtdc');
    Route::get('order/cancelDTDCShipment/{id}', 'OrderController@cancelDTDCShipment')->name('admin.sales.cancelDTDCShipment');

    Route::get(
        '/orders/{order}/dtdc/track',
        [OrderController::class, 'trackDTDCShipment']
    )->name('orders.dtdc.track');

    Route::get(
        '/orders/{order}/dtdc/label',
        [OrderController::class, 'downloadDTDCLabel']
    )->name('orders.dtdc.label');

    Route::get('abandonedCart', 'AbandonedCartController@abandonedCart')->name('admin.abandonedCart');
    Route::get('viewAbandonedCart/{id}', 'AbandonedCartController@viewAbandonedCart')->name('admin.viewAbandonedCart');
    Route::get('/abandoned-cart/send-mail/{id}', 'AbandonedCartController@sendMail')->name('admin.sendAbandonedCartMail');
    Route::post('/abandoned-cart/bulk-mail', [AbandonedCartController::class, 'sendSelectedMail'])
        ->name('admin.sendSelectedAbandonedCartMail');

    Route::get('todayDeals', "ProductController@todayDeals")->name('admin.todayDeals');
    Route::post('updateTodayDeal', "ProductController@updateTodayDeal")->name('admin.updateTodayDeal');
    Route::post('updateTodayDeal', "ProductController@updateTodayDeal")->name('admin.updateTodayDeal');
    Route::post('/admin/today-sale/remove/{id}', [ProductController::class, 'deleteTodaySale'])
        ->name('admin.deleteTodaySale');

    Route::get('/orders/{id}/print', "OrderController@print")->name('admin.orders.print');
    Route::get('/orders/{order}/download', 'OrderController@downloadInvoice')->name('admin.orders.download');

    //Sections
    Route::resource('sections', 'SectionController')->names('admin.sections');
    //Home Offer
    Route::resource('homeoffers', 'HomeOfferController')->names('admin.homeoffers');

    //Export
    Route::get('ordersExport', [OrderController::class, 'export'])
        ->name('admin.orders.export');

    Route::get('/admin/reports/orders/today', [OrderController::class, 'todayOrdersExport'])
        ->name('admin.reports.orders.today');
    Route::get('/admin/reports/transactions/today', [OrderController::class, 'todayTransactionsExport'])
        ->name('admin.reports.transactions.today');

    Route::resource('staffs', "StaffController")->names('admin.staffs');

    Route::post('/add-value', [AttributeController::class, 'addValue']);
    Route::get('/createOrder', [ManualOrderController::class, 'createOrder'])->name('admin.createOrder');

    Route::post('manualCart', [ManualOrderController::class, 'addToCart'])->name('admin.manualCart');
    Route::get('fetchCart', [ManualOrderController::class, 'fetchCart'])->name('admin.fetchCart');

    Route::get('/get-customer-addresses', [ManualOrderController::class, 'getCustomerAddresses'])->name('admin.getCustomerAddresses');
    Route::get('/clearSession', [ManualOrderController::class, 'clearSession'])->name('admin.clearSession');
    Route::post('addCustomer', [ManualOrderController::class, 'addCustomer'])->name('admin.addCustomer');
    Route::post('addAddress', [ManualOrderController::class, 'addAddress'])->name('admin.addAddress');
    Route::post('manualCheckout', [ManualOrderController::class, 'manualCheckout'])->name('admin.manualCheckout');

    Route::post('/orders/add-note', [OrderController::class, 'addNote'])
        ->name('admin.orders.addNote');

    Route::delete('/orders/delete-note/{id}', [OrderController::class, 'deleteNote'])
        ->name('admin.orders.deleteNote');


    Route::post('/orders/import-awb-csv', [OrderController::class, 'importAwbCsv'])
        ->name('admin.orders.importAwbCsv');

    Route::get('/update-orders/upload-awb', [OrderController::class, 'uploadAwbPage'])
        ->name('admin.orders.uploadAwbPage');
    //variants
    Route::post('delete-variant-video', [ProductController::class, 'deleteVideo']);
    //shippingZones
    Route::resource('shippingZones', 'ShippingZoneController')->names('admin.shippingZones');

    Route::resource('coupons', CouponController::class)->names('admin.coupons');

    Route::post('bulkProductUpdate', [ProductController::class, 'bulkProductUpdate'])->name('admin.bulkProductUpdate');

    Route::post('/orders/create-bulk-parcel', [OrderController::class, 'createBulkParcel'])
        ->name('admin.orders.createBulkParcel');
    Route::post('/orders/createBulkDTDCParcel', [OrderController::class, 'createBulkDTDCParcel'])
        ->name('admin.orders.createBulkDTDCParcel');

    Route::any('/refresh-orders/awb-status', [OrderController::class, 'refreshAwbStatus'])
        ->name('admin.orders.refresh-awb-status');

    Route::post('/products/bulk-status', [ProductController::class, 'bulkStatus'])
        ->name('admin.products.bulk.status');

    Route::get('activity-logs', [ActivityLogController::class, 'index'])
        ->name('admin.activity.logs');

    Route::get('activity-logs/{id}', [ActivityLogController::class, 'show'])
        ->name('admin.activity.logs.show');


    Route::get('/sku-report', [OrderController::class, 'skuReport'])
        ->name('admin.sku.report');
    Route::get('/sku-report/export', [OrderController::class, 'exportSkuReport'])
        ->name('admin.sku.export');

    Route::get('db-backup', [AuthController::class, 'backupDatabase'])->name('admin.backupDatabase');

    Route::get('/queue-work', [AuthController::class, 'queueWork']);
    Route::get('/queue-restart', [AuthController::class, 'queueRestart']);

});
Route::get('products/get-attribute-values/{unitId}', [ProductController::class, 'getAttributeValues'])->name('products.getAttributeValues');
Route::get('/get-subcategories/{id}', function ($id) {
    $subcategories = \App\Models\Category::where('parent_id', $id)->get();
    return response()->json($subcategories);
});

Route::get('forgot-password', [AuthController::class, 'showForgotForm'])->name('admin.password.request');

Route::post('send-otp', [AuthController::class, 'sendOtp'])->name('admin.password.sendOtp');

Route::get('verify-otp', [AuthController::class, 'showVerifyForm'])->name('admin.password.verifyForm');

Route::post('verify-otp', [AuthController::class, 'verifyOtp'])->name('admin.password.verifyOtp');

Route::post('reset-password-otp', [AuthController::class, 'resetPassword'])->name('admin.password.resetOtp');


Route::get('updateOrders', [AuthController::class, 'updateOrders'])->name('admin.updateOrders');
