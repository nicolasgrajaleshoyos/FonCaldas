<?php

namespace App\Services\Transparencia;

/** Catálogo de categorías de documentos de transparencia (slug => etiqueta). */
class CategoriasTransparencia
{
    private const CATEGORIAS = [
        'gobierno-corporativo' => 'Gobierno Corporativo',
        'normativa' => 'Normativa y Reglamentos',
        'seguridad' => 'Seguridad de la Información',
        'informes' => 'Informes Institucionales',
        'comunicados' => 'Comunicados',
    ];

    public function todas(): array
    {
        return self::CATEGORIAS;
    }

    public function etiqueta(string $slug): ?string
    {
        return self::CATEGORIAS[$slug] ?? null;
    }

    public function slugs(): array
    {
        return array_keys(self::CATEGORIAS);
    }

    public function slugDe(string $etiqueta): ?string
    {
        return array_search($etiqueta, self::CATEGORIAS, true) ?: null;
    }
}
