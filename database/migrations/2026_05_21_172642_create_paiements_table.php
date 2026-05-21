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
        Schema::create('paiements', function (Blueprint $table) {
            $table->id('idPaie');
            $table->unsignedBigInteger('matricule')->nullable();
            $table->unsignedBigInteger('idAca')->nullable();
            $table->float('montant')->default(0);
            $table->string('url', 255)->nullable();
            $table->string('commentaire', 255)->nullable();
            $table->unsignedBigInteger('idMode')->nullable();
            $table->string('operation_ID', 30)->nullable();
            $table->unsignedBigInteger('idPers')->nullable();
            $table->date('datePaie')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
