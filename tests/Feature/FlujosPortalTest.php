<?php

use App\Mail\NuevaSolicitudAdmin;
use App\Mail\SolicitudEstadoCambiado;
use App\Models\Document;
use App\Models\Event;
use App\Models\Pqrs;
use App\Models\Solicitud;
use App\Models\TramiteTipo;
use App\Models\User;
use App\Services\Solicitudes\RadicadorSolicitudes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

uses(RefreshDatabase::class);

function admin(bool $super = true, array $tipos = []): User
{
    $user = User::factory()->create(['is_super_admin' => $super, 'is_active' => true]);
    $user->tramiteTipos()->sync($tipos);

    return $user;
}

function solicitud(TramiteTipo $tipo): Solicitud
{
    return Solicitud::create([
        'tramite_tipo_id' => $tipo->id, 'asociado_nombre' => 'Ana', 'asociado_documento' => '123',
        'asociado_email' => 'ana@test.co', 'descripcion' => 'x', 'estado' => Solicitud::ESTADO_RECIBIDA,
    ]);
}

function tipo(string $nombre = 'Certificado'): TramiteTipo
{
    return TramiteTipo::create(['nombre' => $nombre, 'slug' => str($nombre)->slug(), 'activo' => true, 'es_certificacion' => false]);
}

test('un asociado radica una solicitud con adjunto y se notifica a los responsables', function () {
    Mail::fake();
    Storage::fake('local');
    $tipo = tipo();
    $responsable = admin();

    $this->post('/tramites', [
        'tramite_tipo_id' => $tipo->id, 'asociado_nombre' => 'Ana', 'asociado_documento' => '123',
        'asociado_email' => 'ana@test.co', 'descripcion' => 'Necesito un certificado',
        'documentos' => [UploadedFile::fake()->create('cedula.pdf', 10)],
    ])->assertRedirect(route('solicitudes.crear'));

    $solicitud = Solicitud::firstOrFail();
    expect($solicitud->codigo)->toStartWith('FC-')
        ->and($solicitud->documentos)->toHaveCount(1)
        ->and($solicitud->eventos->pluck('accion')->all())->toContain('solicitud_creada', 'solicitud_asignada')
        ->and($solicitud->asignado_a)->toBe($responsable->id);
    Storage::disk('local')->assertExists($solicitud->documentos[0]->path);
    Mail::assertSent(NuevaSolicitudAdmin::class);
    Mail::assertSent(SolicitudEstadoCambiado::class, fn ($m) => $m->hasTo('ana@test.co'));
});

test('al radicar, el modelo nace en estado recibida y el correo al asociado se renderiza', function () {
    Mail::fake();
    $radicada = app(RadicadorSolicitudes::class)->radicar([
        'tramite_tipo_id' => tipo()->id, 'asociado_nombre' => 'Ana', 'asociado_documento' => '123',
        'asociado_email' => 'ana@test.co', 'descripcion' => 'x',
    ]);

    expect($radicada->estado)->toBe(Solicitud::ESTADO_RECIBIDA)
        ->and($radicada->eventos->last()->estado_nuevo)->toBe(Solicitud::ESTADO_RECIBIDA);
    Mail::assertSent(SolicitudEstadoCambiado::class, fn ($m) => str_contains($m->render(), $radicada->codigo));
});

test('el flujo admin cambia estados, audita con el actor y notifica al asociado', function () {
    Mail::fake();
    $actor = admin();
    $solicitud = solicitud(tipo());

    $this->actingAs($actor)->post("/admin/solicitudes/{$solicitud->id}/verificar")->assertRedirect();
    expect($solicitud->fresh()->estado)->toBe(Solicitud::ESTADO_EN_REVISION);

    $this->actingAs($actor)->post("/admin/solicitudes/{$solicitud->id}/aprobar");
    expect($solicitud->fresh()->estado)->toBe(Solicitud::ESTADO_APROBADA);

    $this->actingAs($actor)->post("/admin/solicitudes/{$solicitud->id}/finalizar");
    expect($solicitud->fresh()->finalizado_at)->not->toBeNull()
        ->and($solicitud->eventos()->pluck('user_id')->unique()->all())->toBe([$actor->id]);
    Mail::assertSent(SolicitudEstadoCambiado::class, 3);
});

test('un funcionario sin el tipo de trámite recibe 403', function () {
    $solicitud = solicitud(tipo());
    $ajeno = admin(false, [tipo('Otro')->id]);

    $this->actingAs($ajeno)->get("/admin/solicitudes/{$solicitud->id}")->assertForbidden();
    $this->actingAs($ajeno)->post("/admin/solicitudes/{$solicitud->id}/aprobar")->assertForbidden();
});

test('el listado admin filtra por estado y respeta los tipos visibles', function () {
    $propio = tipo('Propio');
    $ajeno = tipo('Ajeno');
    solicitud($propio);
    solicitud($ajeno);
    $user = admin(false, [$propio->id]);

    $this->actingAs($user)->get('/admin/solicitudes?estado=recibida')
        ->assertOk()->assertViewHas('solicitudes', fn ($p) => $p->count() === 1);
    $this->actingAs($user)->get('/admin/dashboard')->assertOk()->assertViewHas('pendientes', 1);
});

