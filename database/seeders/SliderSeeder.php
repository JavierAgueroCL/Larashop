<?php

namespace Database\Seeders;

use App\Models\Slider;
use Illuminate\Database\Seeder;

class SliderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar tabla antes de sembrar para evitar duplicados si se corre varias veces
        Slider::truncate(); // Comentado por seguridad, descomentar si se desea limpiar

        Slider::create([
            'title' => 'Texto de Prueba Principal 1',
            'subtitle' => 'Subtítulo de Prueba 1',
            'description' => 'Esta es una descripción de prueba para el slider principal. Aquí va texto de relleno para visualizar cómo se ve el contenido.',
            'button_text' => 'Botón de Prueba',
            'button_url' => '/products',
            'image_url' => 'https://placehold.co/800x600/333/fff?text=Imagen+Prueba+1',
            'background_image_url' => 'https://placehold.co/1920x800/f3f4f6/FF324D?text=Slider+Prueba+1',
            'order' => 1,
            'is_active' => true,
        ]);

        Slider::create([
            'title' => 'Texto de Prueba Principal 2',
            'subtitle' => 'Subtítulo de Prueba 2',
            'description' => 'Otra descripción de prueba con diferente longitud para verificar la adaptabilidad del diseño del slider.',
            'button_text' => 'Ver Más',
            'button_url' => '/products',
            'image_url' => 'https://placehold.co/800x600/444/fff?text=Imagen+Prueba+2',
            'background_image_url' => 'https://placehold.co/1920x800/e5e7eb/3b82f6?text=Slider+Prueba+2',
            'order' => 2,
            'is_active' => true,
        ]);

         Slider::create([
            'title' => 'Oferta Especial de Prueba',
            'subtitle' => 'Solo por tiempo limitado',
            'description' => 'Aprovecha las ofertas de prueba que tenemos para ti. Calidad garantizada en todos nuestros productos de prueba.',
            'button_text' => 'Comprar Ahora',
            'button_url' => '/products',
            'image_url' => 'https://placehold.co/800x600/555/fff?text=Imagen+Prueba+3',
            'background_image_url' => 'https://placehold.co/1920x800/22c55e/ffffff?text=Slider+Prueba+3',
            'order' => 3,
            'is_active' => true,
        ]);
    }
}
