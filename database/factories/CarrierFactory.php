<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CarrierFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->slug(),
            'display_name' => fake()->company(),
            'is_active' => true,
        ];
    }
}
