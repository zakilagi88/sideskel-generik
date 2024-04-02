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
        Schema::create('apbdes', function (Blueprint $table) {
            $table->id('apbdes_id');
            $table->year('tahun');
            $table->string('komponen');
            $table->unsignedBigInteger('komponen_id')->nullable();
            $table->foreign('komponen_id')->references('apbdes_id')->on('apbdes')->cascadeOnUpdate()->cascadeOnDelete();
            $table->decimal('nilai', 15, 2);
            $table->decimal('realisasi', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apbdes');
    }
};