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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 100);
            $table->string('username', 100)->unique();
            $table->string('password', 255);
            $table->boolean('actif')->default(true);
            $table->smallInteger('typePersonne')->nullable();
            $table->string('mobile', 15)->nullable();
            $table->string('alanyaID', 15)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
