<?php

/**
 * Requête pour valider les données d’une nouvelle partie.
 *
 * @author François Santerre
 * @author Mathurin Moquin
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Vérifie les requêtes pour enregistrer les parties.
 */
class StorePartieRequest extends FormRequest
{
    /**
     * Vérifie si l’utilisateur peut envoyer cette requête.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Règles de validation pour créer une partie.
     *
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'adversaire' => 'required|string|max:255',
        ];
    }

    /**
     * Messages personnalisés en cas d’erreur de validation.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'adversaire.required' => 'Le champ adversaire est obligatoire.',
            'adversaire.string'   => 'Le champ adversaire doit être une chaîne de caractères.',
            'adversaire.max'      => 'Le champ adversaire ne peut pas dépasser 255 caractères.',
        ];
    }
}
