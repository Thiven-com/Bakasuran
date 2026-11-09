<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\FreightCharge;
use App\Models\ShippingZone;
use Illuminate\Support\Facades\Auth;
use App\Models\WishlistItem;
use App\Models\Order;
use App\Models\Testimonial;
use App\Models\Restaurant;
use App\Models\RestaurantTable;
use App\Models\RestaurantTableBooking;
use App\Models\RestaurantBookingSetting;
use App\Models\RestaurantBookingHour;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use App\Models\RestaurantBookingSession;
use App\Models\RestaurantType;

class PageControllers extends Controller
{
    //

    public function home()
    {
        $now = Carbon::now();

        $banners = Banner::where('active', true)
            ->where(function ($query) use ($now) {
                $query->whereNull('starts_at')
                    ->orWhere('starts_at', '<=', $now);
            })
            ->where(function ($query) use ($now) {
                $query->whereNull('ends_at')
                    ->orWhere('ends_at', '>=', $now);
            })
            ->orderBy('sort_order', 'asc')
            ->get();

        $categories = Category::where('status', 'show')
            ->orderBy('id', 'asc')
            ->take(8)
            ->get();

        $products = Product::with([
            'category',
            'variant',
            'restaurant',
        ])
            ->where('status', 'show')
            ->whereHas('variant')
            ->orderBy('orders', 'desc')
            ->take(10)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Wishlist
        |--------------------------------------------------------------------------
        */

        $wishlistVariantIds = [];

        if (Auth::guard('customer')->check()) {
            $userId = Auth::guard('customer')->id();

            $wishlistVariantIds = WishlistItem::where('user_id', $userId)
                ->pluck('product_variant_id')
                ->toArray();
        }

        /*
       /*
    |--------------------------------------------------------------------------
    | Approved Testimonials
    |--------------------------------------------------------------------------
    */

        $testimonials = Testimonial::where('status', 'approved')
            ->latest()
            ->get();

        $testimonialCarousel = $testimonials->map(function ($testimonial) {
            return [
                'text' => $testimonial->description,
                'name' => $testimonial->name,
                'location' => $testimonial->city ?? '',
                'rating' => (int) $testimonial->rating,
            ];
        })->values();

        return view('website.home', compact(
            'banners',
            'categories',
            'products',
            'wishlistVariantIds',
            'testimonials',
            'testimonialCarousel'
        ));
    }


    public function shop(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Categories
        |--------------------------------------------------------------------------
        */

        $categories = Category::where('status', 'show')
            ->whereNull('deleted_at')
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Products Query
        |--------------------------------------------------------------------------
        */

        $productsQuery = Product::with([
            'category',
            'variant',
            'restaurant',
        ])
            ->where('status', 'show')
            ->whereHas('variant');


        /*
        |--------------------------------------------------------------------------
        | Search Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $productsQuery->where(function ($query) use ($search) {

                $query->where('title', 'LIKE', "%{$search}%")
                    ->orWhereHas('category', function ($categoryQuery) use ($search) {

                        $categoryQuery->where('title', 'LIKE', "%{$search}%");

                    });

            });
        }


        /*
        |--------------------------------------------------------------------------
        | Category Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('category')) {

            $category = $request->category;

            $productsQuery->whereHas('category', function ($query) use ($category) {

                $query->where('slug', $category)
                    ->orWhere('id', $category);

            });
        }


        /*
        |--------------------------------------------------------------------------
        | Minimum Price
        |--------------------------------------------------------------------------
        */

        if ($request->filled('min_price')) {

            $minPrice = (float) $request->min_price;

            $productsQuery->whereHas('variant', function ($query) use ($minPrice) {

                $query->whereRaw(
                    'CAST(price AS DECIMAL(15,2)) >= ?',
                    [$minPrice]
                );

            });
        }


        /*
        |--------------------------------------------------------------------------
        | Maximum Price
        |--------------------------------------------------------------------------
        */

        if ($request->filled('max_price')) {

            $maxPrice = (float) $request->max_price;

            $productsQuery->whereHas('variant', function ($query) use ($maxPrice) {

                $query->whereRaw(
                    'CAST(price AS DECIMAL(15,2)) <= ?',
                    [$maxPrice]
                );

            });
        }


        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        */

        switch ($request->get('sort')) {

            case 'best-selling':

                $productsQuery
                    ->orderBy('orders', 'desc')
                    ->orderBy('id', 'desc');

                break;


            case 'price-low':

                $productsQuery->orderBy(
                    Product::select('price')
                        ->from('product_variants')
                        ->whereColumn(
                            'product_variants.product_id',
                            'products.id'
                        )
                        ->limit(1),
                    'asc'
                );

                break;


            case 'price-high':

                $productsQuery->orderBy(
                    Product::select('price')
                        ->from('product_variants')
                        ->whereColumn(
                            'product_variants.product_id',
                            'products.id'
                        )
                        ->limit(1),
                    'desc'
                );

                break;


            case 'new-arrivals':

                $productsQuery->orderBy('created_at', 'desc');

                break;


            default:

                $productsQuery
                    ->orderBy('is_feature', 'desc')
                    ->orderBy('orders', 'desc')
                    ->orderBy('id', 'desc');

                break;
        }


        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $products = $productsQuery
            ->paginate(12)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Category Product Counts
        |--------------------------------------------------------------------------
        */

        $categoryCounts = Product::where('status', 'show')
            ->whereHas('variant')
            ->selectRaw('category_id, COUNT(*) as total')
            ->groupBy('category_id')
            ->pluck('total', 'category_id');


        /*
|--------------------------------------------------------------------------
| Wishlist
|--------------------------------------------------------------------------
*/

        $wishlistVariantIds = [];

        if (Auth::guard('customer')->check()) {

            $userId = Auth::guard('customer')->id();

            $wishlistVariantIds = WishlistItem::where('user_id', $userId)
                ->pluck('product_variant_id')
                ->toArray();
        }


        /*
        |--------------------------------------------------------------------------
        | Return Shop Page
        |--------------------------------------------------------------------------
        */

        return view('website.shop', compact(
            'products',
            'categories',
            'categoryCounts',
            'wishlistVariantIds'
        ));
    }
    // public function product_details()
    // {
    //     return view('website.product-details');
    // }

