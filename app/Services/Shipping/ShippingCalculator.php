<?php

namespace App\Services\Shipping;

use App\Models\Address;
use App\Models\Carrier;
use App\Models\Cart;
use Illuminate\Support\Collection;

class ShippingCalculator
{
    public function __construct(
        protected ChilexpressShippingService $chilexpressService
    ) {}

    public function getAvailableCarriers(Address $address, Cart $cart): Collection
    {
        // 1. Get all active carriers
        $carriers = Carrier::where('is_active', true)->with('shippingRates')->get();

        return $carriers->map(function ($carrier) use ($address, $cart) {
            
            // Specialized Logic for Chilexpress
            if (str_contains(strtolower($carrier->name), 'chilexpress')) {
                return $this->calculateChilexpressRate($carrier, $address, $cart);
            }

            // Default Logic (Flat Rate / DB Rate)
            $rate = $carrier->shippingRates->first(); 
            
            if (!$rate) {
                return null;
            }

            $carrier->calculated_cost = $rate->cost;
            return $carrier;
        })->filter();
    }

    protected function calculateChilexpressRate(Carrier $carrier, Address $address, Cart $cart): ?Carrier
    {
        if (!$address->comuna) {
            return null;
        }

        // Calculate Package Details
        $weight = 0;
        $width = 0;
        $height = 0;
        $depth = 0;
        $totalValue = 0;

        foreach ($cart->items as $item) {
            $product = $item->product;
            $qty = $item->quantity;
            
            // Basic aggregation: Sum weights
            $weight += ($product->weight ?? 1) * $qty;
            $totalValue += $item->total;
            
            // Simplified dimension logic: Stack height, max width/depth
            // This is a rough estimation.
            $height += ($product->height ?? 10) * $qty;
            $width = max($width, $product->width ?? 10);
            $depth = max($depth, $product->depth ?? 10);
        }

        // Use Comuna Name or Code. Ideally, Comuna model has 'code' field. 
        // We try with the name upper-cased as fallback or specific mapping logic here.
        // For MVP, passing the comuna name directly.
        $destination = strtoupper($address->comuna->comuna);

        $apiRates = $this->chilexpressService->getRates(
            $destination, 
            $weight, 
            $width, 
            $height, 
            $depth,
            $totalValue
        );

        if (!$apiRates || !isset($apiRates['data']['courierServiceOptions'])) {
            // Fallback or hide if API fails
            // If API fails, maybe return default rate if available?
            // For now, return null to hide carrier if calculation fails.
            return null; 
        }

        // Find the cheapest or specific service option
        // API returns multiple services (Next Day, Priority, etc.)
        // We can create a Carrier instance for EACH service option, 
        // OR just pick one (e.g. 'Dia Habil Siguiente') for the main 'Chilexpress' carrier.
        
        // Let's pick the first available option for simplicity
        $option = $apiRates['data']['courierServiceOptions'][0] ?? null;

        if ($option) {
            $carrier->calculated_cost = $option['serviceCost'];
            $carrier->display_name = $carrier->display_name . ' (' . $option['serviceDescription'] . ')';
            return $carrier;
        }

        return null;
    }

    public function calculateCost(string $carrierName): float
    {
        // This method needs context (Cart/Address) to work for dynamic carriers.
        // It might be deprecated or need refactoring if called without context.
        // For legacy support, return 0 or DB rate.
        $carrier = Carrier::where('name', $carrierName)->first();
        return $carrier ? ($carrier->shippingRates->first()->cost ?? 0.0) : 0.0;
    }
}
