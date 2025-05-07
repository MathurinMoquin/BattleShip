<?php

/**
 * Politique d'autorisation pour les missiles.
 *
 * @author François Santerre
 * @author Mathurin Moquin
 */

namespace App\Policies;

use App\Models\Missile;
use App\Models\Partie;
use App\Models\User;

class MissilePolicy
{
    /**
     * Vérifie si l'utilisateur peut créer un missile.
     *
     * @param User $user
     * @param Partie $partie
     * @return bool
     */
    public function create(User $user, Partie $partie): bool
    {
        return $user->id === $partie->user_id;
    }

    /**
     * Vérifie si l'utilisateur peut mettre à jour le missile.
     *
     * @param User $user
     * @param Missile $missile
     * @return bool
     */
    public function update(User $user, Missile $missile): bool
    {
        return $user->id === $missile->partie->user_id;
    }

    /**
     * Vérifie si l'utilisateur peut supprimer le missile.
     *
     * @param User $user
     * @param Missile $missile
     * @return bool
     */
    public function delete(User $user, Missile $missile): bool
    {
        return false;
    }

    /**
     * Vérifie si l'utilisateur peut restaurer le missile.
     *
     * @param User $user
     * @param Missile $missile
     * @return bool
     */
    public function restore(User $user, Missile $missile): bool
    {
        return false;
    }
}
