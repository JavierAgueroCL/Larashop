<?php

use App\Models\Category;
use App\Models\Product;

test('can filter products by search term', function () {
    Product::factory()->create(['name' => 'Unique Phone', 'is_active' => true]);
    Product::factory()->create(['name' => 'Another Item', 'is_active' => true]);

    $response = $this->get(route('products.index', ['search' => 'Unique']));

    $response->assertOk();
    $response->assertSee('Unique Phone');
    $response->assertDontSee('Another Item');
});

test('can filter products by category', function () {
    $category = Category::factory()->create(['slug' => 'phones']);
    $otherCategory = Category::factory()->create(['slug' => 'laptops']);

    $productInCat = Product::factory()->create(['name' => 'My Phone', 'is_active' => true]);
    $productInCat->categories()->attach($category);

    $productOther = Product::factory()->create(['name' => 'My Laptop', 'is_active' => true]);
    $productOther->categories()->attach($otherCategory);

    $response = $this->get(route('products.index', ['category' => 'phones']));

    $response->assertOk();
    $response->assertSee('My Phone');
    $response->assertDontSee('My Laptop');
});

test('can sort products', function () {
    Product::factory()->create(['name' => 'Cheap Item', 'base_price' => 10, 'is_active' => true]);
    Product::factory()->create(['name' => 'Expensive Item', 'base_price' => 100, 'is_active' => true]);

    // Price Ascending
    $responseAsc = $this->get(route('products.index', ['sort' => 'price_asc']));
    $responseAsc->assertSeeInOrder(['Cheap Item', 'Expensive Item']);

    // Price Descending
    $responseDesc = $this->get(route('products.index', ['sort' => 'price_desc']));
    $responseDesc->assertSeeInOrder(['Expensive Item', 'Cheap Item']);
});