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
        // 'domain' => env('MAILGUN_DOMAIN'),
        // 'secret' => env('MAILGUN_SECRET'),
        // 'endpoint' => env('MAILGUN_ENDPOINT'),
        'domain' => 'mg.mmrandom.com',
        'secret' => 'key-20ea3a289bf9497ac9fe6a8af6fd99da',
        'endpoint' => 'api.eu.mailgun.net',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

];
