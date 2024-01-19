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
        Schema::create('stuntings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('kategori_stuntings', 'id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('subkategori_id')->constrained('subkategori_stuntings', 'id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('nik', 16);
            $table->string('ibu', 16);
            $table->foreign('nik')->references('nik')->on('penduduk')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('ibu')->references('nik')->on('penduduk')->cascadeOnUpdate()->cascadeOnDelete();
            $table->float('berat_badan');
            $table->float('tinggi_badan');
            $table->float('indeks_massa_tubuh');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stuntings');
    }
};
