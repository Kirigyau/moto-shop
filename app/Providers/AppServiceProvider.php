<?php

namespace App\Providers;

use App\Http\Controllers\ShopController;
use App\Listeners\MergeGuestCartOnLogin;
use App\Models\Product;
use App\Support\Cart;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        Event::listen(Login::class, MergeGuestCartOnLogin::class);

        View::composer('layouts.shop', function ($view): void {
            $view->with('cartState', Cart::getState());
        });

        View::composer('layouts.admin', function ($view): void {
            $categories = ShopController::CATEGORIES;
            $counts = Product::query()
                ->selectRaw('category, count(*) as aggregate')
                ->groupBy('category')
                ->pluck('aggregate', 'category');

            $activeCategory = request()->query('category');
            if (! is_string($activeCategory) || ! isset($categories[$activeCategory])) {
                $activeCategory = null;
            }

            if (request()->routeIs('admin.products.create')) {
                $fromProduct = old('category');
                if (is_string($fromProduct) && isset($categories[$fromProduct])) {
                    $activeCategory = $fromProduct;
                } elseif ($activeCategory === null) {
                    $cat = request()->query('category');
                    if (is_string($cat) && isset($categories[$cat])) {
                        $activeCategory = $cat;
                    }
                }
            }

            if (request()->routeIs('admin.products.edit')) {
                $product = request()->route('product');
                if ($product instanceof Product) {
                    $activeCategory = $product->category;
                }
            }

            $view->with([
                'adminCategories' => $categories,
                'adminCategoryCounts' => $counts,
                'adminActiveCategory' => $activeCategory,
                'adminTotalProducts' => Product::query()->count(),
            ]);
        });
    }
}
