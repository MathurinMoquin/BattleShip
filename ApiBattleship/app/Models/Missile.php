<?php

/**
 * Modèle représentant un missile tiré dans une partie.
 *
 * @author François Santerre
 * @author Mathurin Moquin
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Missile extends Model
{
    /** @use HasFactory<\Database\Factories\MissileFactory> */
    use HasFactory;

    /**
     * Champs qui peuvent être assignés.
     *
     * @var array<int, string>
     */
    protected $fillable = ['partie_id', 'coordonnee', 'resultat'];

    /**
     * Relation avec la partie associée.
     *
     * @return BelongsTo
     */
    public function partie(): BelongsTo
    {
        return $this->belongsTo(Partie::class);
    }
}
