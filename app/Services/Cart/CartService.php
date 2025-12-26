<?php

namespace App\Services\Cart;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use App\Repositories\Contracts\CartRepositoryInterface;
use App\Services\Pricing\DiscountCalculator;
use App\Services\Pricing\PriceCalculator;
use App\Services\Pricing\TaxCalculator;
use Illuminate\Support\Facades\Session;

class CartService
{
    public function __construct(
        protected CartRepositoryInterface $cartRepository,
        protected PriceCalculator $priceCalculator,
        protected TaxCalculator $taxCalculator,
        protected DiscountCalculator $discountCalculator
    ) {}

    public function getCart(?User $user): Cart
    {
        if ($user) {
            $cart = $this->cartRepository->findByUser($user);
        } else {
            $sessionId = Session::getId();
            $cart = $this->cartRepository->findBySession($sessionId);
        }

        if (!$cart) {
            $cart = $this->createCart($user);
        }

        return $cart;
    }

    protected function createCart(?User $user): Cart
    {
        return $this->cartRepository->create([
            'user_id' => $user?->id,
            'session_id' => $user ? null : Session::getId(),
            'status' => 'active',
        ]);
    }

    public function addItem(Cart $cart, Product $product, int $quantity = 1, ?int $combinationId = null): void
    {
        $item = $cart->items()
            ->where('product_id', $product->id)
            ->where('product_combination_id', $combinationId)
            ->first();

        // Calculate current price snapshot (base price + combination impact - specific product discounts)
        $unitPrice = $this->priceCalculator->calculate($product); 
        // Note: Combination price impact should be handled inside PriceCalculator or here. 
        // For now, let's keep it simple: Base Price + Combination Impact
        if ($combinationId) {
            // Logic to fetch combination and add impact would go here
        }

        if ($item) {
            $item->increment('quantity', $quantity);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'product_combination_id' => $combinationId,
                'quantity' => $quantity,
                'price_snapshot' => $unitPrice,
            ]);
        }
    }

    public function removeItem(Cart $cart, int $itemId): void
    {
        $cart->items()->where('id', $itemId)->delete();
    }

    public function updateQuantity(Cart $cart, int $itemId, int $quantity): void
    {
        if ($quantity <= 0) {
            $this->removeItem($cart, $itemId);
            return;
        }

        $cart->items()->where('id', $itemId)->update(['quantity' => $quantity]);
    }

    public function getCartTotals(Cart $cart): array
    {
        $grossTotal = 0;
        $taxTotal = 0;

        foreach ($cart->items as $item) {
            $lineTotal = $item->price_snapshot * $item->quantity;
            $grossTotal += $lineTotal;
            
            // Extract Tax for this line (assuming price_snapshot includes tax)
            $taxAmount = $this->taxCalculator->extract($lineTotal, $item->product->tax);
            $taxTotal += $taxAmount;
        }

        // Apply Global Discounts
        $discountTotal = $this->discountCalculator->calculateCartDiscount($cart);
        
        // Prevent negative total
        $finalTotal = max(0, $grossTotal - $discountTotal);

        // Adjust tax proportionally if there is a discount and total > 0
        if ($grossTotal > 0 && $discountTotal > 0) {
            $ratio = $finalTotal / $grossTotal;
            $taxTotal *= $ratio;
        }

        return [
            'subtotal' => $finalTotal - $taxTotal, // Net Subtotal (Excl. Tax)
            'tax' => $taxTotal,
            'discount' => $discountTotal,
            'total' => $finalTotal
        ];
    }

    public function clear(Cart $cart): void
    {
        $cart->items()->delete();
    }

    public function mergeGuestCartToUser(string $sessionId, User $user): void
    {
        $guestCart = $this->cartRepository->findBySession($sessionId);

        if (!$guestCart) {
            return;
        }

        $userCart = $this->cartRepository->findByUser($user);

        if (!$userCart) {
            $guestCart->update([
                'user_id' => $user->id,
                'session_id' => null
            ]);
        } else {
            foreach ($guestCart->items as $item) {
                $this->addItem($userCart, $item->product, $item->quantity, $item->product_combination_id);
            }
            $guestCart->delete();
            $guestCart->items()->delete();
        }
    }
}
