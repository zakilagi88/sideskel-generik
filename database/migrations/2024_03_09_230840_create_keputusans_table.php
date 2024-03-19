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
        Schema::create('keputusans', function (Blueprint $table) {
            $table->id();
            $table->string('kep_nomor');
            $table->date('kep_tanggal');
            $table->string('kep_tentang');
            $table->text('kep_uraian_singkat')->nullable();
            $table->string('kep_no_dilaporkan')->nullable();
            $table->date('kep_tgl_dilaporkan')->nullable();
            $table->string('kep_keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keputusans');
    }
};