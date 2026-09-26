<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Enviador de correo predeterminado
    |--------------------------------------------------------------------------
    |
    | Esta opción controla el enviador de correo predeterminado que se usa
    | para enviar todos los mensajes, a menos que se especifique otro de
    | forma explícita al enviar el mensaje. Los demás enviadores se pueden
    | configurar en el arreglo "mailers". Se incluyen ejemplos de cada tipo.
    |
    */

    'default' => env('MAIL_MAILER', 'log'),

    /*
    |--------------------------------------------------------------------------
    | Configuración de los enviadores de correo
    |--------------------------------------------------------------------------
    |
    | Aquí puedes configurar todos los enviadores de correo que usa tu
    | aplicación y sus ajustes. Se configuraron varios ejemplos y puedes
    | añadir los tuyos según lo requiera tu aplicación.
    |
    | Laravel soporta varios controladores de "transporte" de correo para
    | entregar un email. Puedes indicar cuál usas en tus enviadores más
    | abajo. También puedes añadir otros enviadores si lo necesitas.
    |
    | Soportado: "smtp", "sendmail", "mailgun", "ses", "ses-v2",
    |            "postmark", "resend", "log", "array",
    |            "failover", "roundrobin"
    |
    */

    'mailers' => [

        'smtp' => [
            'transport' => 'smtp',
            'scheme' => env('MAIL_SCHEME'),
            'url' => env('MAIL_URL'),
            'host' => env('MAIL_HOST', '127.0.0.1'),
            'port' => env('MAIL_PORT', 2525),
            'username' => env('MAIL_USERNAME'),
            'password' => env('MAIL_PASSWORD'),
            'timeout' => null,
            'local_domain' => env('MAIL_EHLO_DOMAIN', parse_url((string) env('APP_URL', 'http://localhost'), PHP_URL_HOST)),
        ],

        'ses' => [
            'transport' => 'ses',
        ],

        'postmark' => [
            'transport' => 'postmark',
            // 'message_stream_id' => env('POSTMARK_MESSAGE_STREAM_ID'),
            // 'client' => [
            //     'timeout' => 5,
            // ],
        ],

        'resend' => [
            'transport' => 'resend',
        ],

        'sendmail' => [
            'transport' => 'sendmail',
            'path' => env('MAIL_SENDMAIL_PATH', '/usr/sbin/sendmail -bs -i'),
        ],

        'log' => [
            'transport' => 'log',
            'channel' => env('MAIL_LOG_CHANNEL'),
        ],

        'array' => [
            'transport' => 'array',
        ],

        'failover' => [
            'transport' => 'failover',
            'mailers' => [
                'smtp',
                'log',
            ],
            'retry_after' => 60,
        ],

        'roundrobin' => [
            'transport' => 'roundrobin',
            'mailers' => [
                'ses',
                'postmark',
            ],
            'retry_after' => 60,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Dirección "From" global
    |--------------------------------------------------------------------------
    |
    | Puedes querer que todos los correos enviados por tu aplicación salgan
    | desde la misma dirección. Aquí puedes especificar un nombre y una
    | dirección que se usan globalmente para todos los correos enviados.
    |
    */

    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
        'name' => env('MAIL_FROM_NAME', env('APP_NAME', 'Laravel')),
    ],

];
