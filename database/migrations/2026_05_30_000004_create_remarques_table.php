<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('remarques', function (Blueprint $table) {
            $table->id('idRemarque');
            $table->unsignedBigInteger('matricule');
            $table->date('date');
            $table->string('categorie', 30)->default('comportement');
            $table->text('contenu');
            $table->unsignedBigInteger('idAuteur')->nullable();
            $table->timestamps();

            $table->foreign('matricule')->references('matricule')->on('eleves')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('remarques');
    }
};
