<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TipoAtestado;

class TipoAtestadoPolicy
{
    /**
     * Determine whether the user can view any TipoAtestado.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function viewAny(User $user)
    {
        return in_array($user->type, ['Admin', 'Gerente', 'Analista']);
    }

    /**
     * Determine whether the user can view the TipoAtestado.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TipoAtestado  $tipoAtestado
     * @return bool
     */
    public function view(User $user, TipoAtestado $tipoAtestado)
    {
        if ($user->type === "Admin") {
            return true;
        } elseif ($user->type === "Gerente") {
            return $user->setor == $tipoAtestado->user->setor;
        } elseif ($user->type === "Analista") {
            return $user->id == $tipoAtestado->user_id;
        }
        return false;
    }
}
