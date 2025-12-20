<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General
            ['key' => 'shop_name', 'value' => 'LaraShop', 'type' => 'string', 'group' => 'general'],
            ['key' => 'shop_email', 'value' => 'info@larashop.com', 'type' => 'string', 'group' => 'general'],
            ['key' => 'shop_phone', 'value' => '+34 900 000 000', 'type' => 'string', 'group' => 'general'],

            // Shop
            ['key' => 'maintenance_mode', 'value' => '0', 'type' => 'boolean', 'group' => 'shop'],
            ['key' => 'allow_guest_checkout', 'value' => '1', 'type' => 'boolean', 'group' => 'shop'],
            ['key' => 'products_per_page', 'value' => '12', 'type' => 'integer', 'group' => 'shop'],

            // Currencies
            ['key' => 'default_currency', 'value' => 'EUR', 'type' => 'string', 'group' => 'currency'],

            // Taxes
            ['key' => 'prices_include_tax', 'value' => '1', 'type' => 'boolean', 'group' => 'tax'],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}
