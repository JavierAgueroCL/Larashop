<?php

namespace App\Services\Pricing;

use App\Models\Product;
use App\Models\Tax;

class TaxCalculator
{
    public function calculate(float $price, ?Tax $tax): float
    {
        if (!$tax) {
            return 0.0;
        }

        return $price * ($tax->rate / 100);
    }

    public function getPriceWithTax(float $price, ?Tax $tax): float
    {
        return $price + $this->calculate($price, $tax);
    }
}
