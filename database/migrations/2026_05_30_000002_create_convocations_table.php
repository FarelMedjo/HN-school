<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('convocations', function (Blueprint $table) {
            $table->id('idConvocation');
            $table->unsignedBigInteger('matricule');
            $table->string('objet');
            $table->text('motif')->nullable();
            $table->dateTime('dateRdv');
            $table->string('lieu')->nullable();
            $table->unsignedBigInteger('idAuteur')->nullable();
            $table->timestamps();

            $table->foreign('matricule')->references('matricule')->on('eleves')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('convocations');
    }
};
