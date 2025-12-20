<?php

use App\Models\Product;

test('home page can be rendered', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

test('home page displays featured products', function () {
    $featuredProduct = Product::factory()->create([
        'is_featured' => true,
        'is_active' => true,
        'name' => 'Featured Item'
    ]);

    $response = $this->get('/');

    $response->assertOk();
    $response->assertSee('Featured Item');
});

test('home page displays new products', function () {
    $newProduct = Product::factory()->create([
        'is_featured' => false,
        'is_active' => true,
        'name' => 'New Item'
    ]);

    $response = $this->get('/');

    $response->assertOk();
    $response->assertSee('New Item');
});