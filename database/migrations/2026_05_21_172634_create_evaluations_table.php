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
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id('idEval');
            $table->float('note')->default(0);
            $table->string('appreciation', 255)->nullable();
            $table->unsignedBigInteger('matricule')->nullable();
            $table->unsignedBigInteger('idEpreuve')->nullable();
            $table->unsignedBigInteger('idCours')->nullable();
            $table->unsignedBigInteger('idSession')->nullable();
            $table->unsignedBigInteger('idPers')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
