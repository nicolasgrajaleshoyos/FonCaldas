<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProductoFinanciero extends Model
{
    protected $table = 'producto_financieros';

    protected $fillable = [
        'nombre',
        'codigo',
        'tipo',
        'descripcion',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function parametros(): HasMany
    {
        return $this->hasMany(ParametroFinanciero::class);
    }

    public function tasaVigente(): HasOne
    {
        return $this->hasOne(ParametroFinanciero::class)
            ->where('codigo', 'tasa_interes')
            ->vigente()
            ->ofMany('fecha_inicio_vigencia', 'max');
    }
}
