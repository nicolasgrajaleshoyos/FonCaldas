<?php

use App\Models\Solicitud;
use App\Models\TramiteTipo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('puedeVer: un super admin ve cualquier tipo, un funcionario solo los asignados', function () {
    $tipo = TramiteTipo::create(['nombre' => 'T', 'slug' => 't', 'activo' => true, 'es_certificacion' => false]);
    $ajeno = TramiteTipo::create(['nombre' => 'A', 'slug' => 'a', 'activo' => true, 'es_certificacion' => false]);

    $super = User::factory()->create(['is_super_admin' => true]);
    $funcionario = User::factory()->create(['is_super_admin' => false]);
    $funcionario->tramiteTipos()->sync([$tipo->id]);

    expect($super->puedeVer($tipo))->toBeTrue()
        ->and($super->puedeVer($ajeno))->toBeTrue()
        ->and($funcionario->puedeVer($tipo))->toBeTrue()
        ->and($funcionario->puedeVer($ajeno))->toBeFalse();
});

test('tramiteTiposVisibles: el super admin ve todos, el funcionario solo los suyos', function () {
    $tipo = TramiteTipo::create(['nombre' => 'T', 'slug' => 't', 'activo' => true, 'es_certificacion' => false]);
    $ajeno = TramiteTipo::create(['nombre' => 'A', 'slug' => 'a', 'activo' => true, 'es_certificacion' => false]);

    $super = User::factory()->create(['is_super_admin' => true]);
    $funcionario = User::factory()->create(['is_super_admin' => false]);
    $funcionario->tramiteTipos()->sync([$tipo->id]);

    expect($super->tramiteTiposVisibles()->pluck('id')->sort()->values()->all())->toBe([$tipo->id, $ajeno->id])
        ->and($funcionario->tramiteTiposVisibles()->pluck('id')->all())->toBe([$tipo->id]);
});

test('scopeResponsablesDe excluye inactivos y funcionarios de otro tipo, pero incluye super admins', function () {
    $tipo = TramiteTipo::create(['nombre' => 'T', 'slug' => 't', 'activo' => true, 'es_certificacion' => false]);
    $otro = TramiteTipo::create(['nombre' => 'Otro', 'slug' => 'otro', 'activo' => true, 'es_certificacion' => false]);

    $super = User::factory()->create(['is_super_admin' => true, 'is_active' => true]);
    $propio = User::factory()->create(['is_super_admin' => false, 'is_active' => true]);
    $propio->tramiteTipos()->sync([$tipo->id]);
    $inactivo = User::factory()->create(['is_super_admin' => false, 'is_active' => false]);
    $inactivo->tramiteTipos()->sync([$tipo->id]);
    $ajeno = User::factory()->create(['is_super_admin' => false, 'is_active' => true]);
    $ajeno->tramiteTipos()->sync([$otro->id]);

    expect(User::responsablesDe($tipo->id)->pluck('id')->sort()->values()->all())
        ->toBe(collect([$super->id, $propio->id])->sort()->values()->all());
});

test('responsableSugerido prefiere al funcionario con menos carga sobre el super admin', function () {
    $tipo = TramiteTipo::create(['nombre' => 'T', 'slug' => 't', 'activo' => true, 'es_certificacion' => false]);
    User::factory()->create(['is_super_admin' => true, 'is_active' => true]);
    $libre = User::factory()->create(['is_super_admin' => false, 'is_active' => true]);
    $libre->tramiteTipos()->sync([$tipo->id]);
    $ocupado = User::factory()->create(['is_super_admin' => false, 'is_active' => true]);
    $ocupado->tramiteTipos()->sync([$tipo->id]);
    Solicitud::create([
        'tramite_tipo_id' => $tipo->id, 'asociado_nombre' => 'Ana', 'asociado_documento' => '1',
        'asociado_email' => 'a@a.co', 'descripcion' => 'x', 'estado' => Solicitud::ESTADO_RECIBIDA, 'asignado_a' => $ocupado->id,
    ]);

    expect(User::responsableSugerido($tipo->id)->id)->toBe($libre->id);
});

test('responsableSugerido cae al super admin si ningún funcionario atiende el tipo', function () {
    $tipo = TramiteTipo::create(['nombre' => 'T', 'slug' => 't', 'activo' => true, 'es_certificacion' => false]);
    $super = User::factory()->create(['is_super_admin' => true, 'is_active' => true]);

    expect(User::responsableSugerido($tipo->id)->id)->toBe($super->id);
});

test('responsableSugerido devuelve null si no hay responsables activos', function () {
    $tipo = TramiteTipo::create(['nombre' => 'T', 'slug' => 't', 'activo' => true, 'es_certificacion' => false]);
    $inactivo = User::factory()->create(['is_super_admin' => true, 'is_active' => false]);

    expect(User::responsableSugerido($tipo->id))->toBeNull();
});
