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
        Schema::create('frequentes', function (Blueprint $table) {
            $table->id('idFrequente');
            $table->unsignedBigInteger('idSalle')->nullable();
            $table->unsignedBigInteger('idAcademi')->nullable();
            $table->unsignedBigInteger('matricule')->nullable();
            $table->string('commentaire', 255)->nullable();
            $table->unsignedBigInteger('idAdmin')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('frequentes');
    }
};
