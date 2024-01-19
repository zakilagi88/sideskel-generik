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
        Schema::create('subkategori_stuntings', function (Blueprint $table) {
            $table->id();
            $table->string('subkategori_nama');
            $table->float('subkategori_batas_bawah');
            $table->float('subkategori_batas_atas');
            $table->foreignId('kategori_id')->constrained('kategori_stuntings', 'id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subkategori_stuntings');
    }
};
