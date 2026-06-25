<?php

namespace App\Policies;

use App\Models\Infraestructura\Infraestructura;
use App\Models\User;

class InfraestructuraPolicy
{
    public function before(User $user): ?bool
    {
        if ($user->hasRole('SuperAdministrador')) {
            return true;
        }
        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('infraestructura.view');
    }

    public function view(User $user, Infraestructura $infraestructura): bool
    {
        if (!$user->hasPermissionTo('infraestructura.view')) {
            return false;
        }
        // Verifica que la infraestructura pertenezca a una sede del usuario
        $userSedeIds = $user->sedes->pluck('id');
        return $userSedeIds->contains($infraestructura->sede_id)
            || $user->hasRole('Administrador');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('infraestructura.create');
    }

    public function update(User $user, Infraestructura $infraestructura): bool
    {
        if (!$user->hasPermissionTo('infraestructura.edit')) {
            return false;
        }
        $userSedeIds = $user->sedes->pluck('id');
        return $userSedeIds->contains($infraestructura->sede_id)
            || $user->hasRole('Administrador');
    }

    public function delete(User $user, Infraestructura $infraestructura): bool
    {
        if (!$user->hasPermissionTo('infraestructura.delete')) {
            return false;
        }
        $userSedeIds = $user->sedes->pluck('id');
        return $userSedeIds->contains($infraestructura->sede_id)
            || $user->hasRole('Administrador');
    }
}
