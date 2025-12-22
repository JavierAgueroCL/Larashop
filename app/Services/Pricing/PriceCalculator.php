<?php

namespace App\Services\Pricing;

use App\Models\Product;
use App\Models\PriceRule;
use Carbon\Carbon;

class PriceCalculator
{
    public function __construct(protected TaxCalculator $taxCalculator)
    {
    }

    public function calculate(Product $product, int $quantity = 1): float
    {
        $price = $product->discount_price ?? $product->base_price;

        // Apply Price Rules (Simplified for now)
        $rules = PriceRule::where('is_active', true)
            ->where(function ($query) use ($product) {
                $query->where('product_id', $product->id)
                      ->orWhere('category_id', $product->categories->first()?->id);
            })
            ->where(function ($query) {
                $query->whereNull('start_date')
                      ->orWhere('start_date', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('end_date')
                      ->orWhere('end_date', '>=', now());
            })
            ->orderBy('priority', 'desc')
            ->get();

        foreach ($rules as $rule) {
            if ($rule->discount_type === 'percentage') {
                $price -= $price * ($rule->discount_value / 100);
            } elseif ($rule->discount_type === 'fixed') {
                $price -= $rule->discount_value;
            }
        }

        return max(0, $price);
    }

    public function calculateWithTax(Product $product): float
    {
        $price = $this->calculate($product);
        return $this->taxCalculator->getPriceWithTax($price, $product->tax);
    }
}
