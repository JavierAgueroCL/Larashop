<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use Illuminate\Database\Seeder;

class BlogCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BlogCategory::create(['name' => 'Tecnología', 'slug' => 'tecnologia']);
        BlogCategory::create(['name' => 'Moda', 'slug' => 'moda']);
        BlogCategory::create(['name' => 'Estilo de Vida', 'slug' => 'estilo-de-vida']);
    }
}
