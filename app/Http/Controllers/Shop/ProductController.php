<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(protected ProductRepositoryInterface $productRepository)
    {
    }

    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'category', 'sort']);
        
        $products = $this->productRepository->getFiltered($filters, 12);
        
        $categories = Category::whereNull('parent_id')->with('children')->get();

        return view('shop.products.index', compact('products', 'categories'));
    }

    public function category(string $categorySlug, Request $request): View
    {
        $category = Category::where('slug', $categorySlug)->firstOrFail();
        
        // Merge category slug into filters
        $filters = $request->only(['search', 'sort']);
        $filters['category'] = $categorySlug;

        $products = $this->productRepository->getFiltered($filters, 12);
        $categories = Category::whereNull('parent_id')->with('children')->get();

        // Pass current category for UI highlighting
        return view('shop.products.index', compact('products', 'categories', 'category'));
    }

    public function show(string $slug): View
    {
        $product = $this->productRepository->findBySlug($slug);
        
        if (!$product) {
            abort(404);
        }

        $meta = [
            'title' => $product->meta_title ?? $product->name,
            'description' => $product->meta_description ?? $product->short_description,
        ];

        return view('shop.products.show', compact('product', 'meta'));
    }
}