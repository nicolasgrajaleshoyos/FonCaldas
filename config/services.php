<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Servicios de terceros
    |--------------------------------------------------------------------------
    |
    | Este archivo sirve para guardar las credenciales de servicios de
    | terceros como Mailgun, Postmark, AWS y más. Es la ubicación estándar
    | para este tipo de información, de modo que los paquetes tengan un
    | archivo convencional donde encontrar las credenciales.
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

];
