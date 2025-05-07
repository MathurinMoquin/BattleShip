<?php

/**
 * Migration pour créer la table des missiles.
 *
 * @author François Santerre
 * @author Mathurin Moquin
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crée la table 'missiles' avec les champs requis.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('missiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partie_id');
            $table->string('coordonnee');
            $table->boolean('resultat')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Supprime la table 'missiles'.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('missiles');
    }
};
