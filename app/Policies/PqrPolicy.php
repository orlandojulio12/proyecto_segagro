<?php

namespace App\Policies;

use App\Models\Complaint\Pqr;
use App\Models\User;

class PqrPolicy
{
    public function before(User $user): ?bool
    {
        // SuperAdministrador puede hacer todo
        if ($user->hasRole('SuperAdministrador')) {
            return true;
        }
        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('pqr.view');
    }

    public function view(User $user, Pqr $pqr): bool
    {
        return $user->hasPermissionTo('pqr.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('pqr.create');
    }

    public function update(User $user, Pqr $pqr): bool
    {
        if (!$user->hasPermissionTo('pqr.edit')) {
            return false;
        }
        // Solo puede editar si creó la PQR o es Gestor PQR
        return $pqr->user_id === $user->id || $user->hasRole('Gestor PQR');
    }

    public function delete(User $user, Pqr $pqr): bool
    {
        if (!$user->hasPermissionTo('pqr.delete')) {
            return false;
        }
        return $pqr->user_id === $user->id || $user->hasRole('Gestor PQR');
    }
}
