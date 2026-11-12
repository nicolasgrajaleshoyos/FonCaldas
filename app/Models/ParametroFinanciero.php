<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParametroFinanciero extends Model
{
    protected $table = 'parametro_financieros';

    protected $fillable = [
        'producto_financiero_id',
        'nombre',
        'codigo',
        'valor',
        'unidad',
        'fecha_inicio_vigencia',
        'fecha_fin_vigencia',
        'activo',
        'descripcion',
    ];

    protected $casts = [
        'valor' => 'decimal:6',
        'fecha_inicio_vigencia' => 'date',
        'fecha_fin_vigencia' => 'date',
        'activo' => 'boolean',
    ];

    public function producto(): BelongsTo
    {
        return $this->belongsTo(ProductoFinanciero::class, 'producto_financiero_id');
    }

    /**
     * ¿Hay otra tasa activa del producto cuya vigencia se cruce con [inicio, fin]? (fin null = vigencia abierta).
     * Única fuente de esta regla: la usan tanto el registro como la edición de tasas.
     */
    public static function haySuperposicion(int $productoId, string $inicio, ?string $fin, ?int $excluirId = null): bool
    {
        return static::where('producto_financiero_id', $productoId)
            ->where('codigo', 'tasa_interes')
            ->where('activo', true)
            ->when($excluirId, fn ($q) => $q->whereKeyNot($excluirId))
            ->where(fn ($q) => $q->whereNull('fecha_fin_vigencia')->orWhere('fecha_fin_vigencia', '>=', $inicio))
            ->when($fin, fn ($q) => $q->where('fecha_inicio_vigencia', '<=', $fin), fn ($q) => $q->whereNotNull('fecha_inicio_vigencia'))
            ->exists();
    }

    public function scopeVigente(Builder $consulta): Builder
    {
        $hoy = now()->toDateString();

        return $consulta
            ->where('activo', true)
            ->whereDate('fecha_inicio_vigencia', '<=', $hoy)
            ->where(function (Builder $consulta) use ($hoy) {
                $consulta
                    ->whereNull('fecha_fin_vigencia')
                    ->orWhereDate('fecha_fin_vigencia', '>=', $hoy);
            });
    }
}
