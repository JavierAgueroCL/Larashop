<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductSpecification;
use App\Models\Product;

class ProductSpecificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $product = Product::where('name', 'like', '%MacBook Pro%')->first();

        if (!$product) {
            return;
        }

        $specifications = [
            [
                'product_id' => $product->id,
                'attribute_section' => 'Características generales',
                'attribute_key' => 'características_generales_marca',
                'attribute_name' => 'Marca',
                'attribute_value' => 'Apple',
            ],
            [
                'product_id' => $product->id,
                'attribute_section' => 'Características generales',
                'attribute_key' => 'características_generales_modelo',
                'attribute_name' => 'Modelo',
                'attribute_value' => 'MacBook Pro',
            ],
            [
                'product_id' => $product->id,
                'attribute_section' => 'Especificaciones técnicas',
                'attribute_key' => 'especificaciones_técnicas_procesador',
                'attribute_name' => 'Procesador',
                'attribute_value' => 'M1 Pro',
            ],
            [
                'product_id' => $product->id,
                'attribute_section' => 'Especificaciones técnicas',
                'attribute_key' => 'especificaciones_técnicas_memoria_ram',
                'attribute_name' => 'Memoria RAM',
                'attribute_value' => '16 GB',
            ],
            [
                'product_id' => $product->id,
                'attribute_section' => 'Especificaciones técnicas',
                'attribute_key' => 'especificaciones_técnicas_almacenamiento',
                'attribute_name' => 'Almacenamiento',
                'attribute_value' => '512 GB SSD',
            ],
            [
                'product_id' => $product->id,
                'attribute_section' => 'Pantalla',
                'attribute_key' => 'pantalla_tamaño',
                'attribute_name' => 'Tamaño de pantalla',
                'attribute_value' => '14 pulgadas',
            ],
            [
                'product_id' => $product->id,
                'attribute_section' => 'Pantalla',
                'attribute_key' => 'pantalla_resolución',
                'attribute_name' => 'Resolución',
                'attribute_value' => '3024 x 1964 píxeles',
            ],
        ];

        foreach ($specifications as $spec) {
            ProductSpecification::updateOrCreate(
                [
                    'product_id' => $spec['product_id'],
                    'attribute_key' => $spec['attribute_key']
                ],
                $spec
            );
        }
    }
}