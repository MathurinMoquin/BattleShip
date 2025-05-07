<?php

/**
 * Requête pour valider les données d’un missile à créer.
 *
 * @author François Santerre
 * @author Mathurin Moquin
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Vérifie les requêtes pour enregistrer les missiles.
 */
class StoreMissileRequest extends FormRequest
{
    /**
     * Vérifie si l’utilisateur peut faire cette requête.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Règles de validation pour l'enregistrement d'un missile.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'coordonnee' => 'string|max:5',
            'resultat'   => 'integer|min:0|max:10',
        ];
    }
}
