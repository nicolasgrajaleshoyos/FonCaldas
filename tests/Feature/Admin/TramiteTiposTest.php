<?php

use App\Models\TramiteTipo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('crear un tipo de trámite genera su slug y queda activo', function () {
    $super = User::factory()->create(['is_super_admin' => true, 'is_active' => true]);

    $this->actingAs($super)->post('/admin/tipos-tramite', [
        'nombre' => 'Certificado de Ahorros', 'descripcion' => 'd', 'es_certificacion' => '1',
    ])->assertSessionHasNoErrors();

    $tipo = TramiteTipo::firstOrFail();
    expect($tipo->slug)->toBe('certificado-de-ahorros')
        ->and($tipo->activo)->toBeTrue()
        ->and($tipo->es_certificacion)->toBeTrue();
});

test('toggleActive alterna el estado activo del tipo de trámite', function () {
    $super = User::factory()->create(['is_super_admin' => true, 'is_active' => true]);
    $tipo = TramiteTipo::create(['nombre' => 'T', 'slug' => 't', 'activo' => true, 'es_certificacion' => false]);

    $this->actingAs($super)->post("/admin/tipos-tramite/{$tipo->id}/toggle");
    expect($tipo->fresh()->activo)->toBeFalse();

    $this->actingAs($super)->post("/admin/tipos-tramite/{$tipo->id}/toggle");
    expect($tipo->fresh()->activo)->toBeTrue();
});
