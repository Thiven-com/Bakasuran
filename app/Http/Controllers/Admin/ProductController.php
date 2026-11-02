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






        $wishlistVariantIds = [];



        if (Auth::guard('customer')->check()) {

            $userId = Auth::guard('customer')->id();



            $wishlistVariantIds = WishlistItem::where('user_id', $userId)

                ->pluck('product_variant_id')

                ->toArray();

        }






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




        $categories = Category::where('status', 'show')

            ->whereNull('deleted_at')

            ->orderBy('sort_order', 'asc')

            ->orderBy('id', 'asc')

            ->get();








        $productsQuery = Product::with([

            'category',

            'variant',

            'restaurant',

        ])

            ->where('status', 'show')

            ->whereHas('variant');








        if ($request->filled('search')) {



            $search = trim($request->search);



            $productsQuery->where(function ($query) use ($search) {



                $query->where('title', 'LIKE', "%{$search}%")

                    ->orWhereHas('category', function ($categoryQuery) use ($search) {



                        $categoryQuery->where('title', 'LIKE', "%{$search}%");



                    });



            });

        }








        if ($request->filled('category')) {



            $category = $request->category;



            $productsQuery->whereHas('category', function ($query) use ($category) {



                $query->where('slug', $category)

                    ->orWhere('id', $category);



            });

        }








        if ($request->filled('min_price')) {



            $minPrice = (float) $request->min_price;



            $productsQuery->whereHas('variant', function ($query) use ($minPrice) {



                $query->whereRaw(

                    'CAST(price AS DECIMAL(15,2)) >= ?',

                    [$minPrice]

                );



            });

        }








        if ($request->filled('max_price')) {



            $maxPrice = (float) $request->max_price;



            $productsQuery->whereHas('variant', function ($query) use ($maxPrice) {



                $query->whereRaw(

                    'CAST(price AS DECIMAL(15,2)) <= ?',

                    [$maxPrice]

                );



            });

        }








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








        $products = $productsQuery

            ->paginate(12)

            ->withQueryString();








        $categoryCounts = Product::where('status', 'show')

            ->whereHas('variant')

            ->selectRaw('category_id, COUNT(*) as total')

            ->groupBy('category_id')

            ->pluck('total', 'category_id');








        $wishlistVariantIds = [];



        if (Auth::guard('customer')->check()) {



            $userId = Auth::guard('customer')->id();



            $wishlistVariantIds = WishlistItem::where('user_id', $userId)

                ->pluck('product_variant_id')

                ->toArray();

        }








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
            'variant',
            'addonGroups.options',



            // Product media

            'media',

            'primaryMedia',

        ])

            ->where('slug', $slug)

            ->where('status', 'show')

            ->firstOrFail();






        $variant = $product->variant;






        $sellingPrice = $variant?->price;

        $actualPrice = $variant?->actual_price;






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






        $productImage = null;



        if ($variant && !empty($variant->image)) {



            $productImage = $variant->image;



        } elseif ($product->primaryMedia) {



            $productImage = $product->primaryMedia->file_path ?? null;



        } elseif (!empty($product->image)) {



            $productImage = $product->image;

        }






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




        $addresses = Address::where('customer_id', $user->id)

            ->orderByDesc('default')

            ->orderByDesc('id')

            ->get();



        $defaultAddress = $addresses->first();




        $subtotal = 0;

        $totalWeight = 0;



        foreach ($cartItems as $item) {

            $variant = $item->variant;



            $subtotal += (float) ($variant?->price ?? 0)

                * (int) $item->quantity;



            $totalWeight += (float) ($variant?->weight ?? 0)

                * (int) $item->quantity;

        }




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



        return view('website.restaurants', compact('restaurants'));

    }







    public function restaurant_details($slug)
    {

        $restaurant = Restaurant::where('slug', $slug)

            ->where('is_active', true)

            ->firstOrFail();



        $products = Product::with([
            'category',
            'brand',
            'restaurant',

            // Product variants, attribute mappings and variant images
            'variants.attributeMappings.attribute',
            'variants.attributeMappings.value',
            'variants.media',

            // Product images
            'media',
            'primaryMedia',

            // Preserve the existing single-variant relationship
            'variant',

            // Product add-on groups and their options
            'addonGroups.options',
        ])

            ->where('restaurant_id', $restaurant->id)

            ->where('status', 'show')

            ->whereHas('variant')

            ->latest()

            ->get();



        $categories = $products

            ->filter(fn($product) => $product->category)

            ->groupBy(fn($product) => $product->category->name)

            ->map(fn($items, $name) => [

                'name' => $name,

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

        $restaurantName = 'Bakasuran Signature';



        return view('website.table-booking', compact(

            'slug',

            'restaurantName'

        ));

    }



    public function checkAvailability(

        \Illuminate\Http\Request $request,

        $slug

    ) {

        $validated = $request->validate([

            'booking_date' => 'required|date|after_or_equal:today',

            'booking_time' => 'required|date_format:H:i',

            'guests' => 'required|integer|min:1|max:12',

            'seating' => 'required|in:indoor,outdoor,family,private',

            'special_request' => 'nullable|string|max:500',

        ]);



        // Demo result only; actual restaurant availability

        // must be checked against real booking data.

        return back()->with([

            'availability_checked' => true,

            'booking_details' => $validated,

            'booking_slug' => $slug,

        ]);

    }





}
