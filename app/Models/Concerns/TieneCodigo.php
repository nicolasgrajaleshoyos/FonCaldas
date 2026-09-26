<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

/** Asigna un código de radicación único ("PREFIJO-AA-XXXXXX") al crear el modelo. */
trait TieneCodigo
{
    abstract protected static function prefijoCodigo(): string;

    protected static function bootTieneCodigo(): void
    {
        static::creating(function ($modelo) {
            $modelo->codigo ??= static::generarCodigo();
        });
    }

    public static function generarCodigo(): string
    {
        do {
            $codigo = static::prefijoCodigo().'-'.now()->format('y').'-'.strtoupper(Str::random(6));
        } while (static::where('codigo', $codigo)->exists());

        return $codigo;
    }
}
