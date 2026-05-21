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
        Schema::create('livres', function (Blueprint $table) {
            $table->id('idLivre');
            $table->string('titre', 255);
            $table->string('auteurs', 255)->nullable();
            $table->float('prix')->default(0);
            $table->unsignedBigInteger('idSpecialite')->nullable();
            $table->string('edition', 255)->nullable();
            $table->integer('annee_parution')->nullable();
            $table->smallInteger('totalCopie')->default(0);
            $table->unsignedBigInteger('idAdmin')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livres');
    }
};
