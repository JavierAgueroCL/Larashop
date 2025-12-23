<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'title' => 'Sobre Nosotros',
                'slug' => 'about-us',
                'content' => '<h1>Sobre LaraShop</h1><p>Somos una plataforma de comercio electrónico moderna construida con Laravel.</p>',
            ],
            [
                'title' => 'Política de Privacidad',
                'slug' => 'privacy-policy',
                'content' => '<h1>Política de Privacidad</h1><p>Su privacidad es importante para nosotros.</p>',
            ],
            [
                'title' => 'Términos y Condiciones',
                'slug' => 'terms-and-conditions',
                'content' => '<h1>Términos y Condiciones</h1><p>Por favor, lea estos términos cuidadosamente.</p>',
            ],
            [
                'title' => 'Preguntas Frecuentes',
                'slug' => 'faq',
                'content' => '<h1>Preguntas Frecuentes</h1><p>Aquí puede encontrar respuestas a preguntas comunes.</p>',
            ],
            [
                'title' => 'Ubicación',
                'slug' => 'location',
                'content' => '<h1>Nuestra Ubicación</h1><p>Estamos ubicados en Calle Falsa 123, Madrid, España.</p>',
            ],
            [
                'title' => 'Afiliados',
                'slug' => 'affiliates',
                'content' => '<h1>Programa de Afiliados</h1><p>Únase a nuestro programa de afiliados y gane comisiones.</p>',
            ],
            [
                'title' => 'Contáctenos',
                'slug' => 'contact',
                'content' => '<h1>Contáctenos</h1><p>Email: info@larashop.com<br>Teléfono: +34 123 456 789</p>',
            ],
            [
                'title' => 'Blog',
                'slug' => 'blog',
                'content' => '<h1>Nuestro Blog</h1><p>Últimas noticias y actualizaciones de LaraShop.</p>',
            ],
        ];

        foreach ($pages as $page) {
            Page::firstOrCreate(
                ['slug' => $page['slug']],
                $page
            );
        }
    }
}
