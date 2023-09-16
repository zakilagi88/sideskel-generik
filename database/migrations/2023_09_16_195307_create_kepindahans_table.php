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
        Schema::create('kepindahan', function (Blueprint $table) {
            $table->id();

            $table->string('nik', 16);
            $table->date('tanggal_pindah');
            $table->string('alamat_tujuan');
            $table->string('keterangan');

            $table->foreign('nik')->references('nik')->on('penduduks')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kepindahan');
    }
};
