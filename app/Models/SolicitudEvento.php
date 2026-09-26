<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SolicitudEvento extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'solicitud_id',
        'user_id',
        'accion',
        'detalle',
        'estado_anterior',
        'estado_nuevo',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function solicitud(): BelongsTo
    {
        return $this->belongsTo(Solicitud::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** Quién ejecutó la acción: el funcionario, el asociado (acciones sin usuario) o el sistema. */
    public function actor(): string
    {
        return $this->usuario?->name
            ?? (in_array($this->accion, ['solicitud_creada', 'documento_adjuntado'], true) ? 'Asociado' : 'Sistema');
    }
}
