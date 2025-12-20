<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    public function run(): void
    {
        Currency::create(['code' => 'EUR', 'symbol' => '€', 'exchange_rate' => 1.000000, 'is_active' => true, 'is_default' => true]);
        Currency::create(['code' => 'USD', 'symbol' => '$', 'exchange_rate' => 1.050000, 'is_active' => true, 'is_default' => false]);
        Currency::create(['code' => 'GBP', 'symbol' => '£', 'exchange_rate' => 0.830000, 'is_active' => true, 'is_default' => false]);
    }
}
