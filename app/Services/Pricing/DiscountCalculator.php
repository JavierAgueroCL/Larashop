<?php

namespace App\Services\Pricing;

use App\Models\Cart;
use App\Models\Coupon;
use App\Models\PriceRule;

class DiscountCalculator
{
    public function calculateCartDiscount(Cart $cart): float
    {
        $discount = 0.0;

        // 1. Check Coupon
        if ($cart->coupon_id && $cart->coupon) {
            $coupon = $cart->coupon;
            
            if ($this->validateCoupon($coupon, $cart)) {
                if ($coupon->discount_type === 'percentage') {
                    $discount += $cart->subtotal * ($coupon->discount_value / 100);
                } elseif ($coupon->discount_type === 'fixed') {
                    $discount += $coupon->discount_value;
                }
            }
        }

        return min($discount, $cart->subtotal);
    }

    protected function validateCoupon(Coupon $coupon, Cart $cart): bool
    {
        if ($coupon->min_purchase_amount && $cart->subtotal < $coupon->min_purchase_amount) {
            return false;
        }

        if ($coupon->max_uses && $coupon->uses_count >= $coupon->max_uses) {
            return false;
        }

        return true;
    }
}
