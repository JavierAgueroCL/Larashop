<?php

return [
    'name' => env('SHOP_NAME', 'LaraShop'),
    'email' => env('SHOP_EMAIL', 'info@larashop.com'),
    'currency' => 'CLP',
    'locale' => 'es',
    'tax_included' => env('SHOP_TAX_INCLUDED', true),
    'guest_checkout' => env('SHOP_GUEST_CHECKOUT', true),
    'low_stock_threshold' => env('SHOP_LOW_STOCK_THRESHOLD', 5),
    'show_product_count' => env('SHOW_PRODUCT_COUNT', false),
    'show_empty_categories' => env('SHOW_EMPTY_CATEGORIES', false),

    'pagination' => [
        'products_per_page' => 12,
        'orders_per_page' => 10,
    ],

    'images' => [
        'product' => [
            'max_size' => 2048, // KB
            'dimensions' => [
                'large' => [800, 800],
                'medium' => [400, 400],
                'thumb' => [150, 150],
            ],
        ],
    ],
];
