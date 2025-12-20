<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TaxFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => 'IVA 21%',
            'rate' => 21.00,
            'is_active' => true,
        ];
    }
}
