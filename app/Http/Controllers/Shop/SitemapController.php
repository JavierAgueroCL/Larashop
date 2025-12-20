<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Page;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $products = Product::active()->get();
        $categories = Category::where('is_active', true)->get();
        $pages = Page::where('is_active', true)->get();

        return response()->view('shop.sitemap', compact('products', 'categories', 'pages'))
            ->header('Content-Type', 'text/xml');
    }
}