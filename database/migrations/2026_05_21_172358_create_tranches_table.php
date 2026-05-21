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
        Schema::create('tranches', function (Blueprint $table) {
            $table->id('idTranche');
            $table->string('libelle', 100);
            $table->float('montant')->default(0);
            $table->string('delai_mois', 2)->nullable();
            $table->string('delai_jour', 2)->nullable();
            $table->unsignedBigInteger('idScolarite')->nullable();
            $table->boolean('actif')->default(true);
            $table->unsignedBigInteger('idFondateur')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tranches');
    }
};
