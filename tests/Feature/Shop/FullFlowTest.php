<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\Carrier;
use App\Models\ShippingZone;

test('full purchase flow', function () {
    // 1. Setup
    $user = User::factory()->create(['first_name' => 'John', 'last_name' => 'Doe']);
    $category = Category::factory()->create(['slug' => 'gadgets']);
    $product = Product::factory()->create([
        'name' => 'Super Phone',
        'slug' => 'super-phone',
        'base_price' => 500,
        'is_active' => true,
    ]);
    $product->categories()->attach($category);
    
    $carrier = Carrier::factory()->create(['name' => 'standard']);
    $zone = ShippingZone::factory()->create();
    $carrier->shippingRates()->create([
        'shipping_zone_id' => $zone->id,
        'calculation_type' => 'flat_rate',
        'cost' => 10.00
    ]);

    // 2. Visit Home
    $this->get(route('home'))->assertOk()->assertSee('Exclusive Products');

    // 3. Visit Category
    $this->get(route('products.index', ['category' => 'gadgets']))->assertOk()->assertSee('Super Phone');

    // 4. Visit Product
    $this->get(route('products.show', 'super-phone'))->assertOk()->assertSee('Super Phone');

    // 5. Add to Cart
    $this->actingAs($user)->post(route('cart.add'), [
        'product_id' => $product->id,
        'quantity' => 1
    ])->assertRedirect();

    // 6. Visit Cart
    $this->actingAs($user)->get(route('cart.index'))->assertOk()->assertSee('Super Phone');

    // 7. Proceed to Checkout
    $this->actingAs($user)->get(route('checkout.index'))->assertOk()->assertSee('Shipping Address');

    // 8. Process Checkout
    $response = $this->actingAs($user)->post(route('checkout.process'), [
        'shipping_address' => [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'address_line_1' => 'Main St 123',
            'city' => 'Madrid',
            'state_province' => 'MD',
            'postal_code' => '28001',
            'country_code' => 'ES',
            'phone' => '123456789',
        ],
        'payment_method' => 'bank_transfer',
        'shipping_method' => 'standard',
    ]);

    $response->assertRedirect();
    
    // 9. Verify Order
    $this->assertDatabaseHas('orders', [
        'user_id' => $user->id,
        'grand_total' => 500 + ($product->tax->rate * 500 / 100) + 10,
    ]);
});