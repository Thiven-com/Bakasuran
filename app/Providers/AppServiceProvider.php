<?php

namespace App\Providers;

use App\Models\Product;
use Illuminate\Support\ServiceProvider;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {

            $uriSlug = 0;

            if (\Request::route()) {
                $uriSlug = \Request::route()->uri;
            }

            $site = SiteSetting::first();

            /*
            |--------------------------------------------------------------------------
            | Featured Products - Only 5
            |--------------------------------------------------------------------------
            */

            $featuredProducts = Product::where('status', 'show')
                ->where('is_feature', 'yes')
                ->whereHas('variant')
                ->with([
                    'variant',
                    'category',
                ])
                ->inRandomOrder()
                ->take(5)
                ->get();

            $view->with([
                'site' => $site,
                'uriSlug' => $uriSlug,
                'featuredProducts' => $featuredProducts,
            ]);
        });
    }
}
