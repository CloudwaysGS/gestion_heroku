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
        Schema::create('dettes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->nullable()->constrained('clients')->onDelete('cascade');
            $table->string('nom')->nullable(); // commentaire peut être null
            $table->decimal('montant', 10, 2)->nullable();
            $table->decimal('reste',10, 2);
            $table->string('commentaire')->nullable(); // commentaire peut être null
            $table->string('etat')->default('impayée'); // état par défaut
            $table->float('depot')->nullable(); // état par défaut
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dettes');
    }
};
