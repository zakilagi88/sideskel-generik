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
        Schema::create('kesehatan_anaks', function (Blueprint $table) {
            $table->id();
            $table->string('anak_id', 16)->nullable();
            $table->string('ibu_id', 16)->nullable();
            $table->foreign('anak_id')->references('nik')->on('penduduk')->cascadeOnUpdate()->cascadeOnDelete();
            $table->float('berat_badan')->nullable();
            $table->float('tinggi_badan')->nullable();
            $table->float('imt')->nullable();
            $table->string('kategori_tbu')->nullable();
            $table->float('z_score_tbu')->nullable();
            $table->string('kategori_bbu')->nullable();
            $table->float('z_score_bbu')->nullable();
            $table->string('kategori_imtu')->nullable();
            $table->float('z_score_imtu')->nullable();
            $table->string('kategori_tb_bb')->nullable();
            $table->float('z_score_tb_bb')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kesehatan_anaks');
    }
};