    public function product_details($slug)
    {
        $product = Product::with([
            'category',
            'brand',
            'restaurant',


            // Product variants with dynamic attributes
            'variants.attributeMappings.attribute',
            'variants.attributeMappings.value',

            // Variant media
            'variants.media',

            // Product media
            'media',
            'primaryMedia',
        ])
            ->where('slug', $slug)
            ->where('status', 'show')
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Select first variant
        |--------------------------------------------------------------------------
        */

        $variant = $product->variant;

        /*
        |--------------------------------------------------------------------------
        | Price
        |--------------------------------------------------------------------------
        */

        $sellingPrice = $variant?->price;
        $actualPrice = $variant?->actual_price;

        /*
        |--------------------------------------------------------------------------
        | Discount
        |--------------------------------------------------------------------------
        */

        $discount = 0;

        if (
            is_numeric($actualPrice) &&
            is_numeric($sellingPrice) &&
            (float) $actualPrice > 0 &&
            (float) $actualPrice > (float) $sellingPrice
        ) {
            $discount = round(
                (
                    ((float) $actualPrice - (float) $sellingPrice)
                    / (float) $actualPrice
                ) * 100
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Product Image
        |--------------------------------------------------------------------------
        */

        $productImage = null;

        if ($variant && !empty($variant->image)) {

            $productImage = $variant->image;

        } elseif ($product->primaryMedia) {

            $productImage = $product->primaryMedia->file_path ?? null;

        } elseif (!empty($product->image)) {

            $productImage = $product->image;
        }

        /*
        |--------------------------------------------------------------------------
        | Badge
        |--------------------------------------------------------------------------
        */

        $badge = null;

        if ($product->orders > 0) {

            $badge = 'Bestseller';

        } elseif (
            $product->created_at &&
            $product->created_at->gt(now()->subDays(30))
        ) {

            $badge = 'New Arrival';

        } elseif ($product->is_feature === 'yes') {

            $badge = 'Featured';
        }

        /*
|--------------------------------------------------------------------------
| Wishlist
|--------------------------------------------------------------------------
*/

        $wishlistVariantIds = [];

        if (Auth::guard('customer')->check()) {

            $userId = Auth::guard('customer')->id();

            $wishlistVariantIds = WishlistItem::where('user_id', $userId)
                ->whereIn(
                    'product_variant_id',
                    $product->variants->pluck('id')
                )
                ->pluck('product_variant_id')
                ->toArray();
        }

        /*
        |--------------------------------------------------------------------------
        | Return Product Details Page
        |--------------------------------------------------------------------------
        */

        return view('website.product-details', compact(
            'product',
            'variant',
            'sellingPrice',
            'actualPrice',
            'discount',
            'productImage',
            'badge',
            'wishlistVariantIds'
        ));
    }

    public function blog()
    {
        $blogs = Blog::with(['category', 'restaurant'])
            ->where('status', 'show')
            ->orderBy('created_at', 'desc')
            ->get();

        $blogCategories = BlogCategory::orderBy('id', 'asc')
            ->get()
            ->keyBy('id');

        return view('website.blog', compact('blogs', 'blogCategories'));
    }


    public function blog_details($slug)
    {
        $blog = Blog::with(['category', 'restaurant'])
            ->where('slug', $slug)
            ->where('status', 'show')
            ->firstOrFail();

        $blogCategory = BlogCategory::where('id', $blog->category_id)
            ->first();

        $relatedBlogs = Blog::with(['category', 'restaurant'])
            ->where('status', 'show')
            ->where('id', '!=', $blog->id)
            ->where('category_id', $blog->category_id)
            ->inRandomOrder()
            ->take(3)
            ->get();

        // If there are less than 3 blogs in the same category,
        // get other latest blogs to complete the 3 cards.
        if ($relatedBlogs->count() < 3) {

            $remaining = 3 - $relatedBlogs->count();

            $additionalBlogs = Blog::with(['category', 'restaurant'])
                ->where('status', 'show')
                ->where('id', '!=', $blog->id)
                ->whereNotIn('id', $relatedBlogs->pluck('id'))
                ->inRandomOrder()
                ->take($remaining)
                ->get();

            $relatedBlogs = $relatedBlogs->concat($additionalBlogs);
        }

        $relatedCategories = BlogCategory::whereIn(
            'id',
            $relatedBlogs->pluck('category_id')->filter()->unique()
        )
            ->get()
            ->keyBy('id');

        return view('website.blogdetails', compact(
            'blog',
            'blogCategory',
            'relatedBlogs',
            'relatedCategories'
        ));
    }

    public function aboutus()
    {
        return view('website.aboutus');
    }
    public function login()
    {
        return view('website.login');
    }

    public function cart()
    {
        if (!Auth::guard('customer')->check()) {
            return redirect()
                ->route('login')
                ->with('error', 'Please login first.');
        }

        $customerId = Auth::guard('customer')->id();


        $cartItems = \App\Models\CartItem::with([
            'variant.product:id,title,slug,image,restaurant_id',
            'variant.product.restaurant:id,name',
            'variant.attributeValues.attribute'
        ])
            ->where('user_id', $customerId)
            ->latest()
            ->get();


        $subtotal = $cartItems->sum(function ($item) {
            return (float) $item->unit_price * (int) $item->quantity;
        });

        $actualTotal = $cartItems->sum(function ($item) {

            $actualPrice = $item->variant->actual_price
                ?? $item->unit_price;

            return (float) $actualPrice * (int) $item->quantity;
        });

        $discount = max(0, $actualTotal - $subtotal);

        $shipping = 0;

        $total = $subtotal + $shipping;

        $cartCount = $cartItems->sum('quantity');

        return view('website.cart', compact(
            'cartItems',
            'subtotal',
            'actualTotal',
            'discount',
            'shipping',
            'total',
            'cartCount'
        ));
    }

    public function wishlist()
    {
        if (!Auth::guard('customer')->check()) {
            return redirect()->route('login');
        }

        $userId = Auth::guard('customer')->id();

        $wishlistItems = WishlistItem::with([
            'variant' => function ($query) {
                $query->with([
                    'product.category',
                    'product.restaurant',
                    'category',
                    'media'
                ]);
            }
        ])
            ->where('user_id', $userId)
            ->latest()
            ->get();

        return view(
            'website.wishlist',
            compact('wishlistItems')
        );
    }

    public function contactus()
    {
        return view('website.contactus');
    }
    public function shippingdelivery()
    {
        return view('website.shippingdelivery');
    }
    public function returnexchange()
    {
        return view('website.returnexchange');
    }
    public function privacypolicy()
    {
        return view('website.privacypolicy');
    }
    public function terms()
    {
        return view('website.terms');
    }
    public function faq()
    {
        return view('website.faq');
    }
    public function track_order()
    {
        return view('website.track-order');
    }

    public function orders()
    {
        if (!Auth::guard('customer')->check()) {
            return redirect()
                ->route('login')
                ->with('error', 'Please login first.');
        }

        $customerId = Auth::guard('customer')->id();

        $orders = Order::with(['items', 'payments', 'shipments'])
            ->where('customer_id', $customerId)
            ->latest('created_at')
            ->get();

        return view('website.orders', compact('orders'));
    }

    public function order_details(Request $request)
    {
        if (!Auth::guard('customer')->check()) {
            return redirect()
                ->route('login')
                ->with('error', 'Please login first.');
        }

        $orderId = $request->query('order_id');

        $order = Order::with([
            'items',
            'payments',
            'shipments',
        ])
            ->where('customer_id', Auth::guard('customer')->id())
            ->where('id', $orderId)
            ->firstOrFail();

        return view('website.order-details', compact('order'));
    }
    public function addresses()
    {
        $customer = Auth::guard('customer')->user();

        $addresses = Address::where('customer_id', $customer->id)
            ->latest()
            ->get();
        return view('website.addresses', compact('addresses', 'customer'));
    }
    public function account_settings()
    {
        $customer = Auth::guard('customer')->user();

        $address = Address::where('customer_id', $customer->id)
            ->latest()
            ->first();
        return view('website.account-settings', compact('customer', 'address'));
    }
    public function account()
    {
        $customer = Auth::guard('customer')->user();
        if (!$customer) {
            return redirect()->route('login');
        }

        $address = Address::where('customer_id', $customer->id)
            ->latest()
            ->first();
        return view('website.account', compact('customer', 'address'));
    }




    public function offers()
    {
        $products = Product::with([
            'category',
            'primaryMedia',
            'variants.category',
            'restaurant',
        ])
            ->where('is_feature', 1)
            ->inRandomOrder()
            ->take(4)
            ->get();

        // Wishlist variant IDs for the logged-in customer
        $wishlistVariantIds = [];

        if (Auth::guard('customer')->check()) {
            $userId = Auth::guard('customer')->id();

            $wishlistVariantIds = WishlistItem::where('user_id', $userId)
                ->pluck('product_variant_id')
                ->toArray();
        }

        return view('website.offers', compact(
            'products',
            'wishlistVariantIds'
        ));
    }





    public function checkout(Request $request)
    {
        $user = auth('customer')->user();

        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Please log in to continue checkout.');
        }

        $mode = $request->query('mode');
        $isBuyNow = $mode === 'buy_now';
        $orderType = $request->query('order_type', 'order');

        /*
        |--------------------------------------------------------------------------
        | 1. LOAD BUY NOW ITEM OR NORMAL CART ITEMS
        |--------------------------------------------------------------------------
        */
        if ($isBuyNow) {
            $buyNow = session('buy_now');

            if (
                !$buyNow ||
                empty($buyNow['product_variant_id']) ||
                !isset($buyNow['quantity']) ||
                (int) $buyNow['quantity'] < 1
            ) {
                return redirect()->route('shop')
                    ->with('error', 'Please select a product to order.');
            }

            $variant = ProductVariant::with([
                'product.restaurant',
                'media',
                'attributeValues.attribute',
            ])->find($buyNow['product_variant_id']);

            if (!$variant || !$variant->product) {
                session()->forget('buy_now');

                return redirect()->route('shop')
                    ->with('error', 'Product not found.');
            }

            $quantity = (int) $buyNow['quantity'];

            if ($variant->status !== 'show') {
                return redirect()->route('shop')
                    ->with('error', 'This product is not available.');
            }

            if ($quantity > (int) $variant->stock) {
                return redirect()->route('shop')
                    ->with(
                        'error',
                        'Only ' . $variant->stock . ' items are available.'
                    );
            }

            if (
                $variant->product_min_order &&
                $quantity < (int) $variant->product_min_order
            ) {
                return redirect()->route('shop')
                    ->with('error', 'Minimum order quantity is ' .
                        $variant->product_min_order . '.');
            }

            if (
                $variant->product_max_order &&
                $quantity > (int) $variant->product_max_order
            ) {
                return redirect()->route('shop')
                    ->with('error', 'Maximum order quantity is ' .
                        $variant->product_max_order . '.');
            }

            // Temporary item for the existing checkout Blade template.
            // This does not insert anything into cart_items.
            $cartItems = collect([
                (object) [
                    'id' => null,
                    'user_id' => $user->id,
                    'product_variant_id' => $variant->id,
                    'variant' => $variant,
                    'quantity' => $quantity,
                    'unit_price' => $variant->price,
                    'is_buy_now' => true,
                ],
            ]);
        } else {
            $cartItems = CartItem::with([
                'variant.product.restaurant',
                'variant.media',
                'variant.attributeValues.attribute',
            ])
                ->where('user_id', $user->id)
                ->get();

            if ($cartItems->isEmpty()) {
                return redirect()->route('cart')
                    ->with('error', 'Your cart is empty.');
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 2. ADDRESSES
        |--------------------------------------------------------------------------
        */
        $addresses = Address::where('customer_id', $user->id)
            ->orderByDesc('default')
            ->orderByDesc('id')
            ->get();

        $defaultAddress = $addresses->first();

        /*
        |--------------------------------------------------------------------------
        | 3. SUBTOTAL AND WEIGHT
        |--------------------------------------------------------------------------
        */
        $subtotal = 0;
        $totalWeight = 0;

        foreach ($cartItems as $item) {
            $variant = $item->variant;

            $subtotal += (float) ($variant?->price ?? 0)
                * (int) $item->quantity;

            $totalWeight += (float) ($variant?->weight ?? 0)
                * (int) $item->quantity;
        }

        /*
        |--------------------------------------------------------------------------
        | 4. SHIPPING
        |--------------------------------------------------------------------------
        */
        $shipping = 0;

        if ($defaultAddress && $defaultAddress->state) {
            $zone = ShippingZone::whereJsonContains(
                'regions',
                $defaultAddress->state
            )->first();

            if ($zone && $zone->free_shipping !== 'yes') {
                $charge = FreightCharge::where(
                    'shipping_zone_id',
                    $zone->id
                )
                    ->where('min_weight', '<=', $totalWeight)
                    ->where('max_weight', '>=', $totalWeight)
                    ->first();

                if ($charge) {
                    $shipping = (float) $charge->charge;
                } else {
                    $latestCharge = FreightCharge::where(
                        'shipping_zone_id',
                        $zone->id
                    )->orderByDesc('id')->first();

                    if (
                        $latestCharge &&
                        $totalWeight > $latestCharge->max_weight
                    ) {
                        $shipping = (float) $latestCharge->charge + 30;
                    }
                }
            }
        }

        $shipping = round($shipping);

        /*
        |--------------------------------------------------------------------------
        | 5. COUPON
        |--------------------------------------------------------------------------
        */
        $discount = 0;
        $couponCode = trim((string) $request->query('code', ''));

        if ($couponCode !== '') {
            $coupon = Coupon::where('code', $couponCode)
                ->where('status', 'active')
                ->first();

            if (
                $coupon &&
                (
                    !$coupon->expiry_date ||
                    Carbon::parse($coupon->expiry_date)
                        ->endOfDay()->isFuture()
                ) &&
                (
                    !$coupon->minimum_purchase ||
                    $subtotal >= $coupon->minimum_purchase
                )
            ) {
                if ($coupon->type === 'fixed') {
                    $discount = min(
                        (float) $coupon->discount,
                        $subtotal
                    );
                } else {
                    $discount = round(
                        ($subtotal * (float) $coupon->discount) / 100
                    );
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 6. TOTAL
        |--------------------------------------------------------------------------
        */
        $total = max(0, $subtotal + $shipping - $discount);

        return view('website.checkout', compact(
            'cartItems',
            'addresses',
            'defaultAddress',
            'subtotal',
            'shipping',
            'discount',
            'total',
            'couponCode',
            'orderType',
            'mode',
            'isBuyNow'
        ));
    }



    public function restaurants(Request $request)
    {
        $restaurants = Restaurant::query()
            ->where('is_active', true);

        // Search by restaurant name, cuisine, or city.
        if ($request->filled('search')) {
            $search = trim($request->search);

            $restaurants->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('cuisine_type', 'LIKE', "%{$search}%")
                    ->orWhere('city', 'LIKE', "%{$search}%");
            });
        }

        $restaurants = $restaurants
            ->latest()
            ->paginate(12)
            ->withQueryString();
        $cuisines = Restaurant::whereNotNull('cuisine_type')
            ->where('cuisine_type', '!=', '')
            ->distinct()
            ->orderBy('cuisine_type')
            ->pluck('cuisine_type');

        $types = Restaurant::whereNotNull('type')
            ->where('type', '!=', '')
            ->distinct()
            ->pluck('type');
        $restauranttypes = RestaurantType::where('status', 1)
            ->orderBy('name', 'asc')
            ->get();

        return view('website.restaurants', compact('restaurants', 'cuisines', 'types', 'restauranttypes'));
    }




    public function restaurant_details($slug)
    {
        $restaurant = Restaurant::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $products = Product::with([
            'category',
            'variant',
            'restaurant',
            'primaryMedia',
            'media',
        ])
            ->where('restaurant_id', $restaurant->id)
            ->where('status', 'show')
            ->whereHas('variant')
            ->latest()
            ->get();

        $categories = $products
            ->filter(fn($product) => $product->category && !empty($product->category->title))
            ->groupBy(fn($product) => $product->category->title)
            ->map(fn($items, $title) => [
                'name' => $title,
                'count' => $items->count(),
                'icon' => '🍽️',
            ])
            ->values();

        return view('website.restaurant-details', compact(
            'restaurant',
            'products',
            'categories'
        ));
    }





    public function table_booking($slug)
    {
        $restaurant = Restaurant::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Restaurant booking settings configured by admin
        $settings = RestaurantBookingSetting::where(
            'restaurant_id',
            $restaurant->id
        )->first();

        // Weekly opening and closing hours
        $bookingHours = RestaurantBookingHour::where(
            'restaurant_id',
            $restaurant->id
        )
            ->orderBy('day_of_week')
            ->get()
            ->keyBy('day_of_week');

        // Active booking sessions configured by admin
        $bookingSessions = RestaurantBookingSession::where(
            'restaurant_id',
            $restaurant->id
        )
            ->where('is_active', true)
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get()
            ->groupBy('day_of_week');

        $restaurantName = $restaurant->name;

        return view(
            'website.table-booking.booking-details',
            compact(
                'slug',
                'restaurant',
                'restaurantName',
                'settings',
                'bookingHours',
                'bookingSessions'
            )
        );
    }







    public function checkAvailability(Request $request, $slug)
    {
        $restaurant = Restaurant::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // 1. Validate customer input
        $validated = $request->validate([
            'booking_date' => [
                'required',
                'date',
                'after_or_equal:today',
            ],
            'booking_time' => [
                'required',
                'date_format:H:i',
            ],
            'guests' => [
                'required',
                'integer',
                'min:1',
                'max:100',
            ],
            'seating' => [
                'required',
                'in:indoor,outdoor,family,private',
            ],
            'table_id' => [
                'nullable',
                'integer',
            ],
            'special_request' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        // 2. Get restaurant booking settings
        $settings = RestaurantBookingSetting::where(
            'restaurant_id',
            $restaurant->id
        )->first();

        if (!$settings || !$settings->is_booking_enabled) {
            return back()
                ->withErrors([
                    'booking_date' => 'Online table reservations are currently unavailable.',
                ])
                ->withInput();
        }

        // 3. Validate guest limits
        if (
            $validated['guests'] < $settings->min_guests ||
            $validated['guests'] > $settings->max_guests
        ) {
            return back()
                ->withErrors([
                    'guests' => "Please select between {$settings->min_guests} and {$settings->max_guests} guests.",
                ])
                ->withInput();
        }

        // 4. Validate booking advance limit
        $bookingDate = Carbon::parse($validated['booking_date'])
            ->startOfDay();

        $lastAllowedDate = today()->addDays(
            (int) $settings->max_advance_days
        );

        if ($bookingDate->gt($lastAllowedDate)) {
            return back()
                ->withErrors([
                    'booking_date' => 'Bookings are allowed only up to ' .
                        $settings->max_advance_days . ' days in advance.',
                ])
                ->withInput();
        }

        // 5. Validate booking duration settings
        $slotDuration = (int) $settings->slot_duration;
        $bookingDuration = (int) $settings->booking_duration;
        $cleaningDuration = (int) $settings->cleaning_duration;
        $minNoticeMinutes = (int) $settings->min_notice_minutes;

        if (
            $slotDuration <= 0 ||
            $bookingDuration <= 0 ||
            $cleaningDuration < 0 ||
            $minNoticeMinutes < 0
        ) {
            return back()
                ->withErrors([
                    'booking_time' => 'Restaurant booking settings are not configured correctly.',
                ])
                ->withInput();
        }

        // 6. Get restaurant opening hours
        $hours = RestaurantBookingHour::where(
            'restaurant_id',
            $restaurant->id
        )
            ->where('day_of_week', $bookingDate->dayOfWeek)
            ->first();

        if (
            !$hours ||
            $hours->is_closed ||
            !$hours->opening_time ||
            !$hours->closing_time
        ) {
            return back()
                ->withErrors([
                    'booking_date' => 'The restaurant is closed on your selected day.',
                ])
                ->withInput();
        }

        $bookingTime = Carbon::createFromFormat(
            'Y-m-d H:i',
            $validated['booking_date'] . ' ' . $validated['booking_time']
        );

        $openingTime = Carbon::parse(
            $validated['booking_date'] . ' ' . $hours->opening_time
        );

        $closingTime = Carbon::parse(
            $validated['booking_date'] . ' ' . $hours->closing_time
        );

        // 7. Calculate actual reservation end and cleaning buffer
        $endTime = $bookingTime->copy()->addMinutes($bookingDuration);

        $blockedUntil = $endTime->copy()->addMinutes($cleaningDuration);

        // 8. Validate minimum notice
        $minimumBookingTime = now()->addMinutes($minNoticeMinutes);

        if ($bookingTime->lt($minimumBookingTime)) {
            return back()
                ->withErrors([
                    'booking_time' => "Please book at least {$minNoticeMinutes} minutes in advance.",
                ])
                ->withInput();
        }

        // 9. Validate restaurant opening hours
        if (
            $bookingTime->lt($openingTime) ||
            $endTime->gt($closingTime)
        ) {
            return back()
                ->withErrors([
                    'booking_time' => 'The selected booking time is outside restaurant opening hours.',
                ])
                ->withInput();
        }

        // 10. Get active admin-configured sessions for selected weekday
        $bookingSessions = RestaurantBookingSession::where(
            'restaurant_id',
            $restaurant->id
        )
            ->where('day_of_week', $bookingDate->dayOfWeek)
            ->where('is_active', true)
            ->orderBy('start_time')
            ->get();

        if ($bookingSessions->isEmpty()) {
            return back()
                ->withErrors([
                    'booking_time' => 'No booking sessions are available on your selected day.',
                ])
                ->withInput();
        }

        // 11. Check whether the selected time belongs to an active session
        $matchingSession = $bookingSessions->first(function ($session) use ($validated, $bookingTime, $endTime, $slotDuration) {
            $sessionStart = Carbon::parse(
                $validated['booking_date'] . ' ' . $session->start_time
            );

            $sessionEnd = Carbon::parse(
                $validated['booking_date'] . ' ' . $session->end_time
            );

            // Booking must start inside the session
            // and finish before the session closes.
            if (
                $bookingTime->lt($sessionStart) ||
                $endTime->gt($sessionEnd)
            ) {
                return false;
            }

            // Booking start must match the session slot interval.
            $minutesFromSessionStart = $sessionStart->diffInMinutes(
                $bookingTime,
                false
            );

            return $minutesFromSessionStart >= 0 &&
                $minutesFromSessionStart % $slotDuration === 0;
        });

        if (!$matchingSession) {
            return back()
                ->withErrors([
                    'booking_time' => 'Please select a valid time within the restaurant booking sessions.',
                ])
                ->withInput();
        }

        // 12. Get active tables matching seating and guest capacity
        $tables = RestaurantTable::where(
            'restaurant_id',
            $restaurant->id
        )
            ->where('status', 1)
            ->where('seating_type', $validated['seating'])
            ->where('seating_capacity', '>=', $validated['guests'])
            ->get();

        // 13. Check existing reservations and cleaning buffers
        $availableTables = $tables->filter(function ($table) use ($validated, $bookingTime, $blockedUntil, $bookingDuration, $cleaningDuration) {
            $existingBookings = RestaurantTableBooking::where(
                'restaurant_table_id',
                $table->id
            )
                ->whereDate(
                    'booking_date',
                    $validated['booking_date']
                )
                ->whereIn('status', ['pending', 'confirmed'])
                ->get();

            foreach ($existingBookings as $booking) {
                $existingStart = Carbon::parse(
                    $validated['booking_date'] . ' ' . $booking->booking_time
                );

                // end_time represents the existing reservation's actual end.
                // Use booking_duration as fallback for older records.
                $existingEnd = $booking->end_time
                    ? Carbon::parse(
                        $validated['booking_date'] . ' ' . $booking->end_time
                    )
                    : $existingStart->copy()->addMinutes($bookingDuration);

                // Include the cleaning buffer after the existing reservation.
                $existingBlockedUntil = $existingEnd->copy()
                    ->addMinutes($cleaningDuration);

                // Check overlapping blocked intervals.
                if (
                    $existingStart->lt($blockedUntil) &&
                    $existingBlockedUntil->gt($bookingTime)
                ) {
                    return false;
                }
            }

            return true;
        })->values();

        // 14. Verify previously selected table, if supplied
        if ($request->filled('table_id')) {
            $selectedTable = $availableTables->firstWhere(
                'id',
                (int) $validated['table_id']
            );

            if (!$selectedTable) {
                return back()
                    ->withErrors([
                        'table_id' => 'This table is no longer available. Please select another table.',
                    ])
                    ->withInput();
            }

            $availableTables = collect([$selectedTable]);
        }

        // 15. Check whether any tables are available
        if ($availableTables->isEmpty()) {
            return back()
                ->withErrors([
                    'table_id' => 'No tables are available for your selected date, time and seating preference.',
                ])
                ->withInput();
        }

        // 16. Store booking information in session

        session([
            'availability_checked' => true,
            'booking_slug' => $slug,
            'booking_details' => $validated,
            'available_table_ids' => $availableTables->pluck('id')->all(),
        ]);

        // Redirect to Step 2: Select Table

        return redirect()->route('restaurant.select-table', [
            'slug' => $slug,
        ]);
    }


    public function selectTablePage($slug)
    {
        $restaurant = Restaurant::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Verify that availability was checked for this restaurant.
        if (
            !session('availability_checked') ||
            session('booking_slug') !== $slug ||
            !is_array(session('booking_details'))
        ) {
            return redirect()
                ->route('restaurant.table-booking', ['slug' => $slug])
                ->withErrors([
                    'booking_date' => 'Please enter your booking details first.',
                ]);
        }

        $bookingDetails = session('booking_details');

        $tableIds = collect(session('available_table_ids', []))
            ->map(fn($id) => (int) $id)
            ->all();

        if (empty($tableIds)) {
            return redirect()
                ->route('restaurant.table-booking', ['slug' => $slug])
                ->withErrors([
                    'table_id' => 'No available tables found. Please check availability again.',
                ]);
        }

        // Load only tables previously returned as available.
        $availableTables = RestaurantTable::where(
            'restaurant_id',
            $restaurant->id
        )
            ->whereIn('id', $tableIds)
            ->where('status', 1)
            ->where('seating_type', $bookingDetails['seating'])
            ->where('seating_capacity', '>=', $bookingDetails['guests'])
            ->get();

        if ($availableTables->isEmpty()) {
            return redirect()
                ->route('restaurant.table-booking', ['slug' => $slug])
                ->withErrors([
                    'table_id' => 'Available tables have changed. Please check again.',
                ]);
        }

        $restaurantName = $restaurant->name;

        return view(
            'website.table-booking.select-table',
            compact(
                'slug',
                'restaurant',
                'restaurantName',
                'bookingDetails',
                'availableTables'
            )
        );
    }


    public function confirmTableBooking(Request $request, $slug)
    {
        $restaurant = Restaurant::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // 1. Validate customer and booking details
        $validated = $request->validate([
            'booking_date' => ['required', 'date', 'after_or_equal:today'],
            'booking_time' => ['required', 'date_format:H:i'],
            'guests' => ['required', 'integer', 'min:1', 'max:100'],
            'seating' => ['required', 'in:indoor,outdoor,family,private'],
            'table_id' => ['required', 'integer'],
            'customer_name' => ['required', 'string', 'max:150'],
            'customer_mobile' => ['required', 'string', 'max:20'],
            'customer_email' => ['nullable', 'email', 'max:150'],
            'special_request' => ['nullable', 'string', 'max:1000'],
        ]);

        // 2. Get restaurant booking settings
        $settings = RestaurantBookingSetting::where(
            'restaurant_id',
            $restaurant->id
        )->first();

        if (!$settings || !$settings->is_booking_enabled) {
            return back()->withErrors([
                'booking_date' => 'Table booking is currently unavailable.',
            ])->withInput();
        }

        // 3. Validate guest limits
        if (
            $validated['guests'] < $settings->min_guests ||
            $validated['guests'] > $settings->max_guests
        ) {
            return back()->withErrors([
                'guests' => "Please select between {$settings->min_guests} and {$settings->max_guests} guests.",
            ])->withInput();
        }

        // 4. Validate advance booking limit
        $bookingDate = Carbon::parse($validated['booking_date'])->startOfDay();

        $lastAllowedDate = today()->addDays(
            (int) $settings->max_advance_days
        );

        if ($bookingDate->gt($lastAllowedDate)) {
            return back()->withErrors([
                'booking_date' => 'Selected date exceeds the advance booking limit.',
            ])->withInput();
        }

        // 5. Validate booking settings
        $slotDuration = (int) $settings->slot_duration;
        $bookingDuration = (int) $settings->booking_duration;
        $cleaningDuration = (int) $settings->cleaning_duration;
        $minNoticeMinutes = (int) $settings->min_notice_minutes;

        if (
            $slotDuration <= 0 ||
            $bookingDuration <= 0 ||
            $cleaningDuration < 0 ||
            $minNoticeMinutes < 0
        ) {
            return back()->withErrors([
                'booking_time' => 'Restaurant booking settings are not configured correctly.',
            ])->withInput();
        }

        // 6. Get restaurant opening hours
        $hours = RestaurantBookingHour::where(
            'restaurant_id',
            $restaurant->id
        )
            ->where('day_of_week', $bookingDate->dayOfWeek)
            ->first();

        if (
            !$hours ||
            $hours->is_closed ||
            !$hours->opening_time ||
            !$hours->closing_time
        ) {
            return back()->withErrors([
                'booking_date' => 'Restaurant is closed on this date.',
            ])->withInput();
        }

        $bookingTime = Carbon::createFromFormat(
            'Y-m-d H:i',
            $validated['booking_date'] . ' ' . $validated['booking_time']
        );

        $openingTime = Carbon::parse(
            $validated['booking_date'] . ' ' . $hours->opening_time
        );

        $closingTime = Carbon::parse(
            $validated['booking_date'] . ' ' . $hours->closing_time
        );

        // 7. Calculate reservation end and cleaning buffer
        $endTime = $bookingTime->copy()->addMinutes($bookingDuration);

        $blockedUntil = $endTime->copy()->addMinutes($cleaningDuration);

        // 8. Validate minimum booking notice
        $minimumBookingTime = now()->addMinutes($minNoticeMinutes);

        if ($bookingTime->lt($minimumBookingTime)) {
            return back()->withErrors([
                'booking_time' => "Please book at least {$minNoticeMinutes} minutes in advance.",
            ])->withInput();
        }

        // 9. Validate restaurant opening hours
        if (
            $bookingTime->lt($openingTime) ||
            $endTime->gt($closingTime)
        ) {
            return back()->withErrors([
                'booking_time' => 'Selected booking time is outside restaurant opening hours.',
            ])->withInput();
        }

        // 10. Get active admin-configured sessions
        $bookingSessions = RestaurantBookingSession::where(
            'restaurant_id',
            $restaurant->id
        )
            ->where('day_of_week', $bookingDate->dayOfWeek)
            ->where('is_active', true)
            ->orderBy('start_time')
            ->get();

        if ($bookingSessions->isEmpty()) {
            return back()->withErrors([
                'booking_time' => 'No booking sessions are available on this date.',
            ])->withInput();
        }

        // 11. Verify selected time belongs to an active session
        $matchingSession = $bookingSessions->first(function ($session) use ($validated, $bookingTime, $endTime, $slotDuration) {
            $sessionStart = Carbon::parse(
                $validated['booking_date'] . ' ' . $session->start_time
            );

            $sessionEnd = Carbon::parse(
                $validated['booking_date'] . ' ' . $session->end_time
            );

            if (
                $bookingTime->lt($sessionStart) ||
                $endTime->gt($sessionEnd)
            ) {
                return false;
            }

            $minutesFromSessionStart = $sessionStart->diffInMinutes(
                $bookingTime,
                false
            );

            return $minutesFromSessionStart >= 0 &&
                $minutesFromSessionStart % $slotDuration === 0;
        });

        if (!$matchingSession) {
            return back()->withErrors([
                'booking_time' => 'Please select a valid time within an active restaurant booking session.',
            ])->withInput();
        }

        // 12. Recheck the selected table inside a database transaction
        $booking = DB::transaction(function () use ($validated, $restaurant, $bookingTime, $endTime, $blockedUntil, $bookingDuration, $cleaningDuration) {
            $table = RestaurantTable::where(
                'id',
                $validated['table_id']
            )
                ->where('restaurant_id', $restaurant->id)
                ->where('status', 1)
                ->lockForUpdate()
                ->first();

            if (
                !$table ||
                $table->seating_type !== $validated['seating'] ||
                $table->seating_capacity < $validated['guests']
            ) {
                throw ValidationException::withMessages([
                    'table_id' => 'The selected table is no longer available. Please select another table.',
                ]);
            }

            // 13. Check existing bookings for this table
            $existingBookings = RestaurantTableBooking::where(
                'restaurant_table_id',
                $table->id
            )
                ->whereDate('booking_date', $validated['booking_date'])
                ->whereIn('status', ['pending', 'confirmed'])
                ->get();

            foreach ($existingBookings as $existing) {
                $existingStart = Carbon::parse(
                    $validated['booking_date'] . ' ' . $existing->booking_time
                );

                $existingEnd = $existing->end_time
                    ? Carbon::parse(
                        $validated['booking_date'] . ' ' . $existing->end_time
                    )
                    : $existingStart->copy()->addMinutes($bookingDuration);

                $existingBlockedUntil = $existingEnd->copy()
                    ->addMinutes($cleaningDuration);

                if (
                    $existingStart->lt($blockedUntil) &&
                    $existingBlockedUntil->gt($bookingTime)
                ) {
                    throw ValidationException::withMessages([
                        'table_id' => 'This table has just been booked or is within its cleaning period. Please select another table.',
                    ]);
                }
            }

            // 14. Create reservation
            return RestaurantTableBooking::create([
                'booking_number' => 'BK' .
                    now()->format('ymd') .
                    strtoupper(\Illuminate\Support\Str::random(6)),

                'restaurant_id' => $restaurant->id,
                'restaurant_table_id' => $table->id,
                'customer_id' => Auth::guard('customer')->id(),

                'customer_name' => $validated['customer_name'],
                'customer_mobile' => $validated['customer_mobile'],
                'customer_email' => $validated['customer_email'] ?? null,

                'booking_date' => $validated['booking_date'],
                'booking_time' => $bookingTime->format('H:i:s'),
                'end_time' => $endTime->format('H:i:s'),

                'guest_count' => $validated['guests'],
                'seating_type' => $validated['seating'],

                'special_requests' => $validated['special_request'] ?? null,

                'status' => 'pending',
            ]);
        });

        // 15. Clear previous availability data
        session()->forget([
            'availability_checked',
            'booking_slug',
            'booking_details',
            'available_table_ids',
        ]);

        // 16. Redirect after successful booking
        return redirect()
            ->route('restaurant.table-booking', ['slug' => $slug])
            ->with(
                'success',
                'Your table reservation has been submitted successfully. Booking ID: ' .
                $booking->booking_number
            );
    }


    public function confirmBookingPage($slug)
    {
        $restaurant = Restaurant::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $bookingDetails = session('booking_details');
        $availableTableIds = session('available_table_ids', []);

        if (
            !session('availability_checked') ||
            session('booking_slug') !== $slug ||
            !is_array($bookingDetails) ||
            empty($bookingDetails['table_id'])
        ) {
            return redirect()
                ->route('restaurant.table-booking', ['slug' => $slug])
                ->with('error', 'Please select your booking details and table first.');
        }

        $selectedTable = RestaurantTable::where('restaurant_id', $restaurant->id)
            ->whereIn('id', $availableTableIds)
            ->where('id', $bookingDetails['table_id'])
            ->where('status', 1)
            ->first();

        if (!$selectedTable) {
            return redirect()
                ->route('restaurant.select-table', ['slug' => $slug])
                ->with('error', 'Your selected table is no longer available.');
        }

        return view('website.table-booking.booking-confirmation', [
            'slug' => $slug,
            'restaurant' => $restaurant,
            'restaurantName' => $restaurant->name,
            'bookingDetails' => $bookingDetails,
            'selectedTable' => $selectedTable,
        ]);
    }



    public function myTableBookings()
    {
        $customer = Auth::guard('customer')->user();

        $bookings = RestaurantTableBooking::with([
            'restaurant',
            'restaurantTable',
        ])
            ->where('customer_id', $customer->id)
            ->latest()
            ->paginate(10);

        return view('website.my-table-bookings', compact('bookings'));
    }



}