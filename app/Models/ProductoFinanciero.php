<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;

/**
 * Atributos calculados que los simuladores agregan al producto antes de enviarlo a la vista.
 *
 * @property bool|null $aportes_simulador_permitidos
 * @property string|null $frecuencia_aporte_simulador
 * @property array<string, mixed>|Collection<string, mixed>|null $parametros_simulador
 */
class ProductoFinanciero extends Model
{
    protected $table = 'producto_financieros';

    protected $fillable = [
        'nombre',
        'codigo',
        'tipo',
        'descripcion',
        'activo',
        'simulable',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'simulable' => 'boolean',
    ];

    public function parametros(): HasMany
    {
        return $this->hasMany(ParametroFinanciero::class);
    }

    /** @return HasMany<ParametroFinanciero, $this> */
    public function parametrosVigentes(): HasMany
    {
        return $this->hasMany(ParametroFinanciero::class)
            ->vigente()
            ->orderBy('codigo')
            ->orderByDesc('fecha_inicio_vigencia');
    }

    /** @return Collection<string, ParametroFinanciero> */
    public function parametrosVigentesPorCodigo(): Collection
    {
        return $this->parametrosVigentes
            ->groupBy('codigo')
            ->map(fn (Collection $registros) => $registros->sortByDesc(
                fn (ParametroFinanciero $parametro) => [
                    $parametro->fecha_inicio_vigencia->toDateString(),
                    $parametro->getKey(),
                ]
            )->first());
    }

    public function tasaVigente(): HasOne
    {
        return $this->hasOne(ParametroFinanciero::class)
            ->where('codigo', 'tasa_interes')
            ->vigente()
            ->ofMany('fecha_inicio_vigencia', 'max');
    }
}
