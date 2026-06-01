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
        Schema::create('presences', function (Blueprint $table) {
            $table->id('idPresence');
            $table->unsignedBigInteger('matricule');
            $table->date('date');
            $table->unsignedBigInteger('idCours')->nullable();
            $table->unsignedBigInteger('idSalle')->nullable();
            $table->enum('statut', ['present', 'absent', 'retard', 'justifie'])->default('present');
            $table->string('motif', 255)->nullable();
            $table->unsignedBigInteger('idPers')->nullable(); // qui a marqué (enseignant/admin)
            $table->timestamps();

            $table->index(['matricule', 'date']);
            $table->index(['date', 'idSalle']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presences');
    }
};
