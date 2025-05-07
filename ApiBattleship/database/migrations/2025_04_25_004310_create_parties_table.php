<?php

/**
 * Migration pour créer la table des parties.
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
     * Crée la table 'parties' avec les champs requis.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('parties', function (Blueprint $table) {
            $table->id();
            $table->string('adversaire');
            $table->json('bateaux');
            $table->foreignId('user_id');
            $table->timestamps();
        });
    }

    /**
     * Supprime la table 'parties'.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('parties');
    }
};
