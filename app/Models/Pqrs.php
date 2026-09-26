<?php

namespace App\Models;

use App\Models\Concerns\TieneCodigo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pqrs extends Model
{
    use TieneCodigo;

    protected $table = 'pqrs';

    public const TIPOS = [
        'peticion' => 'Petición',
        'queja' => 'Queja',
        'reclamo' => 'Reclamo',
        'sugerencia' => 'Sugerencia',
    ];

    public const ESTADO_RECIBIDA = 'recibida';

    public const ESTADO_EN_TRAMITE = 'en_tramite';

    public const ESTADO_RESPONDIDA = 'respondida';

    public const ESTADOS = [
        self::ESTADO_RECIBIDA => 'Recibida',
        self::ESTADO_EN_TRAMITE => 'En trámite',
        self::ESTADO_RESPONDIDA => 'Respondida',
    ];

    protected $fillable = [
        'codigo', 'tipo', 'estado', 'nombre', 'documento', 'email', 'telefono',
        'asunto', 'descripcion', 'respuesta', 'respondido_por', 'respondido_at',
    ];

    protected $attributes = ['estado' => self::ESTADO_RECIBIDA];

    protected $casts = ['respondido_at' => 'datetime'];

    protected static function prefijoCodigo(): string
    {
        return 'PQ';
    }

    public function respondidoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'respondido_por');
    }

    public function tipoLabel(): string
    {
        return self::TIPOS[$this->tipo] ?? $this->tipo;
    }

    public function estadoLabel(): string
    {
        return self::ESTADOS[$this->estado] ?? $this->estado;
    }
}
