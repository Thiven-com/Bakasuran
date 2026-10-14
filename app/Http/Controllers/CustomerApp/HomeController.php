<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Http\Resources\HomeOfferCollection;
use App\Http\Resources\SectionCollection;
use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\Category;
use App\Models\ProductVariant;
use App\Models\FlashSale;
use Carbon\Carbon;
use App\Http\Resources\BannerCollection;
use App\Http\Resources\BlogCollection;
use App\Http\Resources\VariantCollection;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\ProductsCollection;
use App\Models\Blog;
use App\Models\Faq;
use App\Models\HomeOffer;
use App\Models\Product;
use App\Models\Section;
use App\Models\SiteSetting;

class HomeController extends Controller
{

    public function index()
    {
        // 1. Active banners
        $banners = new BannerCollection(
            Banner::where('status', 'show')
                ->orderBy('sort_order')
                ->limit(10)
                ->get()
        );

        // 2. Top categories (only with image)
        $categories =  new CategoryCollection(Category::where('is_feature', 'yes')
            ->where('status', 'show')
            ->where('parent_id', 0)
            ->take(8)
            ->get());

        // 3. Flash Sale (from flash_sales + flash_sale_products)
        $flashSale = FlashSale::where('status', 1)
            ->where('starts_at', '<=', Carbon::now())
            ->where('ends_at', '>=', Carbon::now())
            ->with([
                'products.variant.product',
                'products.variant.media',
                'products.variant.attributeValues.attribute'
            ])
            ->latest('id')
            ->first();

        $flashVariants = $flashSale
            ? $flashSale->products->pluck('variant')->filter() // filter out nulls
            : collect();

        $flashCollection = new VariantCollection(
            $flashVariants,
            ['flash_sale' => $flashSale ? [
                'id'        => $flashSale->id,
                'title'     => $flashSale->title,
                'banner'    => $flashSale->banner ? asset($flashSale->banner) : null,
                'starts_at' => optional($flashSale->starts_at)->toDateTimeString(),
                'ends_at'   => optional($flashSale->ends_at)->toDateTimeString(),
            ] : null]
        );

        // 4. Featured products
        $featuredVariants = ProductVariant::with(['product', 'media', 'attributeValues.attribute'])
            ->whereHas('product', fn($q) => $q->where('is_feature', 'yes'))
            ->whereHas('product', fn($q) => $q->where('status', 'show'))
            ->get()->unique('product_id')
            ->values()
            ->take(10);

        $featured = new VariantCollection($featuredVariants);
        //5.Top Products
        $topVariants = ProductVariant::with(['product', 'media', 'attributeValues.attribute'])
            ->inRandomOrder()
            ->whereHas('product', fn($q) => $q->where('status', 'show'))
            ->get()->unique('product_id')
            ->values()
            ->take(5);

        $topProducts = new VariantCollection($topVariants);
        //5.Product Deals
        $deals = ProductVariant::with(['product', 'media', 'attributeValues.attribute'])->whereNotNull('video')
            ->inRandomOrder()
            ->whereHas('product', fn($q) => $q->where('status', 'show'))
            ->get()->unique('product_id')
            ->values()
            ->take(5);


        $productDeals = new VariantCollection($deals);

        //6.Today Deals
        $today_deals = ProductVariant::with(['product', 'media', 'attributeValues.attribute'])->where('today_deal', 'yes')
            ->inRandomOrder()
            ->whereHas('product', fn($q) => $q->where('status', 'show'))
            ->get()->unique('product_id')
            ->values()
            ->take(10);
        $todayDeals = new VariantCollection($today_deals);


        $sections = new SectionCollection(Section::where('page_name', 'home')->get());
        $homeOffers = new HomeOfferCollection(HomeOffer::latest()->take(10)->get());
        $blogs = new BlogCollection(Blog::latest()->take(10)->get());

        return response()->json([
            'success'    => 1,
            'banners'    => $banners,
            'categories' => $categories,
            'flash_sale' => $flashCollection,
            'featured'   => $featured,
            'top_products'   => $topProducts,
            'product_deals'   => $productDeals,
            'today_deals'   => $todayDeals,
            'sections' => $sections,
            'homeOffers' => $homeOffers,
            'blogs' => $blogs
        ]);
    }
    public function newindex()
    {
        // 1. Active banners
        $banners = new BannerCollection(
            Banner::where('status', 'show')
                ->orderBy('sort_order')
                ->limit(10)
                ->get()
        );

        // 2. Top categories (only with image)
        $categories =  new CategoryCollection(Category::where('is_feature', 'yes')
            ->where('status', 'show')
            ->where('parent_id', 0)
            ->take(8)
            ->get());

        // 3. Flash Sale (from flash_sales + flash_sale_products)
        $flashSale = FlashSale::where('status', 1)
            ->where('starts_at', '<=', Carbon::now())
            ->where('ends_at', '>=', Carbon::now())
            ->with([
                'products.variant.product',
                'products.variant.media',
                'products.variant.attributeValues.attribute'
            ])
            ->latest('id')
            ->first();

        $flashVariants = $flashSale
            ? $flashSale->products->pluck('variant')->filter() // filter out nulls
            : collect();

        $flashCollection = new VariantCollection(
            $flashVariants,
            ['flash_sale' => $flashSale ? [
                'id'        => $flashSale->id,
                'title'     => $flashSale->title,
                'banner'    => $flashSale->banner ? asset($flashSale->banner) : null,
                'starts_at' => optional($flashSale->starts_at)->toDateTimeString(),
                'ends_at'   => optional($flashSale->ends_at)->toDateTimeString(),
            ] : null]
        );

        $featuredProducts = Product::with([
            'variants.media',
            'variants.attributeValues.attribute'
        ])
            ->where('is_feature', 'yes')
            ->limit(10)
            ->get();

        $featured = new ProductsCollection($featuredProducts);
        //5.Top Products
        $topProductsData = Product::with([
            'variants.media',
            'variants.attributeValues.attribute'
        ])
            ->inRandomOrder()
            ->limit(5)
            ->get();

        $topProducts = new ProductsCollection($topProductsData);
        //5.Product Deals
        $productDealsData = Product::with([
            'variants.media',
            'variants.attributeValues.attribute'
        ])
            ->whereHas('variants', function ($q) {
                $q->whereNotNull('video');
            })
            ->inRandomOrder()
            ->limit(5)
            ->get();

        $productDeals = new ProductsCollection($productDealsData);

        //6.Today Deals
        $todayDealsData = Product::with([
            'variants.media',
            'variants.attributeValues.attribute'
        ])
            ->whereHas('variants', function ($q) {
                $q->where('today_deal', 'yes');
            })
            ->inRandomOrder()
            ->limit(10)
            ->get();

        $todayDeals = new ProductsCollection($todayDealsData);


        $sections = new SectionCollection(Section::where('page_name', 'home')->get());

        return response()->json([
            'success'    => 1,
            'banners'    => $banners,
            'categories' => $categories,
            'flash_sale' => $flashCollection,
            'featured'   => $featured,
            'top_products'   => $topProducts,
            'product_deals'   => $productDeals,
            'today_deals'   => $todayDeals,
            'sections' => $sections
        ]);
    }

    public function faqs(Request $request)
    {
        $data = Faq::get();
        if (count($data) > 0) {
            return response()->json([
                'success' => 1,
                'data' => $data,
                'message' => 'Data Fetched Successfully'
            ]);
        } else {
            return response()->json([
                'success' => 0,
                'message' => 'No Data Found'
            ]);
        }
    }

    public function sitesettings(Request $request)
    {
        $settings = SiteSetting::first();
        return response()->json([
            'success' => 1,
            'message' => "Data Fetched Successfully",
            'data' => $settings
        ]);
    }
}
