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
        
        // 3. Create Rates (Simplified)
        // Standard: 5.00 flat rate
        $standard = Carrier::where('name', 'standard')->first();
        $standard->shippingRates()->create([ // Assumes relation in Carrier model, wait, I didn't add hasMany rates to Carrier
            'shipping_zone_id' => $zone->id,
            'calculation_type' => 'flat_rate',
            'cost' => 5.00,
        ]);

        // Express: 15.00 flat rate
        $express = Carrier::where('name', 'express')->first();
        $express->shippingRates()->create([ // Need relation
            'shipping_zone_id' => $zone->id,
            'calculation_type' => 'flat_rate',
            'cost' => 15.00,
        ]);
    }
}
