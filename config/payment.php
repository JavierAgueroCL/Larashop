<?php

return [
    'default' => env('PAYMENT_GATEWAY', 'transbank'),

    'gateways' => [
        'bank_transfer' => [
            'enabled' => true,
            'name' => 'Transferencia Bancaria',
            'description' => 'Realiza una transferencia bancaria a nuestra cuenta.',
            'details' => [
                'bank_name' => env('BANK_TRANSFER_BANK', 'Banco Estado'),
                'account_type' => env('BANK_TRANSFER_TYPE', 'Cuenta Vista/RUT'),
                'account_number' => env('BANK_TRANSFER_NUMBER', '12345678-9'),
                'account_holder' => env('BANK_TRANSFER_HOLDER', 'LaraShop SpA'),
                'rut' => env('BANK_TRANSFER_RUT', '76.123.456-7'),
                'email' => env('BANK_TRANSFER_EMAIL', 'pagos@larashop.cl'),
            ],
        ],
        'transbank' => [
            'enabled' => true,
            'name' => 'Webpay Plus (Transbank)',
            'description' => 'Paga con Redcompra, Tarjeta de Crédito o Débito.',
        ],
        'paypal' => [
            'enabled' => false,
            'name' => 'PayPal',
            'mode' => env('PAYPAL_MODE', 'sandbox'),
            'client_id' => env('PAYPAL_CLIENT_ID'),
            'secret' => env('PAYPAL_SECRET'),
        ],
    ],
];