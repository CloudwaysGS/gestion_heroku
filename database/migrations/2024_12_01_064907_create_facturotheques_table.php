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
        Schema::create('facturotheques', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('nbreLigne');
            $table->string('nomCient');
            $table->decimal('total', 10, 2);
            $table->string('adresse');
            $table->string('telephone')->nullable();
            $table->string('numFacture');
            $table->string('etat');
            $table->decimal('avance', 10, 2)->nullable();
            $table->decimal('reste', 10, 2)->nullable();
            $table->decimal('depot', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facturotheques');
    }
};
