<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductImageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'image_path' => 'https://placehold.co/600x600?text=Product+Image',
            'is_primary' => false,
            'position' => 0,
            'alt_text' => fake()->sentence(),
        ];
    }
}
