<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Product;
use App\Services\Cart\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(protected CartService $cartService)
    {
    }

    public function index(): View
    {
        $cart = $this->cartService->getCart(Auth::user());
        $totals = $this->cartService->getCartTotals($cart);
        
        return view('shop.cart.index', compact('cart', 'totals'));
    }

    public function applyCoupon(Request $request): RedirectResponse
    {
        $request->validate([
            'coupon_code' => 'required|string',
        ]);

        $coupon = Coupon::where('code', $request->coupon_code)->first();

        if (!$coupon) {
            return redirect()->back()->with('error', 'Invalid coupon code.');
        }

        $cart = $this->cartService->getCart(Auth::user());
        $cart->update(['coupon_id' => $coupon->id]);

        return redirect()->back()->with('success', 'Coupon applied successfully!');
    }

    public function add(Request $request): RedirectResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'combination_id' => 'nullable|exists:product_combinations,id',
        ]);

        $product = Product::findOrFail($request->product_id);
        $cart = $this->cartService->getCart(Auth::user());

        $this->cartService->addItem(
            $cart,
            $product,
            $request->quantity,
            $request->combination_id
        );

        return redirect()->back()->with('success', 'Product added to cart!');
    }

    public function update(Request $request, int $itemId): RedirectResponse
    {
        $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        $cart = $this->cartService->getCart(Auth::user());
        $this->cartService->updateQuantity($cart, $itemId, $request->quantity);

        return redirect()->route('cart.index')->with('success', 'Cart updated!');
    }

    public function remove(int $itemId): RedirectResponse
    {
        $cart = $this->cartService->getCart(Auth::user());
        $this->cartService->removeItem($cart, $itemId);

        return redirect()->route('cart.index')->with('success', 'Item removed from cart!');
    }
}