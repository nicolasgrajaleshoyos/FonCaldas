<?php

use App\Models\Document;
use App\Models\Event;
use App\Models\Pqrs;
use App\Models\Solicitud;
use App\Models\SolicitudEvento;
use App\Models\TramiteTipo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    Mail::fake();
    Storage::fake('local');
    Storage::fake('public');
    $this->super = User::factory()->create(['is_super_admin' => true, 'is_active' => true]);
    $this->normal = User::factory()->create(['is_super_admin' => false, 'is_active' => true]);
    $this->tipo = TramiteTipo::create(['nombre' => 'Ahorro', 'slug' => 'ahorro', 'activo' => true, 'es_certificacion' => false]);
    $this->normal->tramiteTipos()->attach($this->tipo);
});

function radicar($t, array $extra = []): Solicitud
{
    $t->post('/tramites', [
        'tramite_tipo_id' => $t->tipo->id, 'asociado_nombre' => 'Ana', 'asociado_documento' => '123',
        'asociado_email' => 'a@b.co', 'descripcion' => 'd',
    ] + $extra)->assertSessionHasNoErrors();

    return Solicitud::firstOrFail();
}

test('ciclo completo de una solicitud y transiciones inválidas', function () {
    $s = radicar($this);
    expect($s->estado)->toBe('recibida')->and($s->asignado_a)->not->toBeNull();

    $this->actingAs($this->normal)->post("/admin/solicitudes/{$s->id}/aprobar")->assertSessionHasErrors('estado');
    $this->actingAs($this->normal)->post("/admin/solicitudes/{$s->id}/finalizar")->assertSessionHasErrors('estado');
    $this->actingAs($this->normal)->post("/admin/solicitudes/{$s->id}/verificar");
    expect($s->fresh()->estado)->toBe('en_revision');
    $this->actingAs($this->normal)->post("/admin/solicitudes/{$s->id}/aprobar");
    $this->actingAs($this->normal)->post("/admin/solicitudes/{$s->id}/finalizar");
    expect($s->fresh()->estado)->toBe('finalizada')->and($s->fresh()->finalizado_at)->not->toBeNull();
});

test('consulta pública exige código y documento correctos', function () {
    $s = radicar($this);
    $this->post('/tramites/consultar', ['codigo' => $s->codigo, 'asociado_documento' => '123'])->assertRedirect();
    $this->post('/tramites/consultar', ['codigo' => $s->codigo, 'asociado_documento' => '999'])->assertSessionHasErrors('codigo');
});

test('un funcionario sin acceso al tipo no puede gestionar la solicitud', function () {
    $s = radicar($this);
    $otro = User::factory()->create(['is_super_admin' => false, 'is_active' => true]);
    $this->actingAs($otro)->get("/admin/solicitudes/{$s->id}")->assertForbidden();
    $this->actingAs($otro)->post("/admin/solicitudes/{$s->id}/verificar")->assertForbidden();
});

test('reasignar solo a un usuario activo que pueda gestionar ese tipo', function () {
    $s = radicar($this);
    $sinAcceso = User::factory()->create(['is_super_admin' => false, 'is_active' => true]);
    $inactivo = User::factory()->create(['is_super_admin' => false, 'is_active' => false]);
    $inactivo->tramiteTipos()->attach($this->tipo);

    $this->actingAs($this->super)->post("/admin/solicitudes/{$s->id}/reasignar", ['asignado_a' => $sinAcceso->id]);
    expect($s->fresh()->asignado_a)->not->toBe($sinAcceso->id);
    $this->actingAs($this->super)->post("/admin/solicitudes/{$s->id}/reasignar", ['asignado_a' => $inactivo->id]);
    expect($s->fresh()->asignado_a)->not->toBe($inactivo->id);
});

test('tipo de trámite duplicado se valida y no da error 500', function () {
    $this->actingAs($this->super)->post('/admin/tipos-tramite', ['nombre' => 'Ahorro'])->assertSessionHasErrors('nombre');
});

test('una PQRS ya respondida no se sobrescribe', function () {
    $p = Pqrs::create(['tipo' => 'queja', 'nombre' => 'n', 'documento' => '1', 'email' => 'a@b.co', 'asunto' => 'a', 'descripcion' => 'd']);
    $this->actingAs($this->super)->post("/admin/pqrs/{$p->id}/responder", ['respuesta' => 'Primera']);
    $this->actingAs($this->super)->post("/admin/pqrs/{$p->id}/responder", ['respuesta' => 'Segunda']);
    expect($p->fresh()->respuesta)->toBe('Primera');
});

