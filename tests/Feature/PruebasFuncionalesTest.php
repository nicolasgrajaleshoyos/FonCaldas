<?php

/*
 * Ejecución de los casos de prueba funcionales PF-01 a PF-30 del «Plan y Registro de Pruebas Funcionales».
 * Cada prueba sigue los pasos del caso a través de las rutas reales del portal y del panel.
 * PF-25 (uso en celular) y PF-26 (modo oscuro) son visuales y se verifican en el navegador.
 */

use App\Mail\NuevaSolicitudAdmin;
use App\Mail\PqrsNotificacion;
use App\Mail\SolicitudEstadoCambiado;
use App\Models\Pqrs;
use App\Models\Solicitud;
use App\Models\TramiteTipo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

beforeEach(function () {
    Mail::fake();
    Storage::fake('local');
});

function pfUsuario(bool $super = false, array $tipos = [], array $extra = []): User
{
    $u = User::factory()->create($extra + ['is_super_admin' => $super, 'is_active' => true, 'username' => 'u'.Str::random(8)]);
    $u->tramiteTipos()->sync($tipos);

    return $u;
}

function pfTipo(string $nombre = 'Certificación de aportes', bool $certificacion = false): TramiteTipo
{
    return TramiteTipo::create(['nombre' => $nombre, 'slug' => str($nombre)->slug(), 'activo' => true, 'es_certificacion' => $certificacion]);
}

function pfDatos(TramiteTipo $tipo, array $extra = []): array
{
    return [
        'tramite_tipo_id' => $tipo->id, 'asociado_nombre' => 'Ana Gómez', 'asociado_documento' => '1053000111',
        'asociado_email' => 'ana@test.co', 'descripcion' => 'Necesito la certificación de mis aportes.',
    ] + $extra;
}

/** Radica por el formulario público y devuelve la solicitud creada. */
function pfRadicar($test, TramiteTipo $tipo, array $extra = []): Solicitud
{
    // Se sigue la redirección como el navegador, para que el aviso con el código no aparezca en la siguiente página.
    $test->followingRedirects()->post('/tramites', pfDatos($tipo, $extra))->assertOk()->assertSee('Tu solicitud fue radicada');

    return Solicitud::latest('id')->firstOrFail();
}

test('PF-01 radicar una solicitud sin iniciar sesión', function () {
    $tipo = pfTipo();
    $responsable = pfUsuario(false, [$tipo->id]);

    $this->get('/tramites')->assertOk()->assertSee('Certificación de aportes');
    $this->followingRedirects()->post('/tramites', pfDatos($tipo, [
        'documentos' => [UploadedFile::fake()->create('cedula.pdf', 200, 'application/pdf')],
    ]))->assertOk()->assertSee('Tu solicitud fue radicada con el código');

    $s = Solicitud::firstOrFail();
    expect($s->codigo)->toMatch('/^FC-\d{2}-[A-Z0-9]{6}$/')->and($s->documentos)->toHaveCount(1);
    Mail::assertSent(SolicitudEstadoCambiado::class, fn ($m) => $m->hasTo('ana@test.co'));
    Mail::assertSent(NuevaSolicitudAdmin::class, fn ($m) => $m->hasTo($responsable->email));
});

test('PF-02 validación de campos obligatorios', function () {
    $this->followingRedirects()->post('/tramites', [])->assertOk()->assertSee('es obligatorio');
    expect(Solicitud::count())->toBe(0);
});

test('PF-03 archivo no permitido', function (string $nombre, int $kb, string $mime) {
    $this->post('/tramites', pfDatos(pfTipo(), ['documentos' => [UploadedFile::fake()->create($nombre, $kb, $mime)]]))
        ->assertSessionHasErrors('documentos.0');
    expect(Solicitud::count())->toBe(0);
})->with([
    'ejecutable .exe' => ['programa.exe', 10, 'application/x-msdownload'],
    'imagen .svg' => ['imagen.svg', 10, 'image/svg+xml'],
    'PDF mayor a 10 MB' => ['grande.pdf', 10241, 'application/pdf'],
]);

