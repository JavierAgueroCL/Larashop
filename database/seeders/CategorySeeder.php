<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Electrónica',
                'slug' => 'electronica',
                'children' => [
                    ['name' => 'Ordenadores', 'slug' => 'ordenadores'],
                    ['name' => 'Móviles', 'slug' => 'moviles'],
                    ['name' => 'Tablets', 'slug' => 'tablets'],
                ]
            ],
            [
                'name' => 'Ropa',
                'slug' => 'ropa',
                'children' => [
                    ['name' => 'Hombre', 'slug' => 'hombre'],
                    ['name' => 'Mujer', 'slug' => 'mujer'],
                    ['name' => 'Niños', 'slug' => 'ninos'],
                ]
            ],
            [
                'name' => 'Hogar',
                'slug' => 'hogar',
                'children' => [
                    ['name' => 'Muebles', 'slug' => 'muebles'],
                    ['name' => 'Decoración', 'slug' => 'decoracion'],
                ]
            ],
        ];

        foreach ($categories as $categoryData) {
            $category = Category::create([
                'name' => $categoryData['name'],
                'slug' => $categoryData['slug'],
                'is_active' => true,
                'position' => 0,
            ]);

            if (isset($categoryData['children'])) {
                foreach ($categoryData['children'] as $index => $childData) {
                    Category::create([
                        'parent_id' => $category->id,
                        'name' => $childData['name'],
                        'slug' => $childData['slug'],
                        'is_active' => true,
                        'position' => $index,
                    ]);
                }
            }
        }
    }
}
