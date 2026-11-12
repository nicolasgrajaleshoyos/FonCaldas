<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'username', 'email', 'password', 'is_super_admin', 'is_active'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_super_admin' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function tramiteTipos(): BelongsToMany
    {
        return $this->belongsToMany(TramiteTipo::class);
    }

    public function solicitudesAsignadas(): HasMany
    {
        return $this->hasMany(Solicitud::class, 'asignado_a');
    }

    public function puedeVer(TramiteTipo $tramiteTipo): bool
    {
        if ($this->is_super_admin) {
            return true;
        }

        return $this->tramiteTipos->contains('id', $tramiteTipo->id);
    }

    /** El administrador principal original: el primero que se creó. Es el único que edita cuentas de otros administradores y contraseñas ajenas. */
    // ponytail: se deduce del id más bajo entre administradores; usar una columna explícita si hiciera falta traspasar el rol.
    public function esPropietario(): bool
    {
        return $this->is_super_admin && $this->id === static::idPropietario();
    }

    /** Método estático con once(): una sola consulta por petición aunque la lista de usuarios lo pregunte por cada fila. */
    private static function idPropietario(): ?int
    {
        return once(fn () => static::where('is_super_admin', true)->min('id'));
    }

    /** Editar la cuenta de otro: el propietario edita a cualquiera; los demás administradores, solo funcionarios y a sí mismos. */
    public function puedeEditar(self $otro): bool
    {
        return $this->is($otro) || $this->esPropietario() || ! $otro->is_super_admin;
    }

    /** Cambiar la contraseña de otro lo puede hacer solo el propietario. */
    public function puedeCambiarClaveDe(self $otro): bool
    {
        return $this->is($otro) || $this->esPropietario();
    }

    /** Tipos de trámite que este usuario puede gestionar (todos si es super admin). */
    public function tramiteTiposVisibles(): Builder
    {
        return $this->is_super_admin
            ? TramiteTipo::query()
            : TramiteTipo::whereIn('id', $this->tramiteTipos()->select('tramite_tipos.id'));
    }

    /** Usuarios activos que pueden atender solicitudes de un tipo (super admins incluidos). */
    public function scopeResponsablesDe(Builder $query, int $tramiteTipoId): Builder
    {
        return $query->where('is_active', true)
            ->where(fn ($q) => $q->where('is_super_admin', true)
                ->orWhereHas('tramiteTipos', fn ($t) => $t->where('tramite_tipos.id', $tramiteTipoId)));
    }

    /** Responsable con menos solicitudes abiertas; los super admins solo si nadie más atiende el tipo. */
    public static function responsableSugerido(int $tramiteTipoId, ?int $excluir = null): ?self
    {
        return static::responsablesDe($tramiteTipoId)
            ->when($excluir, fn ($q) => $q->whereKeyNot($excluir))
            ->withCount(['solicitudesAsignadas' => fn ($q) => $q->whereIn('estado', Solicitud::ESTADOS_ABIERTOS)])
            ->orderBy('is_super_admin')
            ->orderBy('solicitudes_asignadas_count')
            ->first();
    }
}
