<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TramiteTipo extends Model
{
    protected $fillable = [
        'nombre',
        'slug',
        'descripcion',
        'es_certificacion',
        'activo',
    ];

    protected $casts = [
        'es_certificacion' => 'boolean',
        'activo' => 'boolean',
    ];

    public function solicitudes(): HasMany
    {
        return $this->hasMany(Solicitud::class);
    }

    public function usuarios(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
