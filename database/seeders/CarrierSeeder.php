<?php

namespace Database\Seeders;

use App\Models\Carrier;
use App\Models\ShippingZone;
use Illuminate\Database\Seeder;

class CarrierSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Chile Zone
        $zone = ShippingZone::firstOrCreate(['name' => 'Chile']);

        // 2. Create Chilexpress Carrier
        Carrier::firstOrCreate(
            ['name' => 'Chilexpress'],
            [
                'display_name' => 'Chilexpress',
                'delay' => '1-3 días hábiles',
                'is_active' => true,
                'position' => 0,
            ]
        );
    }
}