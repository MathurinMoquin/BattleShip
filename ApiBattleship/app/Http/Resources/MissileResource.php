<?php

/**
 * Ressource JSON pour un missile.
 *
 * @author François Santerre
 * @author Mathurin Moquin
 */

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MissileResource extends JsonResource
{
    /**
     * Transforme le missile en tableau.
     *
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'coordonnee'  => $this->coordonnee,
            'resultat'    => $this->resultat,
            'created_at'  => $this->created_at,
        ];
    }
}
