<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Resend, Postmark, AWS, and more. This file provides the de facto
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

    'gemini' => [
        'key' => env('GEMINI_API_KEY'),
    ],

    'openrouter' => [
        'key' => env('OPENROUTER_API_KEY'),
    ],

    'notchpay' => [
        'key' => env('NOTCHPAY_API_KEY'),
        'base_url' => env('NOTCHPAY_BASE_URL', 'https://api.notchpay.co'),
        'callback_url' => env('NOTCHPAY_CALLBACK_URL'),
        'garage_callback_url' => env('NOTCHPAY_GARAGE_CALLBACK_URL'),
        'payment_callback_url' => env('NOTCHPAY_PAYMENT_CALLBACK_URL'),
        'webhook_secret' => env('NOTCHPAY_WEBHOOK_SECRET'),
    ],

    'piiston_admin_email' => env('PIISTON_EMAIL', 'support@piiston.com'),

];
