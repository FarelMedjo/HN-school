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
        Schema::create('session_examens', function (Blueprint $table) {
            $table->id('idSession');
            $table->string('libelle', 255);
            $table->string('description', 255)->nullable();
            $table->unsignedBigInteger('idTrimestre')->nullable();
            $table->unsignedBigInteger('idPers')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_examens');
    }
};
