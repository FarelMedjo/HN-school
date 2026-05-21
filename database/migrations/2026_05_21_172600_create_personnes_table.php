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
        Schema::create('personnes', function (Blueprint $table) {
            $table->id('idPers');
            $table->string('nom', 255);
            $table->string('prenom', 255);
            $table->date('dateNaissance')->nullable();
            $table->string('lieuNaissance', 100)->nullable();
            $table->string('mobile', 15)->nullable();
            $table->string('phone', 15)->nullable();
            $table->smallInteger('typePersonne')->nullable();
            $table->string('username', 100)->unique()->nullable();
            $table->string('password', 255)->nullable();
            $table->string('alanyaID', 15)->nullable();
            $table->unsignedBigInteger('idAdmin')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personnes');
    }
};
