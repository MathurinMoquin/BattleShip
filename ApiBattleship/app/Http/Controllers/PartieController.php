<?php

/**
 * Contrôleur pour la gestion des parties.
 *
 * @author François Santerre
 * @author Mathurin Moquin
 */

namespace App\Http\Controllers;

use App\Http\Requests\StorePartieRequest;
use App\Http\Resources\PartieResource;
use App\Models\Partie;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

/**
 * Contrôleur pour la gestion des parties Battleship.
 */
class PartieController extends Controller
{
    /**
     * Crée une nouvelle partie.
     *
     * @param StorePartieRequest $request
     * @return PartieResource
     */
    public function store(StorePartieRequest $request): PartieResource
    {
        $bateaux = $this->genererBateaux();

        $partie = Partie::create([
            'adversaire' => $request->adversaire,
            'bateaux' => $bateaux,
            'user_id' => Auth::id(),
        ]);

        return new PartieResource($partie);
    }

    /**
     * Supprime une partie existante.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $partie = Partie::find($id);

        if (!$partie) {
            return response()->json([
                'message' => 'La ressource n’existe pas.'
            ], 404);
        }

        Gate::authorize('delete', $partie);

        $data = [
            'id' => $partie->id,
            'user_id' => Auth::id(),
            'adversaire' => $partie->adversaire,
            'bateaux' => $partie->bateaux,
            'created_at' => $partie->created_at,
        ];

        $partie->delete();

        return response()->json([
            'data' => $data
        ]);
    }

    /**
     * Retourne un placement aléatoire de bateaux selon une stratégie.
     *
     * @return array
     */
    private function genererBateaux(): array
    {
        return $this->placementAleatoire();
    }

    /**
     * Positionne les bateaux aléatoirement sur le plateau.
     *
     * @return array
     */
    public function placementAleatoire(): array
    {
        $type_bateaux = [
            'porte-avions' => 5,
            'cuirasse' => 4,
            'destroyer' => 3,
            'sous-marin' => 3,
            'patrouilleur' => 2,
        ];
        $positions_bateaux = [];

        foreach ($type_bateaux as $type => $size) {
            $positions_bateaux[$type] = $this->generer1Bateau($positions_bateaux, $size);
        }

        return $positions_bateaux;
    }

    /**
     * Génère un bateau aléatoirement
     *
     * @param $positions_bateaux array
     * @param $size int
     * @return array
     */
    private function generer1Bateau($positions_bateaux, $size): array
    {
        $board_size = 10;
        $max_board_length_by_size = $board_size - $size;

        $posX = Rand(0, $max_board_length_by_size);
        $posY = Rand(0, $max_board_length_by_size);
        $dir = Rand(0, 2) == 0 ? 1 : 10;

        $positionId = $posY * 10 + $posX;

        $array = [];
        for ($i = 0; $i < $size; $i++) {
            $pos = $this->idToPosition($positionId + $i * $dir);
            if ($this->ifPositionBateauExist($positions_bateaux, $pos)) {
                return $this->generer1Bateau($positions_bateaux, $size);
            }
            $array[] = $pos;
        }

        return $array;
    }

    /**
     * Detecte si la position généré est déja utilisé par un autre bateau.
     *
     * @param $positions_bateaux array
     * @param $position string
     * @return bool
     */
    private function ifPositionBateauExist($positions_bateaux, $position): bool
    {
        foreach ($positions_bateaux as $position_bateau) {
            for ($i = 0; $i < count($position_bateau); $i++) {
                if ($position_bateau[$i] == $position) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Converti un Id de case en position.
     *
     * @param $id
     * @return string
     */
    private function idToPosition($id): string
    {
        $number = $id % 10 + 1;
        $letter = $this->getLetter((int) floor($id / 10 + 1));
        return "$letter-$number";
    }

    /**
     * Transforme un chiffre en lettre.
     *
     * @param $int int
     * @return string
     */
    private function getLetter($int)
    {
        return match ($int) {
            1 => "A",
            2 => "B",
            3 => "C",
            4 => "D",
            5 => "E",
            6 => "F",
            7 => "G",
            8 => "H",
            9 => "I",
            10 => "J",
            default => "",
        };
    }
}
