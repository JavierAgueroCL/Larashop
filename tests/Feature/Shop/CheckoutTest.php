<?php

use App\Models\Product;
use App\Models\User;

test('user can place an order', function () {
    $user = User::factory()->create([
        'first_name' => 'John',
        'last_name' => 'Doe',
    ]);
    
    $product = Product::factory()->create(['base_price' => 100]);

    // Add to cart
    $this->actingAs($user)->post(route('cart.add'), [
        'product_id' => $product->id,
        'quantity' => 1,
    ]);

    // Checkout
    $response = $this->actingAs($user)->post(route('checkout.process'), [
        'shipping_address' => [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'address_line_1' => '123 Main St',
            'city' => 'New York',
            'state_province' => 'NY',
            'postal_code' => '10001',
            'country_code' => 'US',
            'phone' => '1234567890',
        ],
        'payment_method' => 'bank_transfer',
    ]);

    if (session('error')) {
        dump(session('error'));
    }

    $response->assertRedirect();
    
    $this->assertDatabaseHas('orders', [
        'user_id' => $user->id,
        'customer_email' => $user->email,
        'payment_method' => 'bank_transfer',
    ]);

    $this->assertDatabaseHas('addresses', [
        'user_id' => $user->id,
        'address_line_1' => '123 Main St',
    ]);
});