test('PF-04 tipo de trámite desactivado', function () {
    $tipo = pfTipo('Retiro de ahorros');
    $this->actingAs(pfUsuario(true))->post("/admin/tipos-tramite/{$tipo->id}/toggle")->assertSessionHas('success');
    auth()->logout();

    $this->get('/tramites')->assertOk()->assertDontSee('Retiro de ahorros');
    $this->post('/tramites', pfDatos($tipo))->assertSessionHasErrors('tramite_tipo_id');
    expect(Solicitud::count())->toBe(0);
});

test('PF-05 consultar estado con datos correctos', function () {
    $s = pfRadicar($this, pfTipo());

    $this->followingRedirects()->post('/tramites/consultar', ['codigo' => $s->codigo, 'asociado_documento' => '1053000111'])
        ->assertOk()->assertSee($s->codigo)->assertSee('Recibida')->assertSee('¡Recibimos tu solicitud!');
});

test('PF-06 consultar con documento incorrecto', function () {
    $s = pfRadicar($this, pfTipo());

    $this->followingRedirects()->post('/tramites/consultar', ['codigo' => $s->codigo, 'asociado_documento' => '999'])
        ->assertOk()->assertSee('No encontramos una solicitud con esos datos');
});

test('PF-07 adjuntar documentos a solicitud abierta', function () {
    $s = pfRadicar($this, pfTipo());
    $url = URL::temporarySignedRoute('solicitudes.documentos.store', now()->addHour(), $s);

    $this->followingRedirects()->post($url, ['documentos' => [
        UploadedFile::fake()->create('extracto.pdf', 50), UploadedFile::fake()->create('soporte.png', 50, 'image/png'),
    ]])->assertOk()->assertSee('Documentos que adjuntaste')->assertSee('extracto.pdf')->assertSee('soporte.png');

    expect($s->eventos()->where('accion', 'documento_adjuntado')->exists())->toBeTrue();
});

test('PF-08 acceso al panel con funcionario activo y desactivado', function () {
    pfUsuario(false, [], ['username' => 'activo', 'password' => 'Clave12345']);
    pfUsuario(false, [], ['username' => 'inactivo', 'password' => 'Clave12345', 'is_active' => false]);

    $this->post('/admin/login', ['username' => 'activo', 'password' => 'Clave12345'])->assertRedirect(route('admin.dashboard'));
    $this->assertAuthenticated();
    $this->post('/admin/logout');

    $this->post('/admin/login', ['username' => 'inactivo', 'password' => 'Clave12345'])->assertRedirect(route('admin'));
    $this->assertGuest();
});

test('PF-09 bloqueo por intentos fallidos', function () {
    pfUsuario(false, [], ['username' => 'luis', 'password' => 'Clave12345']);

    foreach (range(1, 5) as $_) {
        $this->post('/admin/login', ['username' => 'luis', 'password' => 'errada'])->assertRedirect(route('admin'));
    }
    $this->post('/admin/login', ['username' => 'luis', 'password' => 'errada'])->assertStatus(429);
    // Ni siquiera la contraseña correcta entra mientras dura el bloqueo.
    $this->post('/admin/login', ['username' => 'luis', 'password' => 'Clave12345'])->assertStatus(429);
    $this->assertGuest();
});

test('PF-10 alcance por tipo de trámite', function () {
    $creditos = pfTipo('Créditos');
    $ahorro = pfTipo('Ahorro');
    $deCreditos = pfRadicar($this, $creditos);
    $deAhorro = pfRadicar($this, $ahorro);
    $funcionario = pfUsuario(false, [$creditos->id]);

    $this->actingAs($funcionario)->get("/admin/solicitudes/{$deAhorro->id}")->assertForbidden();
    $this->actingAs($funcionario)->get('/admin/solicitudes')->assertOk()
        ->assertSee($deCreditos->codigo)->assertDontSee($deAhorro->codigo);
});

