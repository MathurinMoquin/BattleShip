<?php

/**
 * Classe pour gérer les tirs de l'IA au jeu Battleship.
 *
 * @author François Santerre
 * @author Mathurin Moquin
 */

namespace App\DecisionsIA;

use App\Models\Missile;
use App\Models\Partie;
use Illuminate\Support\Facades\Log;

class Tir
{
    public static function genererCoordonneeRandom($partieId): string
    {
        $missiles = Missile::where("partie_id", $partieId)->get();
        $tirs = $missiles->pluck('coordonnee')->toArray();

        $candidats = [];

        for ($row = 65; $row <= 74; $row++) {
            for ($col = 1; $col <= 10; $col++) {

                if ((($row - 65) + $col) % 2 !== 0) continue;

                $coord = chr($row) . "-$col";

                if (!in_array($coord, $tirs)) {
                    $candidats[] = $coord;
                }
            }
        }

        if (empty($candidats)) {

            for ($row = 65; $row <= 74; $row++) {
                for ($col = 1; $col <= 10; $col++) {
                    $coord = chr($row) . "-$col";
                    if (!in_array($coord, $tirs)) {
                        $candidats[] = $coord;
                    }
                }
            }
        }



        return $candidats[array_rand($candidats)];
    }

    public static function tirReflechi(int $partieId): string
    {
        if (self::verifierSiTirDejaTouche($partieId)) {
            Log::info("MISSILE TOUCHE");
            return self::tirOriente($partieId,
              Missile::where("partie_id", $partieId)
                    ->where("resultat", 1)
                    ->first());
        }

        return self::genererCoordonneeRandom($partieId);
    }

    public static function verifierSiTirDejaTouche(int $partieId): bool
    {
        $missiles = Missile::where("partie_id", $partieId)
            ->where("resultat", 1)
            ->get();

        return $missiles->count() > 0;
    }


    public static function tirerACote(int $partieId, $missileTouche): string
    {
        $missilesTouches = Missile::where("partie_id", $partieId)
        ->where("resultat", 1)
        ->get();

        $missile = Missile::where("partie_id", $partieId)
            ->get();

        Log::info($missilesTouches);

        Log::info($missile);

        //Add au debut de la liste
        $missilesTouches = $missilesTouches->prepend($missileTouche);

        foreach ($missilesTouches as $missileTouche)
        {


            [$lettre, $numero] = explode('-', $missileTouche->coordonnee);

            if ($lettre !== 'A') {
                $haut = chr(ord($lettre) - 1) . "-" . $numero;
                if (!Missile::where("partie_id", $partieId)->where("coordonnee", $haut)->exists()) {
                    return $haut;
                }
            }

            // Bas
            if ($lettre !== 'J') {
                $bas = chr(ord($lettre) + 1) . "-" . $numero;
                if (!Missile::where("partie_id", $partieId)->where("coordonnee", $bas)->exists()) {
                    return $bas;
                }
            }

            // Droite
            if ((int)$numero < 10) {
                $droite = $lettre . "-" . ((int)$numero + 1);
                if (!Missile::where("partie_id", $partieId)->where("coordonnee", $droite)->exists()) {
                    return $droite;
                }
            }

            // Gauche
            if ((int)$numero > 1) {
                $gauche = $lettre . "-" . ((int)$numero - 1);
                if (!Missile::where("partie_id", $partieId)->where("coordonnee", $gauche)->exists()) {
                    return $gauche;
                }
            }
        }




        return self::genererCoordonneeRandom($partieId);
    }

