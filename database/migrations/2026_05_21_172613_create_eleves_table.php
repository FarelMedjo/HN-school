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
        Schema::create('eleves', function (Blueprint $table) {
            $table->bigIncrements('matricule');
            $table->string('nom', 60);
            $table->string('prenom', 60);
            $table->date('dateNaissance')->nullable();
            $table->string('lieuNaissance', 30)->nullable();
            $table->smallInteger('sexe')->nullable();
            $table->string('langue', 30)->nullable();
            $table->string('photoURL', 255)->nullable();
            $table->boolean('actif')->default(true);
            $table->unsignedBigInteger('idVilleNaissance')->nullable();
            $table->unsignedBigInteger('idAdmin')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eleves');
    }
};
