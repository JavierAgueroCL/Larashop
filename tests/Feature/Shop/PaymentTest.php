<?php

use App\Models\Product;
use App\Models\User;

test('bank transfer payment updates order status', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create();

    $this->actingAs($user)->post(route('cart.add'), [
        'product_id' => $product->id,
        'quantity' => 1,
    ]);

    $response = $this->actingAs($user)->post(route('checkout.process'), [
        'shipping_address' => [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'address_line_1' => '123 St',
            'city' => 'City',
            'state_province' => 'NA',
            'postal_code' => '12345',
            'country_code' => 'US',
            'phone' => '123456',
        ],
        'payment_method' => 'bank_transfer',
        'shipping_method' => 'standard',
    ]);

    $response->assertRedirect();
    
    $this->assertDatabaseHas('orders', [
        'user_id' => $user->id,
        'payment_method' => 'bank_transfer',
        'payment_status' => 'pending',
    ]);
});

test('paypal payment redirect', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create();

    $this->actingAs($user)->post(route('cart.add'), [
        'product_id' => $product->id,
        'quantity' => 1,
    ]);

    $response = $this->actingAs($user)->post(route('checkout.process'), [
        'shipping_address' => [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'address_line_1' => '123 St',
            'city' => 'City',
            'state_province' => 'NA',
            'postal_code' => '12345',
            'country_code' => 'US',
            'phone' => '123456',
        ],
        'payment_method' => 'paypal',
        'shipping_method' => 'standard',
    ]);

    $response->assertRedirectContains('sandbox.paypal.com');
});