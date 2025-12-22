<?php

namespace Database\Seeders;

use App\Models\Carrier;
use App\Models\ShippingZone;
use Illuminate\Database\Seeder;

class CarrierSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Carriers
        $carriers = [
            [
                'name' => 'standard',
                'display_name' => 'Standard Shipping',
                'delay' => '3-5 business days',
                'position' => 0,
            ],
            [
                'name' => 'express',
                'display_name' => 'Express Shipping',
                'delay' => '1-2 business days',
                'position' => 1,
            ],
        ];

        foreach ($carriers as $data) {
            $carrier = Carrier::create($data);
        }

        // 2. Create Zones (Simplified)
        $zone = ShippingZone::create(['name' => 'Europe']);
        
        // 3. Create Rates
        // Standard: 5.00 flat rate
        $standard = Carrier::where('name', 'standard')->first();
        if ($standard && $standard->shippingRates()->count() === 0) {
            $standard->shippingRates()->create([
                'shipping_zone_id' => $zone->id,
                'calculation_type' => 'flat_rate',
                'min_value' => 0,
                'max_value' => 10000,
                'cost' => 5.00,
            ]);
        }

        // Express: 15.00 flat rate
        $express = Carrier::where('name', 'express')->first();
        if ($express && $express->shippingRates()->count() === 0) {
            $express->shippingRates()->create([
                'shipping_zone_id' => $zone->id,
                'calculation_type' => 'flat_rate',
                'min_value' => 0,
                'max_value' => 10000,
                'cost' => 15.00,
            ]);
        }
    }
}
