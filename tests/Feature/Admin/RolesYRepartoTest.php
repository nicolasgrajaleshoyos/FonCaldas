<?php

use App\Models\Solicitud;
use App\Models\TramiteTipo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

function usuarioRol(bool $super, array $tipos = []): User
{
    $u = User::factory()->create(['is_super_admin' => $super, 'is_active' => true, 'username' => 'u'.Str::random(8)]);
    $u->tramiteTipos()->sync($tipos);

    return $u;
}

function tipoReparto(string $nombre): TramiteTipo
{
    return TramiteTipo::create(['nombre' => $nombre, 'slug' => str($nombre)->slug(), 'activo' => true, 'es_certificacion' => false]);
}

function abiertaPara(TramiteTipo $tipo, User $responsable): Solicitud
{
    return Solicitud::create([
        'tramite_tipo_id' => $tipo->id, 'asociado_nombre' => 'Ana', 'asociado_documento' => '1',
        'asociado_email' => 'ana@test.co', 'descripcion' => 'x', 'estado' => Solicitud::ESTADO_RECIBIDA,
        'asignado_a' => $responsable->id,
    ]);
}

test('un administrador secundario no puede crear administradores ni ascender a un funcionario', function () {
    usuarioRol(true); // propietario (el primero)
    $secundario = usuarioRol(true);
    $funcionario = usuarioRol(false);

    $this->actingAs($secundario)->post('/admin/usuarios', [
        'name' => 'Nuevo', 'username' => 'nuevo', 'email' => 'nuevo@test.co', 'password' => 'secreto123', 'is_super_admin' => '1',
    ])->assertRedirect();
    expect(User::where('username', 'nuevo')->value('is_super_admin'))->toBeFalse();

    $this->actingAs($secundario)->put("/admin/usuarios/{$funcionario->id}", [
        'name' => $funcionario->name, 'username' => $funcionario->username, 'email' => $funcionario->email, 'is_super_admin' => '1',
    ])->assertRedirect();
    expect($funcionario->fresh()->is_super_admin)->toBeFalse();

    // El formulario ni siquiera le muestra la casilla.
    $this->actingAs($secundario)->get('/admin/usuarios/nuevo')->assertDontSee('name="is_super_admin"', false);
});

test('el administrador original sí puede otorgar el rol de administrador', function () {
    $propietario = usuarioRol(true);
    $funcionario = usuarioRol(false);

    $this->actingAs($propietario)->put("/admin/usuarios/{$funcionario->id}", [
        'name' => $funcionario->name, 'username' => $funcionario->username, 'email' => $funcionario->email, 'is_super_admin' => '1',
    ])->assertRedirect();

    expect($funcionario->fresh()->is_super_admin)->toBeTrue();
});

test('al desactivar a un funcionario sus solicitudes abiertas pasan a otro responsable; las cerradas no se tocan', function () {
    Mail::fake();
    $propietario = usuarioRol(true);
    $tipo = tipoReparto('Ahorro');
    $saliente = usuarioRol(false, [$tipo->id]);
    $otro = usuarioRol(false, [$tipo->id]);

    $abierta = abiertaPara($tipo, $saliente);
    $cerrada = abiertaPara($tipo, $saliente);
    $cerrada->update(['estado' => Solicitud::ESTADO_FINALIZADA]);

    $this->actingAs($propietario)->post("/admin/usuarios/{$saliente->id}/toggle")->assertSessionHas('success');

    expect($abierta->fresh()->asignado_a)->toBe($otro->id)
        ->and($cerrada->fresh()->asignado_a)->toBe($saliente->id)
        ->and($abierta->eventos()->where('accion', 'solicitud_reasignada')->exists())->toBeTrue();
});

test('al quitarle un tipo de trámite a un funcionario, sus solicitudes abiertas de ese tipo se reasignan', function () {
    Mail::fake();
    $propietario = usuarioRol(true);
    $ahorro = tipoReparto('Ahorro');
    $credito = tipoReparto('Credito');
    $funcionario = usuarioRol(false, [$ahorro->id, $credito->id]);

    $deAhorro = abiertaPara($ahorro, $funcionario);
    $deCredito = abiertaPara($credito, $funcionario);

    $this->actingAs($propietario)->put("/admin/usuarios/{$funcionario->id}", [
        'name' => $funcionario->name, 'username' => $funcionario->username, 'email' => $funcionario->email,
        'tramite_tipos' => [$credito->id],
    ])->assertRedirect();

    // Ahorro ya no es suyo: pasa al administrador (único responsable restante); Crédito lo conserva.
    expect($deAhorro->fresh()->asignado_a)->toBe($propietario->id)
        ->and($deCredito->fresh()->asignado_a)->toBe($funcionario->id);
});

test('al eliminar a un funcionario sus solicitudes abiertas no quedan sin responsable', function () {
    Mail::fake();
    $propietario = usuarioRol(true);
    $tipo = tipoReparto('Ahorro');
    $saliente = usuarioRol(false, [$tipo->id]);
    $abierta = abiertaPara($tipo, $saliente);

    $this->actingAs($propietario)->post("/admin/usuarios/{$saliente->id}/delete")->assertSessionHas('success');

    expect(User::find($saliente->id))->toBeNull()
        ->and($abierta->fresh()->asignado_a)->toBe($propietario->id);
});

test('solo el propietario puede archivar la auditoría y eliminar solicitudes o PQRS', function () {
    $propietario = usuarioRol(true);
    $secundario = usuarioRol(true);
    $solicitud = abiertaPara(tipoReparto('Ahorros'), $propietario);

    $this->actingAs($secundario)->post('/admin/auditoria/vaciar')->assertForbidden();
    $this->actingAs($secundario)->post('/admin/auditoria/eliminar', ['ids' => [1]])->assertForbidden();
    $this->actingAs($secundario)->post("/admin/solicitudes/{$solicitud->id}/delete")->assertForbidden();
    $this->actingAs($secundario)->get("/admin/solicitudes/{$solicitud->id}")->assertOk()->assertDontSee('Eliminar solicitud');
    expect($solicitud->fresh()->trashed())->toBeFalse();

    $this->actingAs($propietario)->get("/admin/solicitudes/{$solicitud->id}")->assertSee('Eliminar solicitud');
    $this->actingAs($propietario)->post("/admin/solicitudes/{$solicitud->id}/delete")->assertRedirect();
    expect($solicitud->fresh()->trashed())->toBeTrue();
});
