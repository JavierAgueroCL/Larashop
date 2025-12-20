<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Shop\HomeController;
use App\Http\Controllers\Shop\ProductController;
use App\Http\Controllers\Shop\CartController;
use App\Http\Controllers\Shop\CheckoutController;
use App\Http\Controllers\Shop\PageController;
use App\Http\Controllers\Shop\LanguageController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', HomeController::class)->name('home');

// Categories (must be before specific product routes if sharing prefix, but here we separate)
Route::get('/products/{category}', [ProductController::class, 'category'])->name('products.category');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');

// Product Detail (changed to /p/ to avoid conflict with /products/{category})
Route::get('/p/{slug}', [ProductController::class, 'show'])->name('products.show');

Route::get('/pages/{slug}', [PageController::class, 'show'])->name('pages.show');
Route::get('/lang/{locale}', [LanguageController::class, 'switch'])->name('lang.switch');
Route::get('/sitemap.xml', [App\Http\Controllers\Shop\SitemapController::class, 'index'])->name('sitemap');

// Cart Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/coupon', [CartController::class, 'applyCoupon'])->name('cart.coupon');
Route::patch('/cart/{itemId}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{itemId}', [CartController::class, 'remove'])->name('cart.remove');

// Authenticated Routes
Route::middleware('auth')->group(function () {
    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');

    // Dashboard
    Route::get('/dashboard', function () {
        $orders = \App\Models\Order::where('user_id', auth()->id())->latest()->get();
        return view('dashboard', compact('orders'));
    })->middleware(['verified'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';