<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('factures', function (Blueprint $table) {
            $table->id();

            // Unique invoice number
            $table->string('numero_facture')->unique();

            // Billing period
            $table->integer('mois');
            $table->integer('annee');

            // Type of utility
            $table->enum('type', ['eau', 'electricite']);

            // Consumption details
            $table->decimal('consommation', 10, 2);  // m³ or kWh
            $table->decimal('montant_ht', 10, 2);    // amount before tax
            $table->decimal('tva', 5, 2)->default(20.00); // TVA 20% Morocco
            $table->decimal('montant_ttc', 10, 2);   // final amount with tax

            // Status
            $table->enum('status', ['impayee', 'payee', 'en_retard'])
                  ->default('impayee');

            $table->date('date_emission');
            $table->date('date_echeance');  // due date (usually 30 days)

            // Relations
            $table->string('contrat_num');
            $table->foreign('contrat_num')
                  ->references('contrat_num')
                  ->on('contrats')
                  ->onDelete('cascade');

            // Link to the releve that generated this facture
            $table->foreignId('releve_id')
                  ->constrained('releves')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('factures');
    }
};