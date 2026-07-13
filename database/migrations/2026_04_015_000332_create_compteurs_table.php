<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
     Schema::create('compteurs', function (Blueprint $table) {
        $table->id();
        
        // Clé étrangère vers la table contrats
        $table->string('contrat_num');

       $table->foreign('contrat_num')
          ->references('contrat_num')
          ->on('contrats');

        // Type de compteur
        $table->enum('type', ['eau', 'electricite']);

        // Identifiant unique du compteur physique
        $table->string('matricule')->unique();

        // Index actuel (pour l'affichage rapide sans parcourir tous les relevés)
        $table->integer('dernier_index')->default(0);

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compteurs');
    }
};
