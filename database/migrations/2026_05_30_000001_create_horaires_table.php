<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('horaires', function (Blueprint $table) {
            $table->id('idHoraire');
            $table->string('heure', 5)->unique();
            $table->timestamps();
        });

        $now = now();
        $defaults = ['07:30', '08:30', '09:30', '10:30', '11:30', '13:00', '14:00', '15:00', '16:00'];

        DB::table('horaires')->insert(array_map(fn ($h) => [
            'heure'      => $h,
            'created_at' => $now,
            'updated_at' => $now,
        ], $defaults));
    }

    public function down(): void
    {
        Schema::dropIfExists('horaires');
    }
};
