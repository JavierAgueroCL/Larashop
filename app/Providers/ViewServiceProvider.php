<?php

namespace App\Providers;

use App\Models\Category;
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
            'components.layout.navbar'
        ], function ($view) {
            $view->with('globalCategories', Category::whereNull('parent_id')->with('children')->where('is_active', true)->orderBy('position')->get());
        });
    }
}
