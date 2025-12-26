<?php

namespace App\Services\Shipping;

use App\Models\Address;
use App\Models\Carrier;
use App\Models\Cart;
use Illuminate\Support\Collection;

use Illuminate\Support\Facades\Log;

class ShippingCalculator
{
    public function __construct(
        protected ChilexpressShippingService $chilexpressService,
        protected StarkenShippingService $starkenService
    ) {}

    public function getAvailableCarriers(Address $address, Cart $cart): Collection
    {
        // 1. Get all active carriers
        $carriers = Carrier::where('is_active', true)->with('shippingRates')->get();
        
        Log::info('ShippingCalculator: Found ' . $carriers->count() . ' active carriers.', ['carriers' => $carriers->pluck('name')]);

        return $carriers->flatMap(function ($carrier) use ($address, $cart) {
            
            // Specialized Logic for Chilexpress
            if (str_contains(strtolower($carrier->name), 'chilexpress')) {
                return $this->calculateChilexpressRate($carrier, $address, $cart);
            }

            // Specialized Logic for Starken
            if (str_contains(strtolower($carrier->name), 'starken')) {
                return $this->calculateStarkenRate($carrier, $address, $cart);
            }

            // Default Logic (Flat Rate / DB Rate)
            $rate = $carrier->shippingRates->first(); 
            
            if (!$rate) {
                return [];
            }

            $cost = $rate->cost; 

            $carrier->calculated_cost = $cost;
            return [$carrier];
        })->filter();
    }

    protected function calculateStarkenRate(Carrier $carrier, Address $address, Cart $cart): Collection
    {
        if (!$address->comuna) {
            Log::warning('ShippingCalculator: Starken skipped. Address has no comuna.');
            return collect();
        }

        // Calculate Package Details
        $weight = 0;
        $width = 0;
        $height = 0;
        $depth = 0;

        foreach ($cart->items as $item) {
            $product = $item->product;
            $qty = $item->quantity;
            
            $weight += ($product->weight ?? 1) * $qty;
            
            // Simplified aggregation (stacking)
            $height += ($product->height ?? 10) * $qty;
            $width = max($width, $product->width ?? 10);
            $depth = max($depth, $product->depth ?? 10);
        }

        $comunaName = $address->comuna->comuna;

        $apiRates = $this->starkenService->getRates(
            $comunaName, 
            $weight, 
            $width, 
            $height, 
            $depth
        );

        if (!$apiRates || !isset($apiRates['listaTarifas'])) {
            return collect(); 
        }

        $options = $apiRates['listaTarifas'];
        $results = collect();

        foreach ($options as $option) {
            $clone = clone $carrier;
            // Use name|codigoTipoEntrega as unique identifier
            // E.g. Starken|2 (DOMICILIO)
            $code = $option['tipoEntrega']['codigoTipoEntrega'];
            $desc = $option['tipoEntrega']['descripcionTipoEntrega'];
            
            $clone->name = $carrier->name . '|' . $code; 
            $clone->display_name = $carrier->display_name . ' (' . $desc . ')';
            $clone->calculated_cost = $option['costoTotal'];
            
            $results->push($clone);
        }

        return $results;
    }

    protected function calculateChilexpressRate(Carrier $carrier, Address $address, Cart $cart): Collection
    {
        if (!$address->comuna) {
            Log::warning('ShippingCalculator: Chilexpress skipped. Address has no comuna.', ['address_id' => $address->id]);
            return collect();
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
            
            $weight += ($product->weight ?? 1) * $qty;
            $totalValue += $item->total;
            
            $height += ($product->height ?? 10) * $qty;
            $width = max($width, $product->width ?? 10);
            $depth = max($depth, $product->depth ?? 10);
        }

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
            return collect(); 
        }

        $options = $apiRates['data']['courierServiceOptions'];
        $results = collect();

        foreach ($options as $option) {
            // Filter out Return Logistics (Logística Devolución)
            if (str_contains(strtoupper($option['serviceDescription']), 'LDEV') || 
                str_contains(strtoupper($option['serviceDescription']), 'DEVOLUCION')) {
                continue;
            }

            $clone = clone $carrier;
            // Use name|serviceTypeCode as unique identifier
            // E.g. Chilexpress|2
            $clone->name = $carrier->name . '|' . $option['serviceTypeCode']; 
            $clone->display_name = $carrier->display_name . ' (' . $option['serviceDescription'] . ')';
            $clone->calculated_cost = $option['serviceValue'];
            
            $results->push($clone);
        }

        return $results;
    }

    public function calculateCost(string $carrierName, ?Address $address = null, ?Cart $cart = null): float
    {
        // Handle composite name
        if (str_contains($carrierName, '|')) {
             $parts = explode('|', $carrierName);
             $baseName = $parts[0];
             
             if ($address && $cart) {
                 $carrier = Carrier::where('name', $baseName)->first();
                 if (!$carrier) return 0.0;

                 $rates = collect();

                 if (str_contains(strtolower($baseName), 'chilexpress')) {
                     $rates = $this->calculateChilexpressRate($carrier, $address, $cart);
                 } elseif (str_contains(strtolower($baseName), 'starken')) {
                     $rates = $this->calculateStarkenRate($carrier, $address, $cart);
                 }
                 
                 $match = $rates->first(function($c) use ($carrierName) {
                     return $c->name === $carrierName;
                 });
                 
                 return $match ? $match->calculated_cost : 0.0;
             }
        }
        
        // Default legacy / fallback
        $carrier = Carrier::where('name', $carrierName)->first();
        return $carrier ? ($carrier->shippingRates->first()->cost ?? 0.0) : 0.0;
    }
}
