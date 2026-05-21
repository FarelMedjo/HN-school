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
        Schema::create('mode_paiements', function (Blueprint $table) {
            $table->id('idMode');
            $table->string('libelle', 100);
            $table->text('information')->nullable();
            $table->boolean('actif')->default(true);
            $table->unsignedBigInteger('idFondateur')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mode_paiements');
    }
};
