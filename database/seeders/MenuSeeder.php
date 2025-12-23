<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Page;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Menus
        $headerMenu = Menu::firstOrCreate(['slug' => 'header'], ['name' => 'Navegación Principal']);
        $footerUsefulLinks = Menu::firstOrCreate(['slug' => 'footer_useful_links'], ['name' => 'Enlaces Útiles']);
        $footerMyAccount = Menu::firstOrCreate(['slug' => 'footer_my_account'], ['name' => 'Mi Cuenta']);

        // 2. Populate Header Menu
        if ($headerMenu->items()->count() === 0) {
            $headerMenu->items()->create(['title' => 'Inicio', 'route' => 'home', 'order' => 1]);
            
            // "Shop" could be a dropdown or just a link to all products. 
            // In the current layout, categories are a separate dropdown.
            // Let's add "All Products"
            $headerMenu->items()->create(['title' => 'Todos los Productos', 'route' => 'products.index', 'order' => 2]);

            // Add dynamic pages to header
            $blogPage = Page::where('slug', 'blog')->first();
            if ($blogPage) {
                $headerMenu->items()->create(['title' => 'Blog', 'page_id' => $blogPage->id, 'order' => 3]);
            }

            $contactPage = Page::where('slug', 'contact')->first();
            if ($contactPage) {
                $headerMenu->items()->create(['title' => 'Contacto', 'page_id' => $contactPage->id, 'order' => 4]);
            }
        }

        // 3. Populate Footer Useful Links
        if ($footerUsefulLinks->items()->count() === 0) {
            $pages = ['about-us' => 'Sobre Nosotros', 'faq' => 'Preguntas Frecuentes', 'location' => 'Ubicación', 'affiliates' => 'Afiliados', 'contact' => 'Contacto'];
            $order = 1;
            foreach ($pages as $slug => $title) {
                $page = Page::where('slug', $slug)->first();
                if ($page) {
                    $footerUsefulLinks->items()->create([
                        'title' => $title,
                        'page_id' => $page->id,
                        'order' => $order++
                    ]);
                }
            }
        }

        // 4. Populate Footer My Account
        if ($footerMyAccount->items()->count() === 0) {
            $footerMyAccount->items()->create(['title' => 'Mi Cuenta', 'route' => 'dashboard', 'order' => 1]);
            $footerMyAccount->items()->create(['title' => 'Devoluciones', 'url' => '#', 'order' => 3]); // Placeholder
            $footerMyAccount->items()->create(['title' => 'Historial de Pedidos', 'route' => 'dashboard', 'order' => 4]); // Same as dashboard for now
            $footerMyAccount->items()->create(['title' => 'Seguimiento de Pedidos', 'url' => '#', 'order' => 5]); // Placeholder
        }
    }
}