test('los adjuntos solo admiten tipos seguros y siempre se descargan', function () {
    $this->post('/tramites', [
        'tramite_tipo_id' => $this->tipo->id, 'asociado_nombre' => 'Ana', 'asociado_documento' => '123',
        'asociado_email' => 'a@b.co', 'descripcion' => 'd',
        'documentos' => [UploadedFile::fake()->createWithContent('x.html', '<script>alert(1)</script>')],
    ])->assertSessionHasErrors('documentos.0');
    expect(Solicitud::count())->toBe(0);

    $s = radicar($this, ['documentos' => [UploadedFile::fake()->create('ok.pdf', 10, 'application/pdf')]]);
    $doc = $s->documentos()->first();
    $res = $this->actingAs($this->super)->get("/admin/solicitudes/{$s->id}/documentos/{$doc->id}");
    expect((string) $res->headers->get('content-disposition'))->toContain('attachment');
});

test('no se puede quitar el rol al último administrador principal', function () {
    $this->actingAs($this->super)->put("/admin/usuarios/{$this->super->id}", [
        'name' => $this->super->name, 'username' => $this->super->username ?? 'x', 'email' => $this->super->email,
    ]);
    expect(User::where('is_super_admin', true)->where('is_active', true)->count())->toBeGreaterThan(0);
});

// ---- Lo agregado en esta sesión ----

test('eliminar solicitud es borrado lógico: conserva historial y archivos; funcionario recibe 403', function () {
    $s = radicar($this, ['documentos' => [UploadedFile::fake()->create('a.pdf', 10)]]);
    $path = $s->documentos()->first()->path;
    Storage::disk('local')->assertExists($path);

    $this->actingAs($this->normal)->post("/admin/solicitudes/{$s->id}/delete")->assertForbidden();
    $this->actingAs($this->super)->post("/admin/solicitudes/{$s->id}/delete")->assertRedirect('/admin/solicitudes');

    expect(Solicitud::count())->toBe(0)
        ->and(Solicitud::withTrashed()->count())->toBe(1)
        ->and(SolicitudEvento::where('solicitud_id', $s->id)->where('accion', 'solicitud_eliminada')->value('user_id'))->toBe($this->super->id);
    Storage::disk('local')->assertExists($path);
    $this->actingAs($this->super)->get('/admin/auditoria')->assertOk()->assertSee('(eliminada)');
});

test('eliminar tipo de trámite se bloquea si tiene solicitudes', function () {
    radicar($this);
    $this->actingAs($this->super)->post("/admin/tipos-tramite/{$this->tipo->id}/delete")->assertSessionHas('error');
    expect(TramiteTipo::count())->toBe(1);

    $libre = TramiteTipo::create(['nombre' => 'Libre', 'slug' => 'libre', 'activo' => true, 'es_certificacion' => false]);
    $this->actingAs($this->super)->post("/admin/tipos-tramite/{$libre->id}/delete")->assertSessionHas('success');
    expect(TramiteTipo::find($libre->id))->toBeNull();
});

test('eliminar PQRS y auditoría (selección y vaciar) solo para el administrador principal', function () {
    $p = Pqrs::create(['tipo' => 'queja', 'nombre' => 'n', 'documento' => '1', 'email' => 'a@b.co', 'asunto' => 'a', 'descripcion' => 'd']);
    $this->actingAs($this->normal)->post("/admin/pqrs/{$p->id}/delete")->assertForbidden();
    $this->actingAs($this->super)->post("/admin/pqrs/{$p->id}/delete");
    expect(Pqrs::count())->toBe(0)->and(Pqrs::withTrashed()->count())->toBe(1);

    radicar($this);
    $ids = SolicitudEvento::pluck('id')->all();
    expect(count($ids))->toBeGreaterThan(1);
    $this->actingAs($this->normal)->post('/admin/auditoria/vaciar')->assertForbidden();
    $this->actingAs($this->super)->post('/admin/auditoria/eliminar', ['ids' => [$ids[0]]]);
    expect(SolicitudEvento::whereNull('archivado_at')->count())->toBe(count($ids) - 1);
    $this->actingAs($this->super)->post('/admin/auditoria/eliminar', [])->assertSessionHasErrors('ids');
    $this->actingAs($this->super)->post('/admin/auditoria/vaciar');
    expect(SolicitudEvento::whereNull('archivado_at')->count())->toBe(0)
        ->and(SolicitudEvento::count())->toBe(count($ids)); // la evidencia sigue en la base de datos
});

