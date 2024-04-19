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
        Schema::create('lembaga_anggotas', function (Blueprint $table) {
            $table->foreignId('lembaga_id')->constrained('lembagas')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('anggota_id', 16);
            $table->foreign('anggota_id')->references('nik')->on('penduduk')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('jabatan', 50);
            $table->string('keterangan', 50);
            $table->timestamps();
        });
    }

    /**
     * 
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lembaga_anggotas');
    }
};
