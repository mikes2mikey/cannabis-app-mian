<?php

return [
    /*
    |--------------------------------------------------------------------------
    | PayFast Settings
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials and configuration for PayFast.
    | For production, you should update these values in your .env file.
    |
    */

    // Merchant ID provided by PayFast
    'merchant_id' => env('PAYFAST_MERCHANT_ID', '10000100'),

    // Merchant Key provided by PayFast
    'merchant_key' => env('PAYFAST_MERCHANT_KEY', '46f0cd694581a'),

    // Passphrase for security (optional)
    'passphrase' => env('PAYFAST_PASSPHRASE', 'jt7NOE43FZPn'),

    // PayFast sandbox mode for testing
    'test_mode' => env('PAYFAST_TEST_MODE', true),

    // API endpoints
    'endpoints' => [
        // Sandbox endpoints
        'sandbox' => [
            'process' => env('PAYFAST_SANDBOX_PROCESS_URL', 'https://sandbox.payfast.co.za/eng/process'),
            'verify' => env('PAYFAST_SANDBOX_VERIFY_URL', 'https://sandbox.payfast.co.za/eng/query/validate'),
        ],
        // Live endpoints
        'live' => [
            'process' => env('PAYFAST_LIVE_PROCESS_URL', 'https://www.payfast.co.za/eng/process'),
            'verify' => env('PAYFAST_LIVE_VERIFY_URL', 'https://www.payfast.co.za/eng/query/validate'),
        ],
    ],

    // Server IP addresses to accept for ITN (Instant Transaction Notification)
    'valid_hosts' => [
        'www.payfast.co.za',
        'sandbox.payfast.co.za',
        'w1w.payfast.co.za',
        'w2w.payfast.co.za',
    ],
]; 