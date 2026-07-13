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
        Schema::create('contrats', function (Blueprint $table) {
            $table->id();
            $table->string('contrat_num')->unique();
            $table->string('adresse');

            // Sector link
            $table->string('nom_secteur');
            $table->foreign('nom_secteur')
                  ->references('nom_secteur')
                  ->on('secteurs');
            $table->integer('ordre_tournee');

            // Dates
            // date_debut defaults to the exact time of creation in the DB
            $table->timestamp('date_debut')->useCurrent(); 
            $table->date('date_fin')->nullable(); // Nullable if the contract is indefinite

            // Status
            $table->enum('status', ['actif', 'suspendu', 'resilie'])->default('actif');
            // The Composite Unique Constraint
            $table->unique(['nom_secteur', 'ordre_tournee']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contrats');
    }
};
