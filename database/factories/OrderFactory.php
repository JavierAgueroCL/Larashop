<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'order_number' => 'ORD-' . strtoupper(Str::random(10)),
            'user_id' => User::factory(),
            'customer_email' => fake()->safeEmail(),
            'customer_first_name' => fake()->firstName(),
            'customer_last_name' => fake()->lastName(),
            'subtotal' => 100.00,
            'tax_total' => 21.00,
            'grand_total' => 121.00,
            'payment_method' => 'bank_transfer',
            'current_status' => 'pending',
        ];
    }
}
