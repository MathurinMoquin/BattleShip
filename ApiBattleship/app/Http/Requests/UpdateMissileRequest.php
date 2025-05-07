<?php

/**
 * Requête pour valider les données lors de la mise à jour d’un missile.
 *
 * @author François Santerre
 * @author Mathurin Moquin
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMissileRequest extends FormRequest
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
     * Règles de validation pour le champ resultat.
     *
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'resultat' => 'required|integer|in:0,1,2,3,4,5,6',
        ];
    }

    /**
     * Messages personnalisés pour les erreurs de validation.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'resultat.required' => 'Le champ resultat est obligatoire.',
            'resultat.integer'  => 'Le champ resultat doit être un nombre entier.',
            'resultat.in'       => 'Le champ resultat est invalide.',
        ];
    }
}
