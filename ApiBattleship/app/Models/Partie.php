<?php

/**
 * Modèle représentant une partie de Battleship.
 *
 * @author François Santerre
 * @author Mathurin Moquin
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partie extends Model
{
    /** @use HasFactory<\Database\Factories\PartieFactory> */
    use HasFactory;

    /**
     * Champs qui peuvent être assignés.
     *
     * @var array<int, string>
     */
    protected $fillable = ['adversaire', 'bateaux', 'user_id'];

    /**
     * Conversion automatique des types de champs.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'bateaux' => 'array',
    ];
}
