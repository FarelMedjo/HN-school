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
        Schema::create('scolarites', function (Blueprint $table) {
            $table->id('idScolarite');
            $table->float('inscription')->default(0);
            $table->float('pension')->default(0);
            $table->smallInteger('nbreTranche')->default(1);
            $table->text('description')->nullable();
            $table->unsignedBigInteger('idCycle')->nullable();
            $table->unsignedBigInteger('idFondateur')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scolarites');
    }
};
