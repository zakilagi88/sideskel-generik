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
        Schema::create('rukun_warga', function (Blueprint $table) {
            $table->id('rw_id');
            $table->string('rw_nama');
            // $table->unsignedBigInteger('kelurahan_id');
            // $table->foreign('kelurahan_id')->references('kelurahan_id')->on('kelurahan')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rukun_warga');
    }
};
