<?php

namespace App\View\Composers;

use App\Services\Cart\CartService;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class CartComposer
{
    public function __construct(
        protected CartService $cartService
    ) {}

    public function compose(View $view): void
    {
        $user = Auth::user();
        $cart = $this->cartService->getCart($user);
        
        // Calculate total quantity of items
        $cartCount = $cart->items->sum('quantity');

        $view->with('cartCount', $cartCount);
    }
}
