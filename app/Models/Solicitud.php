<?php

namespace App\Models;

use App\Models\Concerns\TieneCodigo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\UploadedFile;

class Solicitud extends Model
{
    use TieneCodigo;

    protected $table = 'solicitudes';

    public const ESTADO_RECIBIDA = 'recibida';

    public const ESTADO_EN_REVISION = 'en_revision';

    public const ESTADO_APROBADA = 'aprobada';

    public const ESTADO_RECHAZADA = 'rechazada';

    public const ESTADO_FINALIZADA = 'finalizada';

    public const ESTADOS = [
        self::ESTADO_RECIBIDA => 'Recibida',
        self::ESTADO_EN_REVISION => 'En revisión',
        self::ESTADO_APROBADA => 'Aprobada',
        self::ESTADO_RECHAZADA => 'Rechazada',
        self::ESTADO_FINALIZADA => 'Finalizada',
    ];

    /** Estados en los que la solicitud sigue en trámite. */
    public const ESTADOS_ABIERTOS = [self::ESTADO_RECIBIDA, self::ESTADO_EN_REVISION];

    protected $fillable = [
        'codigo',
        'tramite_tipo_id',
        'asociado_nombre',
        'asociado_documento',
        'asociado_email',
        'asociado_telefono',
        'descripcion',
        'estado',
        'verificado_por',
        'verificado_at',
        'asignado_a',
        'justificacion',
        'finalizado_at',
    ];

    protected $casts = [
        'verificado_at' => 'datetime',
        'finalizado_at' => 'datetime',
    ];

    protected static function prefijoCodigo(): string
    {
        return 'FC';
    }

    public function tramiteTipo(): BelongsTo
    {
        return $this->belongsTo(TramiteTipo::class);
    }

    public function documentos(): HasMany
    {
        return $this->hasMany(SolicitudDocumento::class);
    }

    public function eventos(): HasMany
    {
        return $this->hasMany(SolicitudEvento::class)->latest('id');
    }

    public function verificadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verificado_por');
    }

    public function asignadoA(): BelongsTo
    {
        return $this->belongsTo(User::class, 'asignado_a');
    }

    /** @param array{estado?: ?string, tramite_tipo_id?: ?int, desde?: ?string, hasta?: ?string} $filtros */
    public function scopeFiltrar(Builder $query, array $filtros): Builder
    {
        return $query
            ->when($filtros['estado'] ?? null, fn ($q, $v) => $q->where('estado', $v))
            ->when($filtros['tramite_tipo_id'] ?? null, fn ($q, $v) => $q->where('tramite_tipo_id', $v))
            ->when($filtros['desde'] ?? null, fn ($q, $v) => $q->whereDate('created_at', '>=', $v))
            ->when($filtros['hasta'] ?? null, fn ($q, $v) => $q->whereDate('created_at', '<=', $v));
    }

    public function abierta(): bool
    {
        return in_array($this->estado, self::ESTADOS_ABIERTOS, true);
    }

    /** Guarda el archivo en el disco privado; los documentos del funcionario se versionan. */
    public function adjuntar(UploadedFile $archivo, string $origen): SolicitudDocumento
    {
        return $this->documentos()->create([
            'origen' => $origen,
            'nombre_original' => $archivo->getClientOriginalName(),
            'path' => $archivo->store("solicitudes/{$this->id}", SolicitudDocumento::DISCO),
            'version' => $origen === 'admin' ? $this->documentos()->where('origen', 'admin')->max('version') + 1 : 1,
        ]);
    }

    public function estadoLabel(): string
    {
        return self::ESTADOS[$this->estado] ?? $this->estado;
    }

    public function registrarEvento(string $accion, ?string $detalle = null, ?string $estadoAnterior = null, ?string $estadoNuevo = null, ?int $userId = null): SolicitudEvento
    {
        return $this->eventos()->create([
            'user_id' => $userId,
            'accion' => $accion,
            'detalle' => $detalle,
            'estado_anterior' => $estadoAnterior,
            'estado_nuevo' => $estadoNuevo,
            'created_at' => now(),
        ]);
    }
}
