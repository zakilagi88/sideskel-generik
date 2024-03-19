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
            $table->foreignId('kategori_id')->nullable()->constrained('kategori_stuntings', 'id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('subkategori_id')->nullable()->constrained('subkategori_stuntings', 'id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('anak_id', 16)->nullable();
            $table->string('ibu_id', 16)->nullable();
            $table->foreign('anak_id')->references('nik')->on('penduduk')->cascadeOnUpdate()->cascadeOnDelete();
            $table->float('berat_badan');
            $table->float('tinggi_badan');
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
