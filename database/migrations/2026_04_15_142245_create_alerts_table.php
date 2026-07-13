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
       Schema::create('alerts', function (Blueprint $table) {
            $table->id();

            $table->text('description'); 
            $table->date('date_alert');

            // Using ENUM for fixed states
            // Default is set to 'en_attente' so it's ready for Admin review
            $table->enum('status', ['en_attente', 'en_cours', 'resolu', 'annule'])
                  ->default('en_attente');

            $table->string('tech_cin'); 
            $table->string('contrat_num');

            // Foreign Key Relations
            $table->foreign('contrat_num')
                  ->references('contrat_num')
                  ->on('contrats')
                 ;

            $table->foreign('tech_cin')
                  ->references('cin')
                  ->on('users')
                ;

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alerts');
    }
};
