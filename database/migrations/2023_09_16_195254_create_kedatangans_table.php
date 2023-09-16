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
        Schema::create('kedatangan', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 16);
            $table->date('tanggal_datang');
            $table->string('alamat_asal');
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
        Schema::dropIfExists('kedatangan');
    }
};
