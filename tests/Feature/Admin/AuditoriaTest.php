<?php

use App\Models\Solicitud;
use App\Models\TramiteTipo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function solicitudDeTipo(TramiteTipo $tipo): Solicitud
{
    return Solicitud::create([
        'tramite_tipo_id' => $tipo->id, 'asociado_nombre' => 'Ana', 'asociado_documento' => '123',
        'asociado_email' => 'ana@test.co', 'descripcion' => 'x', 'estado' => Solicitud::ESTADO_RECIBIDA,
    ]);
}

test('solo se listan eventos de los tipos de trámite visibles para el usuario', function () {
    $propio = TramiteTipo::create(['nombre' => 'Propio', 'slug' => 'propio', 'activo' => true, 'es_certificacion' => false]);
    $ajeno = TramiteTipo::create(['nombre' => 'Ajeno', 'slug' => 'ajeno', 'activo' => true, 'es_certificacion' => false]);
    $user = User::factory()->create(['is_super_admin' => false, 'is_active' => true]);
    $user->tramiteTipos()->sync([$propio->id]);

    $solicitudPropia = solicitudDeTipo($propio);
    $solicitudPropia->registrarEvento('solicitud_creada');
    $solicitudAjena = solicitudDeTipo($ajeno);
    $solicitudAjena->registrarEvento('solicitud_creada');

    $this->actingAs($user)->get('/admin/auditoria')->assertOk()
        ->assertViewHas('eventos', fn ($p) => $p->total() === 1 && $p->first()->solicitud_id === $solicitudPropia->id);
});

test('el filtro por código busca coincidencias parciales en la solicitud', function () {
    $super = User::factory()->create(['is_super_admin' => true, 'is_active' => true]);
    $tipo = TramiteTipo::create(['nombre' => 'T', 'slug' => 't', 'activo' => true, 'es_certificacion' => false]);
    $solicitud = solicitudDeTipo($tipo);
    $solicitud->registrarEvento('solicitud_creada');

    $this->actingAs($super)->get('/admin/auditoria?codigo='.substr($solicitud->codigo, 0, 5))
        ->assertOk()->assertViewHas('eventos', fn ($p) => $p->total() === 1);

    $this->actingAs($super)->get('/admin/auditoria?codigo=NOEXISTE')
        ->assertOk()->assertViewHas('eventos', fn ($p) => $p->total() === 0);
});

test('el filtro por usuario solo muestra eventos ejecutados por ese usuario', function () {
    $super = User::factory()->create(['is_super_admin' => true, 'is_active' => true]);
    $otro = User::factory()->create(['is_super_admin' => true, 'is_active' => true]);
    $tipo = TramiteTipo::create(['nombre' => 'T', 'slug' => 't', 'activo' => true, 'es_certificacion' => false]);
    $solicitud = solicitudDeTipo($tipo);
    $solicitud->registrarEvento('identidad_verificada', userId: $super->id);
    $solicitud->registrarEvento('solicitud_aprobada', userId: $otro->id);

    $this->actingAs($super)->get("/admin/auditoria?user_id={$super->id}")
        ->assertOk()->assertViewHas('eventos', fn ($p) => $p->total() === 1 && $p->first()->user_id === $super->id);
});
