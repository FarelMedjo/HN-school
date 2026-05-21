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
        Schema::create('salles', function (Blueprint $table) {
            $table->id('idSalle');
            $table->string('libelle', 30);
            $table->string('position', 100)->nullable();
            $table->string('surface', 30)->nullable();
            $table->unsignedBigInteger('idClasse')->nullable();
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
        Schema::dropIfExists('salles');
    }
};