test('PF-11 verificar identidad', function () {
    $tipo = pfTipo();
    $funcionario = pfUsuario(false, [$tipo->id]);
    $s = pfRadicar($this, $tipo);

    $this->actingAs($funcionario)->post("/admin/solicitudes/{$s->id}/verificar")->assertSessionHas('success');

    expect($s->fresh())->estado->toBe(Solicitud::ESTADO_EN_REVISION)
        ->verificado_por->toBe($funcionario->id)->verificado_at->not->toBeNull();
    Mail::assertSent(SolicitudEstadoCambiado::class, fn ($m) => $m->hasTo('ana@test.co') && $m->solicitud->estado === Solicitud::ESTADO_EN_REVISION);
});

test('PF-12 rechazar sin verificar', function () {
    $tipo = pfTipo();
    $propietario = pfUsuario(true);
    $s = pfRadicar($this, $tipo);

    $this->actingAs($propietario)->get("/admin/solicitudes/{$s->id}")->assertSee('Rechazar: identidad no coincide');
    $this->actingAs($propietario)->post("/admin/solicitudes/{$s->id}/rechazar", ['justificacion' => 'El documento no corresponde al asociado']);

    expect($s->fresh())->estado->toBe(Solicitud::ESTADO_RECHAZADA)->verificado_at->toBeNull();
    $this->actingAs($propietario)->get('/admin/auditoria?codigo='.$s->codigo)->assertOk()->assertSee('sin verificar la identidad');
});

test('PF-13 aprobar solicitud verificada', function () {
    $tipo = pfTipo();
    $f = pfUsuario(false, [$tipo->id]);
    $s = pfRadicar($this, $tipo);
    $this->actingAs($f)->post("/admin/solicitudes/{$s->id}/verificar");

    $this->actingAs($f)->post("/admin/solicitudes/{$s->id}/aprobar")->assertSessionHasNoErrors();

    expect($s->fresh()->estado)->toBe(Solicitud::ESTADO_APROBADA);
    Mail::assertSent(SolicitudEstadoCambiado::class, fn ($m) => $m->hasTo('ana@test.co') && $m->solicitud->estado === Solicitud::ESTADO_APROBADA);
});

test('PF-14 aprobar sin verificar', function () {
    $tipo = pfTipo();
    $s = pfRadicar($this, $tipo);

    $this->actingAs(pfUsuario(false, [$tipo->id]))->post("/admin/solicitudes/{$s->id}/aprobar")->assertSessionHasErrors('estado');
    expect($s->fresh()->estado)->toBe(Solicitud::ESTADO_RECIBIDA);
});

test('PF-15 publicar certificado con versiones', function () {
    $tipo = pfTipo();
    $f = pfUsuario(false, [$tipo->id]);
    $s = pfRadicar($this, $tipo);

    foreach (['certificado-v1.pdf', 'certificado-v2.pdf'] as $nombre) {
        $this->actingAs($f)->post("/admin/solicitudes/{$s->id}/documentos", ['documento' => UploadedFile::fake()->create($nombre, 20)])->assertSessionHasNoErrors();
    }
    auth()->logout();

    expect($s->documentos()->where('origen', 'admin')->pluck('version')->all())->toBe([1, 2]);
    $html = $this->followingRedirects()->post('/tramites/consultar', ['codigo' => $s->codigo, 'asociado_documento' => '1053000111'])->assertOk()->getContent();
    expect(substr_count($html, '>Vigente<'))->toBe(1)
        ->and(strpos($html, 'certificado-v2.pdf'))->toBeLessThan(strpos($html, '>Vigente<'))
        ->and(strpos($html, '>Vigente<'))->toBeLessThan(strpos($html, 'certificado-v1.pdf'));
});

test('PF-16 descargar certificado y caducidad del enlace', function () {
    $tipo = pfTipo();
    $s = pfRadicar($this, $tipo);
    $this->actingAs(pfUsuario(false, [$tipo->id]))->post("/admin/solicitudes/{$s->id}/documentos", ['documento' => UploadedFile::fake()->create('certificado.pdf', 20)]);
    auth()->logout();

    $html = $this->followingRedirects()->post('/tramites/consultar', ['codigo' => $s->codigo, 'asociado_documento' => '1053000111'])->getContent();
    preg_match('#href="([^"]*/tramites/documentos/\d+\?[^"]+)"#', $html, $m);
    $enlace = html_entity_decode($m[1]);

    $this->get($enlace)->assertOk();
    $this->travel(31)->minutes();
    $this->get($enlace)->assertForbidden();
});

