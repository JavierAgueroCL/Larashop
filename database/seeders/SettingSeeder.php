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
            ['key' => 'shop_description', 'value' => 'High quality products for your daily needs. We provide the best e-commerce experience.', 'type' => 'string', 'group' => 'general'],
            ['key' => 'shop_email', 'value' => 'support@larashop.test', 'type' => 'string', 'group' => 'general'],
            ['key' => 'shop_phone', 'value' => '123-456-7890', 'type' => 'string', 'group' => 'general'],
            ['key' => 'shop_address', 'value' => '123 Street, City, Country', 'type' => 'string', 'group' => 'general'],
            ['key' => 'shop_timezone', 'value' => 'UTC', 'type' => 'string', 'group' => 'general'],

            // Social
            ['key' => 'social_facebook', 'value' => '#', 'type' => 'string', 'group' => 'social'],
            ['key' => 'social_twitter', 'value' => '#', 'type' => 'string', 'group' => 'social'],
            ['key' => 'social_instagram', 'value' => '#', 'type' => 'string', 'group' => 'social'],
            ['key' => 'social_youtube', 'value' => '#', 'type' => 'string', 'group' => 'social'],

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
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}