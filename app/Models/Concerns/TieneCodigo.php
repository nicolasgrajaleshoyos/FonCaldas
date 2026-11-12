<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Asigna un código de radicación único ("PREFIJO-AA-XXXXXX") al crear el modelo.
 *
 * El código se busca libre antes de guardar, pero entre esa búsqueda y el INSERT otra radicación simultánea podría
 * tomar el mismo. En ese caso la base rechaza el duplicado (índice único) y aquí se genera otro y se reintenta:
 * el asociado nunca ve un error ni dos radicaciones comparten código.
 */
trait TieneCodigo
{
    private const INTENTOS_CODIGO = 5;

    /** true si el código lo generó el sistema (uno puesto a mano no se cambia en silencio). */
    private bool $codigoAutomatico = false;

    abstract protected static function prefijoCodigo(): string;

    protected static function bootTieneCodigo(): void
    {
        static::creating(function ($modelo) {
            if ($modelo->codigo === null) {
                $modelo->codigo = static::generarCodigo();
                $modelo->codigoAutomatico = true;
            }
        });
    }

    public static function generarCodigo(): string
    {
        do {
            $codigo = static::prefijoCodigo().'-'.now()->format('y').'-'.strtoupper(Str::random(6));
        } while (static::query()->withoutGlobalScopes()->where('codigo', $codigo)->exists());

        return $codigo;
    }

    /**
     * Cada intento va en su propia transacción (un punto de guardado si ya hay una abierta): así un INSERT rechazado
     * no invalida la transacción de quien radica, también en PostgreSQL.
     */
    protected function performInsert(Builder $query)
    {
        for ($intento = 1; ; $intento++) {
            try {
                return DB::transaction(fn () => parent::performInsert($query));
            } catch (UniqueConstraintViolationException $e) {
                if (! $this->codigoAutomatico || $intento >= self::INTENTOS_CODIGO || ! str_contains($e->getMessage(), 'codigo')) {
                    throw $e;
                }

                $this->codigo = static::generarCodigo();
            }
        }
    }
}
