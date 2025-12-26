<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'transbank' => [
        'commerce_code' => env('TRANSBANK_COMMERCE_CODE', '597055555532'), // Standard Integration Code
        'api_key' => env('TRANSBANK_API_KEY', '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C'), // Standard Integration Key
        'environment' => env('TRANSBANK_ENV', 'integration'), // integration or production
    ],

    'currencyapi' => [
        'key' => env('CURRENCY_API_KEY'),
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI', 'http://localhost:8058/auth/google/callback'),
    ],

    'chilexpress' => [
        'subscription_key' => env('CHILEXPRESS_SUBSCRIPTION_KEY'),
        'origin_comuna' => env('CHILEXPRESS_ORIGIN_COMUNA', 'SANTIAGO CENTRO'),
        'base_url' => env('CHILEXPRESS_BASE_URL', 'https://testservices.wschilexpress.com/rating/api/v1.0'),
    ],

    'starken' => [
        'base_url' => env('STARKEN_BASE_URL', 'https://restservices-qa.starken.cl/apiqa/starkenservices/rest'),
        'rut' => env('STARKEN_RUT', '76211240'),
        'clave' => env('STARKEN_CLAVE', 'key'), // Dummy for QA
        'token' => env('STARKEN_TOKEN', '30751eee-a1a5-4005-b28b-d60a0f91df6a'), // Dummy for QA
        'origin_code' => env('STARKEN_ORIGIN_CODE', 'CODIGO_DLS_ORIGEN'), // Needs to be mapped or set
    ],
];
