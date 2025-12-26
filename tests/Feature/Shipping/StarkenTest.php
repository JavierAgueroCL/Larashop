<?php

use App\Models\Address;
use App\Models\Carrier;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Comuna;
use App\Models\Provincia;
use App\Models\Product;
use App\Models\Region;
use App\Models\StarkenCity;
use App\Services\Shipping\ShippingCalculator;
use Illuminate\Support\Facades\Http;

test('starken shipping rate calculation', function () {
    // 1. Setup Data
    $region = Region::create([
        'region' => 'Coquimbo', 
        'abreviatura' => 'CO',
        'capital' => 'La Serena'
    ]);

    $provincia = Provincia::create([
        'provincia' => 'Elqui',
        'region_id' => $region->id
    ]);

    $comuna = Comuna::create([
        'comuna' => 'ANDACOLLO', 
        'provincia_id' => $provincia->id
    ]);
    
    // Seed Starken City Mapping
    StarkenCity::create([
        'region_code' => 4,
        'city_code' => 1024,
        'city_name' => 'ANDACOLLO',
        'comuna_code' => 2609,
        'comuna_name' => 'ANDACOLLO'
    ]);

    $address = Address::factory()->create([
        'comuna_id' => $comuna->id,
        'country_code' => 'CL'
    ]);
    
    // Ensure Carrier exists
    Carrier::create([
        'name' => 'Starken',
        'display_name' => 'Starken',
        'is_active' => true
    ]);

    // Create Product and Cart
    $product = Product::factory()->create([
        'weight' => 2.5,
        'width' => 20.3,
        'height' => 10.5,
        'depth' => 15.0,
        'base_price' => 10000
    ]);

    $cart = Cart::factory()->create();
    CartItem::factory()->create([
        'cart_id' => $cart->id,
        'product_id' => $product->id,
        'quantity' => 1
    ]);

    // 2. Mock API Response
    Http::fake([
        '*/consultarTarifas' => Http::response([
            'type' => 'consultarCoberturaRespuesta',
            'codigoRespuesta' => 1,
            'mensajeRespuesta' => 'Busqueda exitosa.',
            'listaTarifas' => [
                [
                    'costoTotal' => 9300,
                    'diasEntrega' => 1,
                    'tipoEntrega' => [
                        'codigoTipoEntrega' => 1,
                        'descripcionTipoEntrega' => 'AGENCIA'
                    ]
                ],
                [
                    'costoTotal' => 10300,
                    'diasEntrega' => 1,
                    'tipoEntrega' => [
                        'codigoTipoEntrega' => 2,
                        'descripcionTipoEntrega' => 'DOMICILIO'
                    ]
                ]
            ]
        ], 200)
    ]);

    // 3. Execute Calculator
    $calculator = app(ShippingCalculator::class);
    $carriers = $calculator->getAvailableCarriers($address, $cart);

    // 4. Assertions
    $starken = $carriers->first(fn($c) => str_contains($c->name, 'Starken'));

    expect($starken)->not->toBeNull();
    // Check for AGENCY option (9300)
    $agencia = $carriers->first(fn($c) => $c->name === 'Starken|1');
    expect($agencia)->not->toBeNull();
    expect($agencia->calculated_cost)->toBe(9300);
    expect($agencia->display_name)->toContain('AGENCIA');

    // Check for DOMICILIO option (10300)
    $domicilio = $carriers->first(fn($c) => $c->name === 'Starken|2');
    expect($domicilio)->not->toBeNull();
    expect($domicilio->calculated_cost)->toBe(10300);
    expect($domicilio->display_name)->toContain('DOMICILIO');
});
