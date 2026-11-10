<?php

use App\Services\Transparencia\CategoriasTransparencia;

test('todas() expone el catálogo completo de categorías', function () {
    expect((new CategoriasTransparencia)->todas())->toBe([
        'gobierno-corporativo' => 'Gobierno Corporativo',
        'normativa' => 'Normativa y Reglamentos',
        'seguridad' => 'Seguridad de la Información',
        'informes' => 'Informes Institucionales',
        'comunicados' => 'Comunicados',
    ]);
});

test('etiqueta() y slugDe() son inversas entre sí, y null si no existen', function () {
    $categorias = new CategoriasTransparencia;

    expect($categorias->etiqueta('normativa'))->toBe('Normativa y Reglamentos')
        ->and($categorias->etiqueta('inexistente'))->toBeNull()
        ->and($categorias->slugDe('Normativa y Reglamentos'))->toBe('normativa')
        ->and($categorias->slugDe('Inexistente'))->toBeNull();
});

test('slugs() devuelve solo las llaves del catálogo', function () {
    expect((new CategoriasTransparencia)->slugs())->toBe([
        'gobierno-corporativo', 'normativa', 'seguridad', 'informes', 'comunicados',
    ]);
});
