<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prix', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['eau', 'electricite']);
            $table->integer('tranche');
            $table->decimal('limite_min', 8, 2);
            $table->decimal('limite_max', 8, 2);
            $table->decimal('prix_unitaire', 8, 4);
            $table->string('label')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prix');
    }
};