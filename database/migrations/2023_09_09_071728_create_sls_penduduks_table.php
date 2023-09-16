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
        Schema::create('sls_penduduks', function (Blueprint $table) {
            $table->id();
            // $table->string('kk_id', 10);
            // $table->string('nik', 16);
            // $table->foreignId('sls_id')->references('sls_id')->on('sls')->cascadeOnUpdate()->cascadeOnDelete();
            // $table->foreign('kk_id')->references('kk_id')->on('kartu_keluarga')->cascadeOnUpdate()->cascadeOnDelete();
            // $table->foreign('nik')->references('nik')->on('penduduks')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sls_penduduks');
    }
};