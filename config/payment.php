<?php

    return [
        'API_KEY' => env('PAYMENT_API_KEY'),
        'MERCHANT_ID' => env('PAYMENT_MERCHANT_ID'),
        'PAYMENT_PAGE_CLIENT_ID' => env('PAYMENT_CLIENT_ID'),
        'BASE_URL' => env('PAYMENT_BASE_URL'),
        'ENABLE_LOGGING' => env('PAYMENT_ENABLE_LOGGING', false),
        'LOGGING_PATH' => storage_path('logs/payment.log'),
        'RESPONSE_KEY' => env('PAYMENT_RESPONSE_KEY'),
        'CA_PATH' => env('PAYMENT_CA_PATH'),
    ];
