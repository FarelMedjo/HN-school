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
        Schema::create('emprunts', function (Blueprint $table) {
            $table->id('idEmprunt');
            $table->unsignedBigInteger('idLivre');
            $table->unsignedBigInteger('matricule');
            $table->date('dateEmprunt');
            $table->date('dateRetourPrevue');
            $table->date('dateRetourReelle')->nullable();
            $table->enum('statut', ['en_cours', 'rendu', 'retard'])->default('en_cours');
            $table->text('remarque')->nullable();
            $table->unsignedBigInteger('idAdmin')->nullable();
            $table->timestamps();

            $table->foreign('idLivre')->references('idLivre')->on('livres')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emprunts');
    }
};
