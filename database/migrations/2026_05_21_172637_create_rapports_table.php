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
        Schema::create('rapports', function (Blueprint $table) {
            $table->id('idRap');
            $table->string('libelle', 100);
            $table->integer('points')->default(0);
            $table->unsignedBigInteger('matricule')->nullable();
            $table->unsignedBigInteger('idAca')->nullable();
            $table->text('commentaire')->nullable();
            $table->date('event_date')->nullable();
            $table->unsignedBigInteger('idPers')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rapports');
    }
};
