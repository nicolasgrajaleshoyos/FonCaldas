<?php

use App\Models\Pqrs;
use App\Models\Solicitud;
use App\Models\TramiteTipo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('radicar una solicitud sin los campos requeridos falla la validación', function () {
    $this->post('/tramites', [])
        ->assertSessionHasErrors(['tramite_tipo_id', 'asociado_nombre', 'asociado_documento', 'asociado_email', 'descripcion']);
});

test('radicar una solicitud con un tipo de trámite inexistente falla la validación', function () {
    $this->post('/tramites', [
        'tramite_tipo_id' => 9999, 'asociado_nombre' => 'Ana', 'asociado_documento' => '123',
        'asociado_email' => 'ana@test.co', 'descripcion' => 'x',
    ])->assertSessionHasErrors('tramite_tipo_id');
});

test('consultar una solicitud con datos correctos muestra su estado', function () {
    $tipo = TramiteTipo::create(['nombre' => 'T', 'slug' => 't', 'activo' => true, 'es_certificacion' => false]);
    $solicitud = Solicitud::create([
        'tramite_tipo_id' => $tipo->id, 'asociado_nombre' => 'Ana', 'asociado_documento' => '123',
        'asociado_email' => 'ana@test.co', 'descripcion' => 'x', 'estado' => Solicitud::ESTADO_RECIBIDA,
    ]);

    $this->post('/tramites/consultar', ['codigo' => $solicitud->codigo, 'asociado_documento' => '123'])
        ->assertOk()->assertSee($solicitud->codigo);
});

test('radicar una PQRS sin aceptar el tratamiento de datos falla la validación', function () {
    $this->post('/pqrs', [
        'tipo' => 'queja', 'nombre' => 'Ana', 'documento' => '123', 'email' => 'ana@test.co',
        'asunto' => 'Demora', 'descripcion' => 'Detalle',
    ])->assertSessionHasErrors('acepta_datos');
});

test('radicar una PQRS con un tipo inválido falla la validación', function () {
    $this->post('/pqrs', [
        'tipo' => 'no-existe', 'nombre' => 'Ana', 'documento' => '123', 'email' => 'ana@test.co',
        'asunto' => 'Demora', 'descripcion' => 'Detalle', 'acepta_datos' => '1',
    ])->assertSessionHasErrors('tipo');
});

test('consultar una PQRS con datos que no existen muestra un error', function () {
    $this->post('/pqrs/consultar', ['codigo' => 'PQ-26-INEXISTENTE', 'documento' => '000'])
        ->assertSessionHasErrors('codigo');
});

test('subir un documento de transparencia que no sea PDF falla la validación', function () {
    Storage::fake('public');
    $admin = User::factory()->create(['is_super_admin' => true, 'is_active' => true]);

    $this->actingAs($admin)->post('/admin/documentos', [
        'title' => 'Reglamento', 'description' => 'd', 'category' => 'normativa',
        'document' => UploadedFile::fake()->create('r.txt', 10, 'text/plain'),
    ])->assertSessionHasErrors('document');
});

test('crear un evento con un archivo de tipo no soportado falla la validación', function () {
    Storage::fake('public');
    $admin = User::factory()->create(['is_super_admin' => true, 'is_active' => true]);

    $this->actingAs($admin)->post('/admin/eventos', [
        'title' => 'Asamblea', 'description' => 'd', 'location' => 'Manizales',
        'event_date' => '2026-10-01', 'event_time' => '10:00',
        'media' => UploadedFile::fake()->create('archivo.txt', 10, 'text/plain'),
    ])->assertSessionHasErrors('media');
});
