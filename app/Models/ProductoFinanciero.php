<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
}
