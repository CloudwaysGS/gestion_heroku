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
        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('nom');
            $table->decimal('qteProduit', 30, 20);
            $table->decimal('prixProduit', 10, 2);
            $table->decimal('prixAchat', 10, 2)->nullable();
            $table->decimal('montant', 10, 2)->nullable();
            $table->string('nomDetail')->nullable();
            $table->decimal('nombre', 10, 2)->nullable();
            $table->decimal('prixDetail', 10, 2)->nullable();
            $table->decimal('qteDetail', 10, 2)->nullable();
            $table->decimal('nbreVendu', 10, 2)->nullable();
            $table->decimal('prixRevient', 10, 2)->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produits');
    }
};
