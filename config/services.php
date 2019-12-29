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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'telegram' => [
        'general-telegram-bot-token' => env('GENERAL_TELEGRAM_BOT_TOKEN'),
        'general-telegram-bot-username' => env('GENERAL_TELEGRAM_BOT_USERNAME'),
        'error-telegram-bot-token' => env('ERROR_TELEGRAM_BOT_TOKEN'),
        'telegram-id-user-error' => env('TELEGRAM_ID_USER_ERROR'),
        'webhook-url' => env('TELEGRAM_WEBHOOK_URL'),
    ],
];
