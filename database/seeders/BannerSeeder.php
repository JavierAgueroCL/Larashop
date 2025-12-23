<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Banner::create([
            'title' => 'Teléfonos Inteligentes',
            'subtitle' => 'Desde $99',
            'button_text' => 'Comprar Ahora',
            'button_url' => '/products',
            'image_url' => 'https://placehold.co/800x400/333/fff?text=Banner+1',
            'order' => 1,
        ]);

        Banner::create([
            'title' => 'Portátiles',
            'subtitle' => 'Hasta 20% DTO',
            'button_text' => 'Ver Oferta',
            'button_url' => '/products',
            'image_url' => 'https://placehold.co/800x400/555/fff?text=Banner+2',
            'order' => 2,
        ]);
    }
}