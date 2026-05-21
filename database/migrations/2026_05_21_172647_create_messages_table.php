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
        Schema::create('messages', function (Blueprint $table) {
            $table->id('idMessages');
            $table->unsignedBigInteger('idExp_Pers')->nullable();
            $table->unsignedBigInteger('idParent')->nullable();
            $table->string('objet', 255);
            $table->text('information')->nullable();
            $table->smallInteger('type_message')->nullable();
            $table->string('AnneeAcade', 15)->nullable();
            $table->boolean('valider')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
