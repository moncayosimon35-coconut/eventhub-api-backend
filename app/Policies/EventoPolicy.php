<?php

namespace App\Policies;

use App\Models\Evento;
use App\Models\User;

class EventoPolicy
{
    /**
     * Determinar si el usuario puede actualizar el evento.
     */
    public function update(User $user, Evento $evento): bool
    {
        return $user->id === $evento->user_id;
    }
}
