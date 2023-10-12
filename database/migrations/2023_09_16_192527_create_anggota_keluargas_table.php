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
        Schema::create('anggota_keluarga', function (Blueprint $table) {
            $table->id('anggota_id');
            $table->string('nik', 16)->unique();
            $table->string('kk_id', 16)->nullable();
            $table->string('hubungan')->nullable();

            $table->foreign('nik')->references('nik')->on('penduduks')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('kk_id')->references('kk_id')->on('kartu_keluarga')->cascadeOnDelete()->cascadeOnUpdate();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggota_keluarga');
    }
};