test('PF-17 finalizar solicitud aprobada', function () {
    $tipo = pfTipo();
    $f = pfUsuario(false, [$tipo->id]);
    $s = pfRadicar($this, $tipo);
    $this->actingAs($f)->post("/admin/solicitudes/{$s->id}/verificar");
    $this->actingAs($f)->post("/admin/solicitudes/{$s->id}/aprobar");

    $this->actingAs($f)->post("/admin/solicitudes/{$s->id}/finalizar")->assertSessionHasNoErrors();

    expect($s->fresh()->estado)->toBe(Solicitud::ESTADO_FINALIZADA);
    Mail::assertSent(SolicitudEstadoCambiado::class, fn ($m) => $m->hasTo('ana@test.co') && $m->solicitud->estado === Solicitud::ESTADO_FINALIZADA);
});

test('PF-18 reasignar a otro funcionario del mismo tipo', function () {
    $tipo = pfTipo();
    $a = pfUsuario(false, [$tipo->id]);
    $s = pfRadicar($this, $tipo);
    $b = pfUsuario(false, [$tipo->id]);
    expect($s->asignado_a)->toBe($a->id);

    $this->actingAs($a)->post("/admin/solicitudes/{$s->id}/reasignar", ['asignado_a' => $b->id])->assertSessionHasNoErrors();

    expect($s->fresh()->asignado_a)->toBe($b->id)
        ->and($s->eventos()->where('accion', 'solicitud_reasignada')->exists())->toBeTrue();
    Mail::assertSent(NuevaSolicitudAdmin::class, fn ($m) => $m->reasignada && $m->hasTo($b->email));
});

test('PF-19 filtros del listado por estado, tipo y fechas', function () {
    $ahorro = pfTipo('Ahorro');
    $credito = pfTipo('Crédito');
    $admin = pfUsuario(true);
    $objetivo = pfRadicar($this, $ahorro);
    $otroEstado = pfRadicar($this, $ahorro);
    $otroEstado->update(['estado' => Solicitud::ESTADO_EN_REVISION]);
    $otroTipo = pfRadicar($this, $credito);
    $antigua = pfRadicar($this, $ahorro);
    $antigua->forceFill(['created_at' => now()->subMonths(2)])->save();

    $desde = now()->subDays(7)->toDateString();
    $hasta = now()->toDateString();
    $this->actingAs($admin)->get("/admin/solicitudes?estado=recibida&tramite_tipo_id={$ahorro->id}&desde={$desde}&hasta={$hasta}")
        ->assertOk()->assertSee($objetivo->codigo)
        ->assertDontSee($otroEstado->codigo)->assertDontSee($otroTipo->codigo)->assertDontSee($antigua->codigo);
});

test('PF-20 consultar auditoría por código y por usuario', function () {
    $tipo = pfTipo();
    $admin = pfUsuario(true, [], ['name' => 'Marta Auditora']);
    $s = pfRadicar($this, $tipo);
    $this->actingAs($admin)->post("/admin/solicitudes/{$s->id}/verificar");
    $otra = pfRadicar($this, $tipo);

    $this->actingAs($admin)->get('/admin/auditoria?codigo='.$s->codigo)->assertOk()
        ->assertSee($s->codigo)->assertDontSee($otra->codigo)
        ->assertSee('solicitud creada')->assertSee('identidad verificada')->assertSee('Marta Auditora')->assertSee(now()->format('d/m/Y'));
    $this->actingAs($admin)->get('/admin/auditoria?user_id='.$admin->id)->assertOk()
        ->assertViewHas('eventos', fn ($p) => $p->count() > 0 && $p->every(fn ($e) => $e->user_id === $admin->id));
});

