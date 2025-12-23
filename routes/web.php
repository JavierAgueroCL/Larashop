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
Route::post('/products/{product}/reviews', [ProductController::class, 'storeReview'])->name('products.reviews.store')->middleware('auth');
Route::get('/products/{product}/quick-view', [ProductController::class, 'quickView'])->name('products.quick-view');

// Wishlist
Route::post('/wishlist/toggle/{product}', [\App\Http\Controllers\Shop\WishlistController::class, 'toggle'])->name('wishlist.toggle');
Route::middleware('auth')->group(function () {
    Route::get('/wishlists/json', [\App\Http\Controllers\Shop\WishlistController::class, 'getWishlistsJson'])->name('wishlist.json');
    Route::post('/wishlist/{wishlist}/add', [\App\Http\Controllers\Shop\WishlistController::class, 'addItem'])->name('wishlist.add_item');
    Route::get('/wishlist', [\App\Http\Controllers\Shop\WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist', [\App\Http\Controllers\Shop\WishlistController::class, 'store'])->name('wishlist.store');
    Route::get('/wishlist/{wishlist}', [\App\Http\Controllers\Shop\WishlistController::class, 'show'])->name('wishlist.show');
    Route::put('/wishlist/{wishlist}', [\App\Http\Controllers\Shop\WishlistController::class, 'update'])->name('wishlist.update');
    Route::delete('/wishlist/{wishlist}', [\App\Http\Controllers\Shop\WishlistController::class, 'destroy'])->name('wishlist.destroy');
    Route::delete('/wishlist/{wishlist}/item/{product}', [\App\Http\Controllers\Shop\WishlistController::class, 'removeItem'])->name('wishlist.remove_item');
});

// Blog
Route::get('/pages/blog', [App\Http\Controllers\Shop\BlogController::class, 'index'])->name('blog.index');
Route::get('/pages/blog/{slug}', [App\Http\Controllers\Shop\BlogController::class, 'show'])->name('blog.show');

Route::get('/pages/{slug}', [PageController::class, 'show'])->name('pages.show');
Route::get('/sitemap.xml', [App\Http\Controllers\Shop\SitemapController::class, 'index'])->name('sitemap');

// Newsletter
Route::post('/newsletter/subscribe', [App\Http\Controllers\Shop\NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

// Cart Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/coupon', [CartController::class, 'applyCoupon'])->name('cart.coupon');
Route::patch('/cart/{itemId}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{itemId}', [CartController::class, 'remove'])->name('cart.remove');

// Checkout (Public for Guest, handles Auth internally)
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');

// Payment Routes
Route::match(['get', 'post'], '/payment/transbank/callback', [App\Http\Controllers\Shop\PaymentController::class, 'callback'])->name('payment.transbank.callback');

// Authenticated Routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        $orders = \App\Models\Order::where('user_id', auth()->id())->latest()->get();
        return view('dashboard', compact('orders'));
    })->middleware(['verified'])->name('dashboard');
    
    Route::get('/dashboard/help', function () {
        return view('shop.dashboard.help');
    })->name('dashboard.help');

    Route::post('/dashboard/help', function (\Illuminate\Http\Request $request) {
        // Here you would handle sending the email
        // Mail::to('support@larashop.test')->send(new ContactForm($request->all()));
        
        return back()->with('success', 'Your message has been sent successfully! We will contact you soon.');
    })->name('dashboard.help.submit');

    // Security
    Route::get('/dashboard/security', [App\Http\Controllers\Shop\Dashboard\SecurityController::class, 'index'])->name('dashboard.security');
    Route::delete('/dashboard/security/sessions', [App\Http\Controllers\Shop\Dashboard\SecurityController::class, 'destroyOtherSessions'])->name('dashboard.security.logout-others');

    // Addresses
    Route::get('/dashboard/addresses/shipping', [\App\Http\Controllers\Shop\AddressController::class, 'shippingIndex'])->name('addresses.shipping');
    Route::get('/dashboard/addresses/billing', [\App\Http\Controllers\Shop\AddressController::class, 'billingIndex'])->name('addresses.billing');
    Route::resource('addresses', \App\Http\Controllers\Shop\AddressController::class)->except(['index', 'show']);

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';