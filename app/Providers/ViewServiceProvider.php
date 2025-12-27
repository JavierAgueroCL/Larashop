<?php

namespace App\Providers;

use App\Models\Category;
use App\View\Composers\CartComposer;
use App\View\Composers\WishlistComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer([
            'layouts.navigation', 
            'layouts.footer', 
            'components.shop.sidebar',
            'components.layout.main-header',
            'components.layout.navbar',
            'shop.home'
        ], function ($view) {
            $showCount = config('shop.show_product_count');
            $showEmpty = config('shop.show_empty_categories');

            $query = Category::whereNull('parent_id')
                ->where('is_active', true)
                ->orderBy('position');
            
            if (!$showEmpty) {
                $query->where(function ($q) {
                    $q->has('products')->orHas('children.products');
                });
            }

            if ($showCount) {
                $query->withCount('products');
            }

            $query->with(['children' => function ($q) use ($showCount, $showEmpty) {
                $q->where('is_active', true);
                
                if (!$showEmpty) {
                    $q->where(function($sub) {
                        $sub->has('products')->orHas('children.products');
                    });
                }

                if ($showCount) {
                    $q->withCount('products');
                }

                // Load Level 3 (Grandchildren)
                $q->with(['children' => function ($q2) use ($showCount, $showEmpty) {
                    $q2->where('is_active', true);
                    if (!$showEmpty) {
                        $q2->has('products');
                    }
                    if ($showCount) {
                        $q2->withCount('products');
                    }
                }]);
            }]);

            $view->with('globalCategories', $query->get());
        });

        View::composer('components.layout.main-header', CartComposer::class);
        View::composer('components.layout.main-header', WishlistComposer::class);
    }
}