test('PF-21 eliminar solicitud conservando el historial', function () {
    $propietario = pfUsuario(true);
    $s = pfRadicar($this, pfTipo());

    $this->actingAs($propietario)->post("/admin/solicitudes/{$s->id}/delete")->assertRedirect();

    $this->actingAs($propietario)->get('/admin/solicitudes')->assertDontSee($s->codigo);
    $this->actingAs($propietario)->get('/admin/auditoria')->assertSee($s->codigo.' (eliminada)');
});

test('PF-22 gestionar funcionarios', function () {
    $propietario = pfUsuario(true);
    $tipo = pfTipo();

    $this->actingAs($propietario)->post('/admin/usuarios', [
        'name' => 'Luis Pérez', 'username' => 'lperez', 'email' => 'luis@test.co', 'password' => 'Secreto12345',
        'tramite_tipos' => [$tipo->id],
    ])->assertSessionHasNoErrors();
    expect(User::where('username', 'lperez')->firstOrFail()->tramiteTipos->pluck('id')->all())->toBe([$tipo->id]);

    $this->actingAs($propietario)->post("/admin/usuarios/{$propietario->id}/toggle")->assertSessionHasErrors('usuario');
    expect($propietario->fresh()->is_active)->toBeTrue();
});

test('PF-23 radicar y responder una PQRS', function () {
    $propietario = pfUsuario(true);

    $this->post('/pqrs', [
        'tipo' => 'queja', 'nombre' => 'Ana', 'documento' => '1053000111', 'email' => 'ana@test.co',
        'asunto' => 'Demora en respuesta', 'descripcion' => 'Llevo días esperando.', 'acepta_datos' => '1',
    ])->assertSessionHasNoErrors();

    $p = Pqrs::firstOrFail();
    expect($p->codigo)->toMatch('/^PQ-\d{2}-[A-Z0-9]{6}$/')->and($p->estado)->toBe(Pqrs::ESTADO_RECIBIDA);
    $this->actingAs($propietario)->post("/admin/pqrs/{$p->id}/tramitar");
    expect($p->fresh()->estado)->toBe(Pqrs::ESTADO_EN_TRAMITE);
    $this->actingAs($propietario)->post("/admin/pqrs/{$p->id}/responder", ['respuesta' => 'Ya fue atendida.']);
    expect($p->fresh()->estado)->toBe(Pqrs::ESTADO_RESPONDIDA);

    // Radicada, en trámite y respondida: tres avisos al asociado.
    Mail::assertSent(PqrsNotificacion::class, fn ($m) => $m->hasTo('ana@test.co') && ! $m->paraAdmin);
    expect(Mail::sent(PqrsNotificacion::class, fn ($m) => $m->hasTo('ana@test.co'))->count())->toBe(3);
});

test('PF-24 PQRS restringidas al administrador principal', function () {
    $f = pfUsuario(false, [pfTipo()->id]);

    $this->actingAs($f)->get('/admin/pqrs')->assertForbidden();
    $this->actingAs($f)->get('/admin/dashboard')->assertOk()->assertDontSee(route('admin.pqrs.index'));
});

test('PF-27 reparto automático al retirar a un funcionario', function () {
    $propietario = pfUsuario(true);
    $tipo = pfTipo();
    $saliente = pfUsuario(false, [$tipo->id]);
    $abierta1 = pfRadicar($this, $tipo);
    $abierta2 = pfRadicar($this, $tipo);
    $cerrada = pfRadicar($this, $tipo);
    $cerrada->update(['estado' => Solicitud::ESTADO_FINALIZADA]);
    $relevo = pfUsuario(false, [$tipo->id]);
    expect([$abierta1->asignado_a, $abierta2->asignado_a, $cerrada->asignado_a])->each->toBe($saliente->id);

    $this->actingAs($propietario)->post("/admin/usuarios/{$saliente->id}/toggle")
        ->assertSessionHas('success', 'Funcionario desactivado. Sus 2 solicitudes abiertas se asignaron a otros responsables.');

    expect($abierta1->fresh()->asignado_a)->toBe($relevo->id)
        ->and($abierta2->fresh()->asignado_a)->toBe($relevo->id)
        ->and($cerrada->fresh()->asignado_a)->toBe($saliente->id)
        ->and($abierta1->eventos()->where('accion', 'solicitud_reasignada')->exists())->toBeTrue()
        ->and($cerrada->eventos()->where('accion', 'solicitud_reasignada')->exists())->toBeFalse();
});

