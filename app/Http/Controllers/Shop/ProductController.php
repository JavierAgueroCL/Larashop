<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(protected ProductRepositoryInterface $productRepository)
    {
    }

    public function index(Request $request): View|\Illuminate\Http\RedirectResponse
    {
        if ($request->has('category') && $request->filled('category')) {
            $slug = $request->query('category');
            return redirect()->route('products.category', array_merge(['category' => $slug], $request->except('category')));
        }

        $filters = $request->only(['search', 'sort']);
        
        $perPage = $request->input('per_page', 12);
        if (!in_array($perPage, [12, 24, 36, 48])) {
            $perPage = 12;
        }

        $products = $this->productRepository->getFiltered($filters, $perPage);
        
        $categories = Category::whereNull('parent_id')->with('children')->get();

        return view('shop.products.index', compact('products', 'categories'));
    }

    public function category(string $categorySlug, Request $request): View
    {
        $category = Category::where('slug', $categorySlug)->firstOrFail();
        
        // Merge category slug into filters
        $filters = $request->only(['search', 'sort']);
        $filters['category'] = $categorySlug;

        $perPage = $request->input('per_page', 12);
        if (!in_array($perPage, [12, 24, 36, 48])) {
            $perPage = 12;
        }

        $products = $this->productRepository->getFiltered($filters, $perPage);
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

        $product->load(['reviews.user', 'images']);

        $meta = [
            'title' => $product->meta_title ?? $product->name,
            'description' => $product->meta_description ?? $product->short_description,
        ];

        return view('shop.products.show', compact('product', 'meta'));
    }

    public function storeReview(Request $request, Product $product)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if (!$user->hasPurchased($product)) {
            return back()->with('error', 'You can only review products you have purchased.');
        }

        if ($product->reviews()->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'You have already reviewed this product.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        $product->reviews()->create([
            'user_id' => $user->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        return back()->with('success', 'Thank you for your review!');
    }

    public function quickView(Product $product): View
    {
        return view('components.product.quick-view', compact('product'));
    }
}