<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appreciations', function (Blueprint $table) {
            $table->id('idAppreciation');
            $table->unsignedBigInteger('matricule');
            $table->unsignedBigInteger('idTrimes');
            $table->text('contenu');
            $table->unsignedBigInteger('idAuteur')->nullable();
            $table->timestamps();

            $table->unique(['matricule', 'idTrimes']);
            $table->foreign('matricule')->references('matricule')->on('eleves')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appreciations');
    }
};