    public static function tirOriente(int $partieId, $missileTouche): string
    {
        [$lettre, $numero] = explode('-', $missileTouche->coordonnee);
        $numero = (int)$numero;

        // DROITE : si la case juste à droite a aussi été touchée, on continue encore à droite
        if ($numero < 10) {
            $droite = $lettre . "-" . ($numero + 1);
            if (Missile::where("partie_id", $partieId)->where("coordonnee", $droite)->where("resultat", 1)->exists()) {
                $droite2 = $lettre . "-" . ($numero + 2);
                if ($numero + 2 <= 10 && !Missile::where("partie_id", $partieId)->where("coordonnee", $droite2)->exists()) {
                    return $droite2;
                }
            }
        }

        // GAUCHE
        if ($numero > 1) {
            $gauche = $lettre . "-" . ($numero - 1);
            if (Missile::where("partie_id", $partieId)->where("coordonnee", $gauche)->where("resultat", 1)->exists()) {
                $gauche2 = $lettre . "-" . ($numero - 2);
                if ($numero - 2 >= 1 && !Missile::where("partie_id", $partieId)->where("coordonnee", $gauche2)->exists()) {
                    return $gauche2;
                }
            }
        }

        // HAUT
        if ($lettre !== 'A') {
            $lettreAvant = chr(ord($lettre) - 1);
            $haut = $lettreAvant . "-" . $numero;
            if (Missile::where("partie_id", $partieId)->where("coordonnee", $haut)->where("resultat", 1)->exists()) {
                $lettreAvant2 = chr(ord($lettre) - 2);
                if ($lettreAvant2 >= 'A') {
                    $haut2 = $lettreAvant2 . "-" . $numero;
                    if (!Missile::where("partie_id", $partieId)->where("coordonnee", $haut2)->exists()) {
                        return $haut2;
                    }
                }
            }
        }

        // BAS
        if ($lettre !== 'J') {
            $lettreApres = chr(ord($lettre) + 1);
            $bas = $lettreApres . "-" . $numero;
            if (Missile::where("partie_id", $partieId)->where("coordonnee", $bas)->where("resultat", 1)->exists()) {
                $lettreApres2 = chr(ord($lettre) + 2);
                if ($lettreApres2 <= 'J') {
                    $bas2 = $lettreApres2 . "-" . $numero;
                    if (!Missile::where("partie_id", $partieId)->where("coordonnee", $bas2)->exists()) {
                        return $bas2;
                    }
                }
            }
        }


        return self::tirerACote($partieId, $missileTouche);
    }

    public static function mettreAJourMissilesCoules(int $partieId): void
    {
        $touches = Missile::where("partie_id", $partieId)
            ->where("resultat", 1)
            ->get();

        $groupes = self::trouverGroupesTouchesAdjacentes($touches->pluck('coordonnee')->toArray());

        foreach ($groupes as $groupe) {
            $taille = count($groupe);
            $valeur = match ($taille) {
                5 => 2, // Porte-avions
                4 => 3, // Cuirassé
                3 => self::choisir3CaseDisponible($partieId, $groupe), // destroyer ou sous-marin
                2 => 6, // Patrouilleur
                default => null,
            };

            if ($valeur !== null) {
                Missile::where("partie_id", $partieId)
                    ->whereIn("coordonnee", $groupe)
                    ->update(["resultat" => $valeur]);
            }
        }
    }

    private static function trouverGroupesTouchesAdjacentes(array $touches): array
    {
        $grille = array_fill_keys($touches, true);
        $groupes = [];
        $visitees = [];

        foreach ($touches as $depart) {
            if (in_array($depart, $visitees)) continue;

            $pile = [$depart];
            $groupe = [];

            while ($pile) {
                $courant = array_pop($pile);
                if (in_array($courant, $visitees)) continue;

                $visitees[] = $courant;
                $groupe[] = $courant;

                [$lettre, $chiffre] = explode('-', $courant);
                $chiffre = (int)$chiffre;

                $autour = [];
                if ($lettre !== 'A') $autour[] = chr(ord($lettre) - 1) . "-$chiffre";
                if ($lettre !== 'J') $autour[] = chr(ord($lettre) + 1) . "-$chiffre";
                if ($chiffre > 1)     $autour[] = "$lettre-" . ($chiffre - 1);
                if ($chiffre < 10)    $autour[] = "$lettre-" . ($chiffre + 1);

                foreach ($autour as $adj) {
                    if (isset($grille[$adj]) && !in_array($adj, $visitees)) {
                        $pile[] = $adj;
                    }
                }
            }

            $groupes[] = $groupe;
        }

        return $groupes;
    }

    private static function choisir3CaseDisponible(int $partieId, array $coordonnes): int
    {
        $valeursDejaCoulees = Missile::where("partie_id", $partieId)
            ->whereIn("resultat", [4, 5])
            ->pluck("resultat")
            ->unique()
            ->toArray();

        return in_array(4, $valeursDejaCoulees) ? 5 : 4;
    }



}