test('PF-28 jerarquía del administrador principal original', function () {
    $original = pfUsuario(true);
    $secundario = pfUsuario(true);
    $otroAdmin = pfUsuario(true);
    $funcionario = pfUsuario(false);
    $s = pfRadicar($this, pfTipo());

    $this->actingAs($secundario)->post('/admin/auditoria/vaciar')->assertForbidden();
    $this->actingAs($secundario)->post("/admin/solicitudes/{$s->id}/delete")->assertForbidden();
    $this->actingAs($secundario)->get("/admin/usuarios/{$otroAdmin->id}/editar")->assertForbidden();
    $this->actingAs($secundario)->put("/admin/usuarios/{$otroAdmin->id}", ['name' => 'X', 'username' => $otroAdmin->username, 'email' => $otroAdmin->email])->assertForbidden();
    // Otorgar el rol de administrador: el sistema no lo aplica (la casilla no se muestra y el valor enviado se ignora).
    $this->actingAs($secundario)->put("/admin/usuarios/{$funcionario->id}", [
        'name' => $funcionario->name, 'username' => $funcionario->username, 'email' => $funcionario->email, 'is_super_admin' => '1',
    ]);
    expect($funcionario->fresh()->is_super_admin)->toBeFalse();

    $this->actingAs($original)->put("/admin/usuarios/{$funcionario->id}", [
        'name' => $funcionario->name, 'username' => $funcionario->username, 'email' => $funcionario->email, 'is_super_admin' => '1',
    ])->assertSessionHasNoErrors();
    $this->actingAs($original)->get("/admin/usuarios/{$otroAdmin->id}/editar")->assertOk();
    $this->actingAs($original)->post('/admin/auditoria/vaciar')->assertSessionHas('success');
    $this->actingAs($original)->post("/admin/solicitudes/{$s->id}/delete")->assertRedirect();
    expect($funcionario->fresh()->is_super_admin)->toBeTrue()->and($s->fresh()->trashed())->toBeTrue();
});

test('PF-29 auditoría restringida y actor conservado', function () {
    $original = pfUsuario(true);
    $tipo = pfTipo();
    $f = pfUsuario(false, [$tipo->id], ['name' => 'Carlos Funcionario']);
    $s = pfRadicar($this, $tipo);
    $this->actingAs($f)->post("/admin/solicitudes/{$s->id}/verificar");

    $this->actingAs($f)->get('/admin/auditoria')->assertForbidden();

    $this->actingAs($original)->post("/admin/usuarios/{$f->id}/delete")->assertSessionHas('success');
    expect(User::find($f->id))->toBeNull();
    $this->actingAs($original)->get('/admin/auditoria?codigo='.$s->codigo)->assertOk()->assertSee('Carlos Funcionario');
});

test('PF-30 certificación sin certificado publicado', function () {
    $tipo = pfTipo('Certificado tributario', certificacion: true);
    $f = pfUsuario(false, [$tipo->id]);
    $s = pfRadicar($this, $tipo);
    $this->actingAs($f)->post("/admin/solicitudes/{$s->id}/verificar");
    $this->actingAs($f)->post("/admin/solicitudes/{$s->id}/aprobar");

    $this->actingAs($f)->post("/admin/solicitudes/{$s->id}/finalizar")->assertSessionHasErrors('documento');
    expect($s->fresh()->estado)->toBe(Solicitud::ESTADO_APROBADA);

    $this->actingAs($f)->post("/admin/solicitudes/{$s->id}/documentos", ['documento' => UploadedFile::fake()->create('certificado.pdf', 20)]);
    $this->actingAs($f)->post("/admin/solicitudes/{$s->id}/finalizar")->assertSessionHasNoErrors();
    expect($s->fresh()->estado)->toBe(Solicitud::ESTADO_FINALIZADA);
});
