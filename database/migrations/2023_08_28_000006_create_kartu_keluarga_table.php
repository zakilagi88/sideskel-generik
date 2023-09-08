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
        Schema::create('kartu_keluarga', function (Blueprint $table) {
            $table->id('kk_id');
            $table->string('kk_no')->unique();
            $table->string('kk_alamat');
            // $table->unsignedBigInteger('sls_id');
            $table->unsignedBigInteger('rt_id');
            $table->unsignedBigInteger('rw_id');

            // $table->foreign('sls_id')->references('sls_id')->on('sls')->cascadeOnUpdate()->cascadeOnDelete()->nullable();
            $table->foreign('rt_id')->references('rt_id')->on('rukun_tetangga')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('rw_id')->references('rw_id')->on('rukun_warga')->cascadeOnUpdate()->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kartu_keluarga');
    }
};
