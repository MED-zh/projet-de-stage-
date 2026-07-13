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
             Schema::create('paiements', function (Blueprint $table) {
                $table->id();
                $table->string('numero_recu')->unique();
                $table->foreignId('facture_id')->constrained('factures');
                $table->foreignId('recu_par')->constrained('users');
                $table->string('contrat_num');
                $table->foreign('contrat_num')->references('contrat_num')->on('contrats');
                $table->decimal('montant_total', 10, 2);
                $table->date('date_paiement');
                $table->text('notes')->nullable();
                $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
