<?php

use App\Models\Product;

test('can list active products', function () {
    Product::factory()->count(5)->create(['is_active' => true]);
    Product::factory()->count(2)->create(['is_active' => false]);

    $response = $this->get(route('products.index'));

    $response->assertOk();
    $response->assertViewHas('products', function ($products) {
        return $products->count() === 5;
    });
});

test('can view single product', function () {
    $product = Product::factory()->create();

    $response = $this->get(route('products.show', $product->slug));

    $response->assertOk();
    $response->assertSee($product->name);
});
