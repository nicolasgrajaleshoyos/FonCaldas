<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Controlador de sesión predeterminado
    |--------------------------------------------------------------------------
    |
    | Esta opción determina el controlador de sesión predeterminado para las
    | peticiones entrantes. Laravel soporta varias opciones de almacenamiento
    | para persistir los datos de sesión. El almacenamiento en base de datos
    | es una buena opción por defecto.
    |
    | Soportado: "file", "cookie", "database", "memcached",
    |            "redis", "dynamodb", "array"
    |
    */

    'driver' => env('SESSION_DRIVER', 'database'),

    /*
    |--------------------------------------------------------------------------
    | Duración de la sesión
    |--------------------------------------------------------------------------
    |
    | Aquí puedes especificar el número de minutos que la sesión puede
    | permanecer inactiva antes de expirar. Si quieres que expire apenas se
    | cierre el navegador, indícalo con la opción de configuración
    | expire_on_close.
    |
    */

    'lifetime' => (int) env('SESSION_LIFETIME', 120),

    'expire_on_close' => env('SESSION_EXPIRE_ON_CLOSE', false),

    /*
    |--------------------------------------------------------------------------
    | Cifrado de la sesión
    |--------------------------------------------------------------------------
    |
    | Esta opción permite indicar fácilmente que todos los datos de sesión
    | se cifren antes de guardarse. El cifrado lo realiza Laravel
    | automáticamente y puedes usar la sesión con normalidad.
    |
    */

    'encrypt' => env('SESSION_ENCRYPT', false),

    /*
    |--------------------------------------------------------------------------
    | Ubicación de los archivos de sesión
    |--------------------------------------------------------------------------
    |
    | Cuando se usa el controlador de sesión "file", los archivos de sesión
    | se guardan en disco. Aquí se define la ubicación de almacenamiento
    | predeterminada; puedes indicar otra ubicación si lo prefieres.
    |
    */

    'files' => storage_path('framework/sessions'),

    /*
    |--------------------------------------------------------------------------
    | Conexión de base de datos de la sesión
    |--------------------------------------------------------------------------
    |
    | Cuando se usan los controladores de sesión "database" o "redis",
    | puedes especificar la conexión que gestionará estas sesiones. Debe
    | corresponder a una conexión de la configuración de base de datos.
    |
    */

    'connection' => env('SESSION_CONNECTION'),

    /*
    |--------------------------------------------------------------------------
    | Tabla de base de datos de la sesión
    |--------------------------------------------------------------------------
    |
    | Cuando se usa el controlador de sesión "database", puedes especificar
    | la tabla donde se guardan las sesiones. Ya se definió un valor
    | razonable por defecto; puedes cambiarlo por otra tabla.
    |
    */

    'table' => env('SESSION_TABLE', 'sessions'),

    /*
    |--------------------------------------------------------------------------
    | Almacén de caché de la sesión
    |--------------------------------------------------------------------------
    |
    | Cuando se usa un motor de sesión basado en la caché del framework,
    | puedes definir el almacén de caché que guardará los datos de sesión
    | entre peticiones. Debe coincidir con uno de tus almacenes definidos.
    |
    | Aplica a: "dynamodb", "memcached", "redis"
    |
    */

    'store' => env('SESSION_STORE'),

    /*
    |--------------------------------------------------------------------------
    | Sorteo de limpieza de sesiones
    |--------------------------------------------------------------------------
    |
    | Algunos controladores de sesión deben limpiar manualmente su
    | almacenamiento para eliminar sesiones antiguas. Aquí se define la
    | probabilidad de que ocurra en una petición dada. Por defecto son
    | 2 de cada 100.
    |
    */

    'lottery' => [2, 100],

    /*
    |--------------------------------------------------------------------------
    | Nombre de la cookie de sesión
    |--------------------------------------------------------------------------
    |
    | Aquí puedes cambiar el nombre de la cookie de sesión creada por el
    | framework. Normalmente no necesitas cambiarlo, ya que hacerlo no
    | aporta una mejora de seguridad significativa.
    |
    */

    'cookie' => env(
        'SESSION_COOKIE',
        Str::slug((string) env('APP_NAME', 'laravel')).'-session'
    ),

    /*
    |--------------------------------------------------------------------------
    | Ruta de la cookie de sesión
    |--------------------------------------------------------------------------
    |
    | La ruta de la cookie de sesión determina la ruta para la cual la
    | cookie se considera disponible. Normalmente es la raíz de tu
    | aplicación, pero puedes cambiarla cuando sea necesario.
    |
    */

    'path' => env('SESSION_PATH', '/'),

    /*
    |--------------------------------------------------------------------------
    | Dominio de la cookie de sesión
    |--------------------------------------------------------------------------
    |
    | Este valor determina el dominio y subdominios a los que está
    | disponible la cookie de sesión. Por defecto está disponible para el
    | dominio raíz sin subdominios. Normalmente no debería cambiarse.
    |
    */

    'domain' => env('SESSION_DOMAIN'),

    /*
    |--------------------------------------------------------------------------
    | Cookies solo por HTTPS
    |--------------------------------------------------------------------------
    |
    | Al establecer esta opción en true, las cookies de sesión solo se
    | devuelven al servidor si el navegador tiene una conexión HTTPS. Así se
    | evita enviar la cookie cuando no se puede hacer de forma segura.
    |
    */

    'secure' => env('SESSION_SECURE_COOKIE'),

    /*
    |--------------------------------------------------------------------------
    | Acceso solo por HTTP
    |--------------------------------------------------------------------------
    |
    | Establecer este valor en true impide que JavaScript acceda al valor
    | de la cookie, que solo será accesible mediante el protocolo HTTP. Es
    | poco probable que debas desactivar esta opción.
    |
    */

    'http_only' => env('SESSION_HTTP_ONLY', true),

    /*
    |--------------------------------------------------------------------------
    | Cookies Same-Site
    |--------------------------------------------------------------------------
    |
    | Esta opción determina cómo se comportan tus cookies cuando ocurren
    | peticiones entre sitios, y sirve para mitigar ataques CSRF. Por defecto
    | se establece en "lax" para permitir peticiones seguras entre sitios.
    |
    | Ver: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Set-Cookie#samesitesamesite-value
    |
    | Soportado: "lax", "strict", "none", null
    |
    */

    'same_site' => env('SESSION_SAME_SITE', 'lax'),

    /*
    |--------------------------------------------------------------------------
    | Cookies particionadas
    |--------------------------------------------------------------------------
    |
    | Establecer este valor en true vincula la cookie al sitio de nivel
    | superior en un contexto entre sitios. El navegador acepta las cookies
    | particionadas cuando están marcadas como "secure" y el atributo
    | Same-Site es "none".
    |
    */

    'partitioned' => env('SESSION_PARTITIONED_COOKIE', false),

    /*
    |--------------------------------------------------------------------------
    | Serialización de la sesión
    |--------------------------------------------------------------------------
    |
    | Este valor controla la estrategia de serialización de los datos de
    | sesión, que por defecto es JSON. Establecerlo en "php" permite guardar
    | objetos PHP en la sesión, pero puede hacer la aplicación vulnerable a
    | ataques de serialización de "cadena de gadgets" si se filtra la
    | APP_KEY.
    |
    | Soportado: "json", "php"
    |
    */

    'serialization' => 'json',

];
