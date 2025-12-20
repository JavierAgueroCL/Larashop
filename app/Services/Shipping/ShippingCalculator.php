<?php

namespace App\Services\Shipping;

use App\Models\Address;
use App\Models\Carrier;
use App\Models\Cart;
use Illuminate\Support\Collection;

class ShippingCalculator
{
    public function getAvailableCarriers(Address $address, Cart $cart): Collection
    {
        // 1. Determine Zone based on Address (Simplified: All 'US' is one zone, 'ES' another, etc)
        // For MVP, we only have one zone "Europe" created in seeder, not linked to countries yet.
        // Let's just return all carriers with their default rate for the first zone found.
        
        $carriers = Carrier::where('is_active', true)->with('shippingRates')->get();

        return $carriers->map(function ($carrier) {
            // Find applicable rate
            $rate = $carrier->shippingRates->first(); // Simplified
            
            if (!$rate) {
                return null;
            }

            $cost = $rate->cost; // Simplified (ignore weight/price logic for now)

            $carrier->calculated_cost = $cost;
            return $carrier;
        })->filter();
    }

    public function calculateCost(string $carrierName): float
    {
        $carrier = Carrier::where('name', $carrierName)->first();
        if (!$carrier) {
            return 0.0;
        }
        
        // Simplified: return first rate cost
        return $carrier->shippingRates->first()->cost ?? 0.0;
    }
}
