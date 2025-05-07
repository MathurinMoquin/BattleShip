<?php

/**
 * Contrôleur pour la gestion des missiles.
 *
 * @author François Santerre
 * @author Mathurin Moquin
 */

namespace App\Http\Controllers;

use App\DecisionsIA\Tir;
use App\Http\Requests\StoreMissileRequest;
use App\Http\Requests\UpdateMissileRequest;
use App\Http\Resources\MissileResource;
use App\Models\Missile;
use App\Models\Partie;
use Illuminate\Support\Facades\Gate;

/**
 * Contrôleur pour la gestion des missiles.
 */
class MissileController extends Controller
{
    /**
     * Enregistre un nouveau missile pour une partie.
     *
     * @param StoreMissileRequest $request
     * @param int $partieId
     * @return MissileResource
     */
    public function store(StoreMissileRequest $request, $partieId): MissileResource
    {
        $partie = Partie::find($partieId);

        if (!$partie) {
            abort(404, 'La ressource n’existe pas.');
        }

        Gate::authorize('create', [Missile::class, $partie]);

        $missile = Missile::create([
            'partie_id' => $partie->id,
            'coordonnee' => Tir::tirReflechi($partieId),
            'resultat' => null,
        ]);

        return new MissileResource($missile);
    }

    /**
     * Met à jour le résultat d'un missile existant.
     *
     * @param UpdateMissileRequest $request
     * @param int $partieId
     * @param string $coordonnee
     * @return MissileResource
     */
    public function update(UpdateMissileRequest $request, $partieId, $coordonnee): MissileResource
    {
        $missile = Missile::with('partie')
            ->where('partie_id', $partieId)
            ->where('coordonnee', $coordonnee)
            ->firstOrFail();

        Gate::authorize('update', $missile);

        $missile->update($request->validated());

        if ($missile->resultat > 1)
        {
          // Tir::mettreAJourMissilesCoules($partieId);
        }

        return new MissileResource($missile);
    }
}
