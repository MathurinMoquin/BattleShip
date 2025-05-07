<?php

/**
 * Politique d'autorisation pour les parties.
 *
 * @author François Santerre
 * @author Mathurin Moquin
 */

namespace App\Policies;

use App\Models\Partie;
use App\Models\User;

class PartiePolicy
{
    /**
     * Vérifie si l'utilisateur peut modifier la partie.
     *
     * @param User $user
     * @param Partie $partie
     * @return bool
     */
    public function update(User $user, Partie $partie): bool
    {
        return $user->id === $partie->user_id;
    }

    /**
     * Vérifie si l'utilisateur peut supprimer la partie.
     *
     * @param User $user
     * @param Partie $partie
     * @return bool
     */
    public function delete(User $user, Partie $partie): bool
    {
        return $user->id === $partie->user_id;
    }
}
