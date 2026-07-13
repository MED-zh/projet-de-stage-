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
    Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            // Role definition
            $table->enum('role', ['admin', 'client', 'technicien','caissier'])->default('client');

            // General Info
            $table->string('phone')->nullable();
            $table->string('adresse')->nullable();

            // Unique Identifiers
            $table->string('cin')->nullable()->unique(); // Essential for tech_cin relations

            /**
             * Technician Specific: 
             * Instead of a simple string 'zone_intervention', we link to the 'secteurs' table.
             * A technician is responsible for a specific sector.
             */
            
            $table->string('nom_secteur')->nullable();
            $table->foreign('nom_secteur')
                  ->references('nom_secteur')
                  ->on('secteurs');


            /**
             * Client Specific:
             * A client must have a contract number to register.
             */
            $table->string('contrat_num')->unique()->nullable();
            $table->foreign('contrat_num')
                  ->references('contrat_num')
                  ->on('contrats')
                  ->onDelete('cascade');

            $table->rememberToken();
            $table->timestamps();
        });

    Schema::create('password_reset_tokens', function (Blueprint $table) {
        $table->string('email')->primary();
        $table->string('token');
        $table->timestamp('created_at')->nullable();
    });

    Schema::create('sessions', function (Blueprint $table) {
        $table->string('id')->primary();
        $table->foreignId('user_id')->nullable()->index();
        $table->string('ip_address', 45)->nullable();
        $table->text('user_agent')->nullable();
        $table->longText('payload');
        $table->integer('last_activity')->index();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
