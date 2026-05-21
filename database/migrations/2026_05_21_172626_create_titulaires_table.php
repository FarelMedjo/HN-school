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
        Schema::create('titulaires', function (Blueprint $table) {
            $table->id('idTitu');
            $table->unsignedBigInteger('idClasse')->nullable();
            $table->unsignedBigInteger('idSalle')->nullable();
            $table->unsignedBigInteger('idPers')->nullable();
            $table->boolean('actif')->default(true);
            $table->unsignedBigInteger('idAdmin')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('titulaires');
    }
};
