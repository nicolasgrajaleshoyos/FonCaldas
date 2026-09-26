<?php

use App\Models\User;

return [

    /*
    |--------------------------------------------------------------------------
    | Valores predeterminados de autenticación
    |--------------------------------------------------------------------------
    |
    | Esta opción define el "guard" de autenticación y el "broker" de
    | restablecimiento de contraseña predeterminados de tu aplicación. Puedes
    | cambiarlos según lo necesites, pero son un buen punto de partida.
    |
    */

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Guards de autenticación
    |--------------------------------------------------------------------------
    |
    | A continuación puedes definir cada guard de autenticación de tu
    | aplicación. Ya se definió una buena configuración por defecto que usa
    | almacenamiento en sesión y el proveedor de usuarios Eloquent.
    |
    | Todos los guards tienen un proveedor de usuarios, que define cómo se
    | obtienen los usuarios de tu base de datos u otro sistema de
    | almacenamiento. Normalmente se utiliza Eloquent.
    |
    | Soportado: "session"
    |
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Proveedores de usuarios
    |--------------------------------------------------------------------------
    |
    | Todos los guards de autenticación tienen un proveedor de usuarios, que
    | define cómo se obtienen los usuarios de tu base de datos u otro sistema
    | de almacenamiento. Normalmente se utiliza Eloquent.
    |
    | Si tienes varias tablas o modelos de usuarios, puedes configurar varios
    | proveedores que representen el modelo / tabla. Luego se pueden asignar
    | a cualquier guard de autenticación adicional que hayas definido.
    |
    | Soportado: "database", "eloquent"
    |
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => env('AUTH_MODEL', User::class),
        ],

        // 'users' => [
        //     'driver' => 'database',
        //     'table' => 'users',
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Restablecimiento de contraseñas
    |--------------------------------------------------------------------------
    |
    | Estas opciones especifican el comportamiento del restablecimiento de
    | contraseña de Laravel, incluida la tabla usada para guardar los tokens
    | y el proveedor de usuarios que se invoca para obtener a los usuarios.
    |
    | El tiempo de expiración es el número de minutos que cada token de
    | restablecimiento se considera válido. Esta medida de seguridad mantiene
    | los tokens de corta vida para que tengan menos tiempo de ser adivinados.
    | Puedes cambiarlo según lo necesites.
    |
    | El límite de frecuencia es el número de segundos que un usuario debe
    | esperar antes de generar más tokens de restablecimiento. Esto evita que
    | genere rápidamente una gran cantidad de tokens.
    |
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Tiempo de espera de confirmación de contraseña
    |--------------------------------------------------------------------------
    |
    | Aquí puedes definir el número de segundos antes de que expire la
    | ventana de confirmación de contraseña y se pida al usuario volver a
    | ingresarla en la pantalla de confirmación. Por defecto dura tres horas.
    |
    */

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
