<?php

namespace App\Policies;

use App\Models\Solicitud;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SolicitudPolicy
{
    public function gestionar(User $user, Solicitud $solicitud): Response
    {
        return $user->tramiteTiposVisibles()->whereKey($solicitud->tramite_tipo_id)->exists()
            ? Response::allow()
            : Response::deny('No tienes acceso a este tipo de trámite.');
    }
}
