<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

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
