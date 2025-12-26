<?php

namespace App\Services\Pricing;

use App\Models\Product;
use App\Models\Tax;

class TaxCalculator
{
    /**
     * Calculate tax amount on top of a net price.
     * Use this when prices are exclusive of tax.
     */
    public function calculate(float $price, ?Tax $tax): float
    {
        if (!$tax) {
            return 0.0;
        }

        return $price * ($tax->rate / 100);
    }

    /**
     * Extract tax amount from a gross price.
     * Use this when prices are inclusive of tax.
     */
    public function extract(float $grossPrice, ?Tax $tax): float
    {
        if (!$tax) {
            return 0.0;
        }

        // Formula: Tax = Gross - (Gross / (1 + Rate/100))
        return $grossPrice - ($grossPrice / (1 + ($tax->rate / 100)));
    }

    public function getPriceWithTax(float $price, ?Tax $tax): float
    {
        return $price + $this->calculate($price, $tax);
    }
}