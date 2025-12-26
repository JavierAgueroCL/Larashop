<?php

use App\Models\Address;
use App\Models\Carrier;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Comuna;
use App\Models\Provincia;
use App\Models\Product;
use App\Models\Region;
use App\Services\Shipping\ShippingCalculator;
use Illuminate\Support\Facades\Http;

test('chilexpress shipping rate calculation', function () {
    config(['services.chilexpress.subscription_key' => 'mock_key']);
    config(['services.chilexpress.origin_comuna' => 'SANTIAGO CENTRO']);
    config(['services.chilexpress.base_url' => 'https://test.api']);

    // 1. Setup Data
    $region = Region::create([
        'region' => 'Metropolitana', 
        'abreviatura' => 'RM',
        'capital' => 'Santiago'
    ]);

    $provincia = Provincia::create([
        'provincia' => 'Santiago',
        'region_id' => $region->id
    ]);

    $comuna = Comuna::create([
        'comuna' => 'PROVIDENCIA', 
        'provincia_id' => $provincia->id
    ]);
    
    $address = Address::factory()->create([
        'comuna_id' => $comuna->id,
        'country_code' => 'CL'
    ]);
    
    // Ensure Carrier exists
    Carrier::create([
        'name' => 'Chilexpress',
        'display_name' => 'Chilexpress',
        'is_active' => true
    ]);

    // Create Product and Cart
    $product = Product::factory()->create([
        'weight' => 1.5,
        'width' => 10,
        'height' => 10,
        'depth' => 10,
        'base_price' => 10000
    ]);

    $cart = Cart::factory()->create();
    CartItem::factory()->create([
        'cart_id' => $cart->id,
        'product_id' => $product->id,
        'quantity' => 2
    ]);

    // 2. Mock API Response
    Http::fake([
        '*/rates/courier' => Http::response([
            'data' => [
                'courierServiceOptions' => [
                    [
                        'serviceTypeCode' => 2,
                        'serviceValue' => 4500,
                        'serviceDescription' => 'Dia Habil Siguiente'
                    ],
                    [
                        'serviceTypeCode' => 3,
                        'serviceValue' => 8000,
                        'serviceDescription' => 'Express'
                    ]
                ]
            ]
        ], 200)
    ]);

    // 3. Execute Calculator
    $calculator = app(ShippingCalculator::class);
    $carriers = $calculator->getAvailableCarriers($address, $cart);

    // 4. Assertions
    $chilexpress = $carriers->first(fn($c) => str_starts_with($c->name, 'Chilexpress'));

    expect($chilexpress)->not->toBeNull();
    // Use float comparison or flexible check as APIs return numbers
    expect($chilexpress->calculated_cost)->toBe(4500);
    expect($chilexpress->display_name)->toContain('Dia Habil Siguiente');
    expect($chilexpress->name)->toBe('Chilexpress|2');
    
    // Check second option exists
    $express = $carriers->first(fn($c) => $c->name === 'Chilexpress|3');
    expect($express)->not->toBeNull();
    expect($express->calculated_cost)->toBe(8000);
});