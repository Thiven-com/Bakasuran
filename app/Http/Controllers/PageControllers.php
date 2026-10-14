<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
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

        return view(
            'website.home',
            compact(
                'banners',
                'categories',
                'products',
                'wishlistVariantIds'
            )
        );
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
        $blogs = Blog::where('status', 'show')
            ->orderBy('created_at', 'desc')
            ->get();

        $blogCategories = BlogCategory::orderBy('id', 'asc')
            ->get()
            ->keyBy('id');

        return view('website.blog', compact('blogs', 'blogCategories'));
    }
    public function blog_details($slug)
    {
        $blog = Blog::where('slug', $slug)
            ->where('status', 'show')
            ->firstOrFail();

        $blogCategory = BlogCategory::where('id', $blog->category_id)
            ->first();

        $relatedBlogs = Blog::where('status', 'show')
            ->where('id', '!=', $blog->id)
            ->where('category_id', $blog->category_id)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        // If there are less than 3 blogs in the same category,
        // get other latest blogs to complete the 3 cards.
        if ($relatedBlogs->count() < 3) {

            $remaining = 3 - $relatedBlogs->count();

            $additionalBlogs = Blog::where('status', 'show')
                ->where('id', '!=', $blog->id)
                ->whereNotIn('id', $relatedBlogs->pluck('id'))
                ->orderBy('created_at', 'desc')
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
            'variant.product:id,title,slug,image',
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
        // Change this guard if your website uses a different customer guard.
        $user = auth('customer')->user();

        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Please log in to continue checkout.');
        }

        $orderType = $request->query('order_type', 'order');

        $cartItems = CartItem::with([
            'variant.product',
            'variant.media',
            'variant.attributeValues.attribute',
        ])
            ->where('user_id', $user->id)
            ->where('type', $orderType)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->back()
                ->with('error', 'Your cart is empty.');
        }

        $addresses = Address::where('customer_id', $user->id)
            ->orderByDesc('default')
            ->orderByDesc('id')
            ->get();

        $defaultAddress = $addresses->first();

        // Calculate subtotal and weight from current cart data.
        $subtotal = 0;
        $totalWeight = 0;

        foreach ($cartItems as $item) {
            $variant = $item->variant;

            $subtotal += (float) ($variant?->price ?? 0)
                * (int) $item->quantity;

            $totalWeight += (float) ($variant?->weight ?? 0)
                * (int) $item->quantity;
        }

        // Calculate shipping using your existing shipping zone and freight tables.
        $shipping = 0;

        if ($defaultAddress && $defaultAddress->state) {
            $zone = ShippingZone::whereJsonContains(
                'regions',
                $defaultAddress->state
            )->first();

            if ($zone && $zone->free_shipping !== 'yes') {
                $charge = FreightCharge::where('shipping_zone_id', $zone->id)
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

                    if ($latestCharge && $totalWeight > $latestCharge->max_weight) {
                        $shipping = (float) $latestCharge->charge + 30;
                    }
                }
            }
        }

        $shipping = round($shipping);

        // Optional coupon from the URL, for example /checkout?code=SAVE10
        $discount = 0;
        $couponCode = trim((string) $request->query('code', ''));

        if ($couponCode !== '') {
            $coupon = Coupon::where('code', $couponCode)
                ->where('status', 'active')
                ->first();

            if (
                $coupon &&
                (!$coupon->expiry_date ||
                    Carbon::parse($coupon->expiry_date)->endOfDay()->isFuture()) &&
                (!$coupon->minimum_purchase ||
                    $subtotal >= $coupon->minimum_purchase)
            ) {
                if ($coupon->type === 'fixed') {
                    $discount = min((float) $coupon->discount, $subtotal);
                } else {
                    $discount = round(
                        ($subtotal * (float) $coupon->discount) / 100
                    );
                }
            }
        }

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
            'orderType'
        ));
    }

}