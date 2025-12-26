<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(), // or null if nullable
            'alias' => $this->faker->word,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'address_line_1' => $this->faker->streetAddress,
            'region_id' => 1, // Fallback, override in tests
            'comuna_id' => 1, // Fallback, override in tests
            'country_code' => 'CL',
            'phone' => $this->faker->phoneNumber,
            'address_type' => 'shipping',
        ];
    }
}
