<?php

use App\Models\Product;
use App\Models\User;

test('user can add product to cart', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create();

    $response = $this->actingAs($user)
        ->post(route('cart.add'), [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('carts', ['user_id' => $user->id]);
    $this->assertDatabaseHas('cart_items', [
        'product_id' => $product->id,
        'quantity' => 2,
    ]);
});

test('guest can add product to cart', function () {
    $product = Product::factory()->create();

    $response = $this->post(route('cart.add'), [
        'product_id' => $product->id,
        'quantity' => 1,
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');

    // Cart should exist with null user_id but active session
    $this->assertDatabaseHas('carts', ['user_id' => null]);
});

test('can view cart page', function () {
    $user = User::factory()->create();
    $this->actingAs($user)->get(route('cart.index'))->assertOk();
});