test('publicar documento versiona y reasignar deja rastro', function () {
    Mail::fake();
    Storage::fake('local');
    $actor = admin();
    $solicitud = solicitud(tipo());

    foreach ([1, 2] as $_) {
        $this->actingAs($actor)->post("/admin/solicitudes/{$solicitud->id}/documentos", ['documento' => UploadedFile::fake()->create('r.pdf', 5)]);
    }
    $this->actingAs($actor)->post("/admin/solicitudes/{$solicitud->id}/reasignar", ['asignado_a' => $actor->id]);

    expect($solicitud->documentos()->pluck('version')->all())->toBe([1, 2])
        ->and($solicitud->fresh()->asignado_a)->toBe($actor->id);
    Mail::assertSent(NuevaSolicitudAdmin::class, fn ($m) => $m->reasignada && $m->hasTo($actor->email));
});

test('no se puede aprobar ni rechazar sin verificar la identidad; rechazar cierra la solicitud', function () {
    Mail::fake();
    $actor = admin();
    $solicitud = solicitud(tipo());

    $this->actingAs($actor)->post("/admin/solicitudes/{$solicitud->id}/aprobar")->assertSessionHasErrors('estado');
    $this->actingAs($actor)->post("/admin/solicitudes/{$solicitud->id}/rechazar", ['justificacion' => 'x'])->assertSessionHasErrors('estado');
    $this->actingAs($actor)->post("/admin/solicitudes/{$solicitud->id}/finalizar")->assertSessionHasErrors('estado');
    expect($solicitud->fresh()->estado)->toBe(Solicitud::ESTADO_RECIBIDA);

    $this->actingAs($actor)->post("/admin/solicitudes/{$solicitud->id}/verificar");
    $this->actingAs($actor)->post("/admin/solicitudes/{$solicitud->id}/rechazar", ['justificacion' => 'incompleto']);
    expect($solicitud->fresh())->estado->toBe(Solicitud::ESTADO_RECHAZADA)->justificacion->toBe('incompleto')
        ->and($solicitud->fresh()->finalizado_at)->not->toBeNull();
});

test('la asignación automática reparte por carga y prefiere funcionarios sobre super admins', function () {
    Mail::fake();
    $tipo = tipo();
    admin();
    $a = admin(false, [$tipo->id]);
    $b = admin(false, [$tipo->id]);

    $radicar = fn () => $this->post('/tramites', [
        'tramite_tipo_id' => $tipo->id, 'asociado_nombre' => 'Ana', 'asociado_documento' => '123',
        'asociado_email' => 'ana@test.co', 'descripcion' => 'x',
    ]);
    $radicar();
    $radicar();

    expect(Solicitud::pluck('asignado_a')->sort()->values()->all())->toBe([$a->id, $b->id]);
});

test('los documentos son privados: solo se descargan con permiso o con enlace firmado', function () {
    Storage::fake('local');
    $solicitud = solicitud(tipo());
    $documento = $solicitud->adjuntar(UploadedFile::fake()->create('cedula.pdf', 5), 'asociado');
    $ruta = "/admin/solicitudes/{$solicitud->id}/documentos/{$documento->id}";

    $this->get($ruta)->assertRedirect();
    $this->actingAs(admin(false, [tipo('Otro')->id]))->get($ruta)->assertForbidden();
    $this->actingAs(admin())->get($ruta)->assertOk();
    $this->actingAs(admin())->get("/admin/solicitudes/{$solicitud->id}")->assertOk()->assertSee($ruta);

    $this->get("/tramites/documentos/{$documento->id}")->assertForbidden();
    $this->get(URL::temporarySignedRoute('solicitudes.documentos.descargar', now()->addMinutes(5), $documento))->assertOk();
    $this->get('/storage/'.$documento->path)->assertForbidden();
});

test('el asociado adjunta documentos con enlace firmado solo mientras la solicitud está abierta', function () {
    Storage::fake('local');
    $solicitud = solicitud(tipo());
    $url = fn () => URL::temporarySignedRoute('solicitudes.documentos.store', now()->addHour(), $solicitud);
    $archivo = fn () => ['documentos' => [UploadedFile::fake()->create('extra.pdf', 5)]];

    $this->post("/tramites/{$solicitud->id}/documentos", $archivo())->assertForbidden();

    $this->post($url(), $archivo())->assertOk()->assertSee('extra.pdf');
    expect($solicitud->documentos()->where('origen', 'asociado')->count())->toBe(1)
        ->and($solicitud->eventos()->where('accion', 'documento_adjuntado')->exists())->toBeTrue();

    $solicitud->update(['estado' => Solicitud::ESTADO_FINALIZADA]);
    $this->post($url(), $archivo())->assertForbidden();
});

