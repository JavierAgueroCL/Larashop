<?php

use App\Models\Carrier;
use App\Models\Product;
use App\Models\User;
use App\Services\Shipping\ShippingCalculator;

test('shipping cost is calculated correctly', function () {
    // Seed carriers manually for test control (or use seeder if predictable)
    // Assuming CarrierSeeder already ran in test env setup or we create new ones
    // Let's create specific carrier/rate
    
    $carrier = Carrier::factory()->create(['name' => 'test_carrier']);
    $zone = \App\Models\ShippingZone::factory()->create();
    $carrier->shippingRates()->create([
        'shipping_zone_id' => $zone->id,
        'calculation_type' => 'flat_rate',
        'cost' => 10.00
    ]);

    $calculator = app(ShippingCalculator::class);
    $cost = $calculator->calculateCost('test_carrier');

    expect($cost)->toBe(10.00);
});

test('order includes shipping cost', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['base_price' => 100]);
    
    // Create carrier
    $carrier = Carrier::factory()->create(['name' => 'standard']);
    $zone = \App\Models\ShippingZone::factory()->create();
    $carrier->shippingRates()->create([
        'shipping_zone_id' => $zone->id,
        'calculation_type' => 'flat_rate',
        'cost' => 5.00
    ]);

    $this->actingAs($user)->post(route('cart.add'), [
        'product_id' => $product->id,
        'quantity' => 1,
    ]);

    $this->actingAs($user)->post(route('checkout.process'), [
        'shipping_address' => [
            'first_name' => 'Test',
            'last_name' => 'User',
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

    $this->assertDatabaseHas('orders', [
        'user_id' => $user->id,
        'shipping_cost' => 5.00,
        // Grand total = 100 (price) + tax + 5 (shipping)
        // Note: Tax calculation depends on product tax rate. 
        // If tax is 21%, grand total = 121 + 5 = 126
    ]);
});