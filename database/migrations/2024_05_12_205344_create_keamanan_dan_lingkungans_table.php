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
        Schema::create('keamanan_dan_lingkungans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deskel_profil_id')->constrained('deskel_profils')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('jenis');
            $table->json('data');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keamanan_dan_lingkungans');
    }
};
