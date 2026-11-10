<?php

use App\Models\User;
use App\Services\Archivos\AlmacenArchivosDiscoPublico;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('las respuestas web incluyen las cabeceras de seguridad', function () {
    $this->get('/')
        ->assertHeader('X-Content-Type-Options', 'nosniff')
        ->assertHeader('X-Frame-Options', 'SAMEORIGIN')
        ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
});

test('el inicio de sesión se bloquea tras varios intentos fallidos del mismo usuario', function () {
    User::factory()->create(['username' => 'func', 'password' => 'correcta123', 'is_active' => true]);

    // Cada intento llega desde una IP distinta para que solo actúe el límite por usuario, no el de la ruta.
    foreach (range(1, 10) as $i) {
        $this->withServerVariables(['REMOTE_ADDR' => "10.0.0.{$i}"])->post('/admin/login', ['username' => 'func', 'password' => 'mala']);
    }

    // Con la misma cuenta, ni la clave correcta entra mientras dure el bloqueo.
    $this->withServerVariables(['REMOTE_ADDR' => '10.0.0.99'])->post('/admin/login', ['username' => 'func', 'password' => 'correcta123']);
    $this->assertGuest();
});

test('un archivo público toma la extensión de su contenido real y no la del nombre enviado', function () {
    Storage::fake('public');
    $ruta = tempnam(sys_get_temp_dir(), 'png');
    file_put_contents($ruta, base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg=='));
    $png = new UploadedFile($ruta, 'inocente.html', 'text/html', null, true);

    $guardada = app(AlmacenArchivosDiscoPublico::class)->guardar($png, 'uploads/prueba');

    expect($guardada)->toEndWith('.png')->not->toContain('.html');
});

test('los eventos ya no aceptan SVG', function () {
    $user = User::factory()->create(['is_super_admin' => true, 'is_active' => true]);
    $svg = UploadedFile::fake()->createWithContent('x.svg', '<svg xmlns="http://www.w3.org/2000/svg"><script>alert(1)</script></svg>');

    $this->actingAs($user)->post('/admin/eventos', [
        'title' => 'T', 'description' => 'D', 'location' => 'L',
        'event_date' => '2026-12-01', 'event_time' => '10:00', 'media' => $svg,
    ])->assertSessionHasErrors('media');
});
