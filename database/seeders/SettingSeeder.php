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
            ['key' => 'shop_logo', 'value' => null, 'type' => 'image', 'group' => 'general'],
            ['key' => 'shop_description', 'value' => 'Productos de alta calidad para sus necesidades diarias. Ofrecemos la mejor experiencia de comercio electrónico.', 'type' => 'string', 'group' => 'general'],
            ['key' => 'shop_email', 'value' => 'support@larashop.test', 'type' => 'string', 'group' => 'general'],
            ['key' => 'shop_phone', 'value' => '+56 9 1234 5678', 'type' => 'string', 'group' => 'general'],
            ['key' => 'shop_address', 'value' => 'Av. Providencia 1234, Santiago, Chile', 'type' => 'string', 'group' => 'general'],
            ['key' => 'shop_timezone', 'value' => 'America/Santiago', 'type' => 'string', 'group' => 'general'],

            // Home Features
            ['key' => 'feature_1_title', 'value' => 'Envío Gratis', 'type' => 'string', 'group' => 'home_features'],
            ['key' => 'feature_1_subtitle', 'value' => 'En todos los pedidos superiores a $50.000', 'type' => 'string', 'group' => 'home_features'],
            ['key' => 'feature_2_title', 'value' => 'Pago Seguro', 'type' => 'string', 'group' => 'home_features'],
            ['key' => 'feature_2_subtitle', 'value' => 'Transacciones 100% seguras', 'type' => 'string', 'group' => 'home_features'],
            ['key' => 'feature_3_title', 'value' => 'Soporte 24/7', 'type' => 'string', 'group' => 'home_features'],
            ['key' => 'feature_3_subtitle', 'value' => 'Atención al cliente dedicada', 'type' => 'string', 'group' => 'home_features'],

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
            ['key' => 'default_currency', 'value' => 'CLP', 'type' => 'string', 'group' => 'currency'],

            // Taxes
            ['key' => 'prices_include_tax', 'value' => '1', 'type' => 'boolean', 'group' => 'tax'],

            // Design
            ['key' => 'hero_slider_height_mobile', 'value' => '500', 'type' => 'integer', 'group' => 'design'],
            ['key' => 'hero_slider_height_desktop', 'value' => '750', 'type' => 'integer', 'group' => 'design'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