test('editar evento conserva el archivo sin subida nueva y lo reemplaza con una nueva', function () {
    $e = Event::create(['title' => 'T', 'description' => 'd', 'location' => 'L', 'event_date' => '2026-12-01', 'event_time' => '10:00', 'media_type' => 'image', 'media_path' => 'storage/uploads/eventos/old.png']);
    Storage::disk('public')->put('uploads/eventos/old.png', 'x');
    $datos = ['title' => 'Nuevo', 'description' => 'd2', 'location' => 'L2', 'event_date' => '2026-12-02', 'event_time' => '11:30'];

    $this->actingAs($this->normal)->put("/admin/eventos/{$e->id}", $datos)->assertForbidden();

    $this->actingAs($this->super)->put("/admin/eventos/{$e->id}", $datos)->assertRedirect('/admin/eventos');
    expect($e->fresh()->title)->toBe('Nuevo')->and($e->fresh()->media_path)->toBe('storage/uploads/eventos/old.png');

    $this->actingAs($this->super)->put("/admin/eventos/{$e->id}", $datos + ['media' => UploadedFile::fake()->image('n.png')]);
    expect($e->fresh()->media_path)->not->toBe('storage/uploads/eventos/old.png');
    Storage::disk('public')->assertMissing('uploads/eventos/old.png');
    $this->actingAs($this->super)->get("/admin/eventos/{$e->id}/editar")->assertOk();
});

test('editar documento: sin PDF nuevo conserva el actual; con PDF lo reemplaza', function () {
    $d = Document::create(['title' => 'D', 'description' => 'd', 'category' => 'Comunicados', 'file_path' => 'storage/uploads/transparencia/old.pdf', 'published_at' => '2026-01-01']);
    Storage::disk('public')->put('uploads/transparencia/old.pdf', 'x');
    $datos = ['title' => 'D2', 'description' => 'd2', 'category' => 'normativa'];

    $this->actingAs($this->normal)->put("/admin/documentos/{$d->id}", $datos)->assertForbidden();

    $this->actingAs($this->super)->put("/admin/documentos/{$d->id}", $datos)->assertSessionHasNoErrors();
    expect($d->fresh()->category)->toBe('Normativa y Reglamentos')->and($d->fresh()->file_path)->toBe('storage/uploads/transparencia/old.pdf');

    $this->actingAs($this->super)->put("/admin/documentos/{$d->id}", $datos + ['document' => UploadedFile::fake()->create('n.pdf', 5, 'application/pdf')]);
    expect($d->fresh()->file_path)->not->toBe('storage/uploads/transparencia/old.pdf');
    Storage::disk('public')->assertMissing('uploads/transparencia/old.pdf');
    $this->actingAs($this->super)->get("/admin/documentos/{$d->id}/editar")->assertOk();
});

test('editar tipo de trámite conserva el slug y solo lo hace el administrador principal', function () {
    $this->actingAs($this->normal)->put("/admin/tipos-tramite/{$this->tipo->id}", ['nombre' => 'X'])->assertForbidden();
    $this->actingAs($this->super)->put("/admin/tipos-tramite/{$this->tipo->id}", ['nombre' => 'Ahorro programado', 'es_certificacion' => '1']);
    expect($this->tipo->fresh()->nombre)->toBe('Ahorro programado')->and($this->tipo->fresh()->slug)->toBe('ahorro')->and($this->tipo->fresh()->es_certificacion)->toBeTrue();
    $this->actingAs($this->super)->get("/admin/tipos-tramite/{$this->tipo->id}/editar")->assertOk();
});

test('todas las pantallas del panel responden 200', function () {
    $s = radicar($this);
    foreach (['dashboard', 'solicitudes', "solicitudes/{$s->id}", 'pqrs', 'documentos', 'eventos', 'auditoria', 'usuarios', 'usuarios/nuevo', 'tipos-tramite'] as $ruta) {
        $this->actingAs($this->super)->get("/admin/{$ruta}")->assertOk();
    }
});

test('una solicitud cerrada no se puede reasignar ni genera aviso', function () {
    $s = radicar($this);
    $original = $s->asignado_a;
    $this->actingAs($this->super)->post("/admin/solicitudes/{$s->id}/rechazar", ['justificacion' => 'Datos no coinciden']);
    Mail::fake(); // se descartan los correos previos para medir solo la reasignación

    $this->actingAs($this->super)->post("/admin/solicitudes/{$s->id}/reasignar", ['asignado_a' => $this->super->id])
        ->assertSessionHasErrors('asignado_a');

    expect($s->fresh()->asignado_a)->toBe($original);
    Mail::assertNothingSent();
});

test('al radicar no se aceptan más de 5 documentos', function () {
    $this->post('/tramites', [
        'tramite_tipo_id' => $this->tipo->id, 'asociado_nombre' => 'Ana', 'asociado_documento' => '123',
        'asociado_email' => 'a@b.co', 'descripcion' => 'd',
        'documentos' => array_map(fn ($i) => UploadedFile::fake()->create("d{$i}.pdf", 5), range(1, 6)),
    ])->assertSessionHasErrors('documentos');

    expect(Solicitud::count())->toBe(0);
});
