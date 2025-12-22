<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Tax;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->words(3, true);

        return [
            'brand_id' => Brand::factory(),
            'sku' => strtoupper(Str::random(10)),
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'short_description' => fake()->sentence(),
            'description' => fake()->paragraphs(3, true),
            'guarantee' => fake()->boolean(80) ? fake()->paragraph() : null, // 80% chance of having guarantee info
            'is_digital' => false,
            'is_active' => true,
            'is_featured' => fake()->boolean(20), // 20% de probabilidad
            'base_price' => $basePrice = fake()->randomFloat(2, 10, 500),
            'discount_price' => fake()->boolean(30) ? $basePrice * (1 - fake()->randomFloat(2, 0.1, 0.5)) : null,
            'cost_price' => fake()->randomFloat(2, 5, 250),
            'tax_id' => Tax::factory(),
            'weight' => fake()->numberBetween(100, 5000),
            'stock_quantity' => fake()->numberBetween(0, 100),
            'low_stock_threshold' => 5,
            'has_combinations' => false,
        ];
    }

    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }

    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock_quantity' => 0,
        ]);
    }
}
