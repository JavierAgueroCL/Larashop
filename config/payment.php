<?php

return [
    'default' => env('PAYMENT_GATEWAY', 'bank_transfer'),

    'gateways' => [
        'bank_transfer' => [
            'enabled' => true,
            'name' => 'Transferencia Bancaria',
            'description' => 'Realiza una transferencia bancaria a nuestra cuenta.',
        ],
        'paypal' => [
            'enabled' => env('PAYPAL_ENABLED', false),
            'name' => 'PayPal',
            'mode' => env('PAYPAL_MODE', 'sandbox'),
            'client_id' => env('PAYPAL_CLIENT_ID'),
            'secret' => env('PAYPAL_SECRET'),
        ],
    ],
];
