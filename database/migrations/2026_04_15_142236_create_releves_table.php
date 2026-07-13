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
         Schema::create('releves', function (Blueprint $table) {
            $table->id();

            $table->date('date_releve');
            $table->enum('type', ['eau', 'electricite']); // Keeps the type (water/elec)

            $table->float('index_depart');
            $table->float('index_fin');
            $table->float('consommation');
             $table->string('matricule');
            // Link to the specific meter being read
             $table->foreign('matricule')->references('matricule')->on('compteurs');

            // Keeping your original identifying attributes
            $table->string('contrat_num');
            $table->string('tech_cin');

            // Relations
            $table->foreign('contrat_num')->references('contrat_num')->on('contrats');
            $table->foreign('tech_cin')->references('cin')->on('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('releves');
    }
};