test('el dashboard resume volumen, estados y tiempo promedio de atención', function () {
    $tipo = tipo();
    solicitud($tipo);
    $cerrada = solicitud($tipo);
    $cerrada->forceFill(['estado' => Solicitud::ESTADO_FINALIZADA, 'created_at' => now()->subHours(10), 'finalizado_at' => now()])->save();

    $this->actingAs(admin())->get('/admin/dashboard')->assertOk()
        ->assertViewHas('metricas', fn ($m) => $m['total'] === 2 && $m['ultimos30'] === 2
            && $m['porEstado']['finalizada'] === 1 && round($m['horasPromedio']) === 10.0);
});

test('las consultas públicas tienen límite de intentos', function () {
    foreach (range(1, 10) as $_) {
        $this->post('/tramites/consultar', ['codigo' => 'FC-26-AAAAAA', 'asociado_documento' => '1'])->assertSessionHasErrors('codigo');
    }

    $this->post('/tramites/consultar', ['codigo' => 'FC-26-AAAAAA', 'asociado_documento' => '1'])->assertStatus(429);
});

test('el ciclo de una PQRS: radicar, consultar, tramitar y responder', function () {
    Mail::fake();
    $actor = admin();

    $this->post('/pqrs', [
        'tipo' => 'queja', 'nombre' => 'Ana', 'documento' => '123', 'email' => 'ana@test.co',
        'asunto' => 'Demora', 'descripcion' => 'Detalle', 'acepta_datos' => '1',
    ])->assertRedirect(route('pqrs.crear'));

    $pqrs = Pqrs::firstOrFail();
    expect($pqrs->codigo)->toStartWith('PQ-')->and($pqrs->estado)->toBe(Pqrs::ESTADO_RECIBIDA);

    $this->post('/pqrs/consultar', ['codigo' => strtolower($pqrs->codigo), 'documento' => '123'])->assertOk();

    $this->actingAs($actor)->post("/admin/pqrs/{$pqrs->id}/tramitar");
    expect($pqrs->fresh()->estado)->toBe(Pqrs::ESTADO_EN_TRAMITE);

    $this->actingAs($actor)->post("/admin/pqrs/{$pqrs->id}/responder", ['respuesta' => 'Listo']);
    expect($pqrs->fresh())->estado->toBe(Pqrs::ESTADO_RESPONDIDA)->respondido_por->toBe($actor->id);
});

test('eventos: crear con video guarda el archivo y eliminar lo borra', function () {
    Storage::fake('public');
    $actor = admin();

    $this->actingAs($actor)->post('/admin/eventos', [
        'title' => 'Asamblea', 'description' => 'd', 'location' => 'Manizales',
        'event_date' => '2026-10-01', 'event_time' => '10:00',
        'media' => UploadedFile::fake()->create('clip.mp4', 100, 'video/mp4'),
    ])->assertSessionHasNoErrors();

    $evento = Event::firstOrFail();
    $ruta = str($evento->media_path)->after('storage/')->toString();
    expect($evento->media_type)->toBe('video');
    Storage::disk('public')->assertExists($ruta);

    $this->get('/eventos')->assertOk();
    $this->actingAs($actor)->post("/admin/eventos/{$evento->id}/delete");
    expect(Event::count())->toBe(0);
    Storage::disk('public')->assertMissing($ruta);
});

test('transparencia: subir y eliminar un PDF, y navegar por categoría', function () {
    Storage::fake('public');
    $actor = admin();

    $this->actingAs($actor)->post('/admin/documentos', [
        'title' => 'Reglamento', 'description' => 'd', 'category' => 'normativa',
        'document' => UploadedFile::fake()->create('r.pdf', 10, 'application/pdf'),
    ])->assertSessionHasNoErrors();

    $doc = Document::firstOrFail();
    expect($doc->category)->toBe('Normativa y Reglamentos');

    $this->get('/transparencia')->assertOk();
    $this->get('/transparencia/categoria/normativa')->assertOk()->assertSee('Reglamento');
    $this->get('/transparencia/categoria/inexistente')->assertNotFound();

    $this->actingAs($actor)->post("/admin/documentos/{$doc->id}/delete");
    expect(Document::count())->toBe(0);
});

test('usuarios: crear, editar sin cambiar contraseña y no auto-desactivarse', function () {
    $actor = admin();
    $tipo = tipo();

    $this->actingAs($actor)->post('/admin/usuarios', [
        'name' => 'Luis', 'username' => 'luis', 'email' => 'luis@test.co', 'password' => 'secreto123',
        'tramite_tipos' => [$tipo->id],
    ])->assertSessionHasNoErrors();

    $luis = User::where('username', 'luis')->firstOrFail();
    $hash = $luis->password;
    expect($luis->tramiteTipos)->toHaveCount(1)->and($luis->is_active)->toBeTrue();

    $this->actingAs($actor)->put("/admin/usuarios/{$luis->id}", [
        'name' => 'Luis P', 'username' => 'luis', 'email' => 'luis@test.co',
    ])->assertSessionHasNoErrors();
    expect($luis->fresh())->name->toBe('Luis P')->password->toBe($hash);

    $this->actingAs($actor)->post("/admin/usuarios/{$actor->id}/toggle")->assertSessionHasErrors('usuario');
});
