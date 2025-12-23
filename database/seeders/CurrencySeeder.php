<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    public function run(): void
    {
        Currency::truncate();

        Currency::create([
            'code' => 'CLP',
            'symbol' => '$',
            'exchange_rate' => 1.000000,
            'is_active' => true,
            'is_default' => true
        ]);

        Currency::create([
            'code' => 'USD',
            'symbol' => 'US$',
            'exchange_rate' => 0.001100, // This will be updated by the command
            'is_active' => true,
            'is_default' => false
        ]);
    }
}