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
        Schema::create('dinamikas', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 16)->nullable();
            $table->foreign('nik')->references('nik')->on('penduduk')->cascadeOnUpdate()->cascadeOnDelete();
            $table->morphs('dinamika');
            $table->string('jenis_dinamika')->nullable();
            $table->string('catatan_dinamika')->nullable();
            $table->date('tanggal_dinamika')->nullable();
            $table->date('tanggal_lapor')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dinamikas');
    }
};
