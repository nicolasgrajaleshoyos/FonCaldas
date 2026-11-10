<?php

use App\Contracts\AlmacenArchivos;
use App\Services\Transparencia\GestorEventos;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

function datosEvento(): array
{
    return ['title' => 'Asamblea', 'description' => 'd', 'location' => 'Manizales', 'event_date' => '2026-10-01', 'event_time' => '10:00'];
}

test('crea() clasifica como video una extensión de video en mayúsculas', function () {
    $this->mock(AlmacenArchivos::class, fn ($m) => $m->shouldReceive('guardar')->once()->andReturn('storage/uploads/eventos/x.mov'));

    $evento = app(GestorEventos::class)->crear(datosEvento(), UploadedFile::fake()->create('clip.MOV', 10));

    expect($evento->media_type)->toBe('video');
});

test('crea() clasifica como imagen cualquier extensión que no sea de video', function () {
    $this->mock(AlmacenArchivos::class, fn ($m) => $m->shouldReceive('guardar')->once()->andReturn('storage/uploads/eventos/x.png'));

    $evento = app(GestorEventos::class)->crear(datosEvento(), UploadedFile::fake()->create('foto.png', 10));

    expect($evento->media_type)->toBe('image');
});

test('crea() sin archivo no guarda media_type ni media_path', function () {
    $this->mock(AlmacenArchivos::class, fn ($m) => $m->shouldNotReceive('guardar'));

    $evento = app(GestorEventos::class)->crear(datosEvento(), null);

    expect($evento->media_type)->toBeNull()->and($evento->media_path)->toBeNull();
});
