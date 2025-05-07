<?php

/**
 * Ressource JSON pour une partie.
 *
 * @author François Santerre
 * @author Mathurin Moquin
 */

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PartieResource extends JsonResource
{
    /**
     * Transforme la partie en tableau.
     *
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'adversaire'  => $this->adversaire,
            'bateaux'     => $this->bateaux,
            'created_at'  => $this->created_at,
        ];
    }
}
