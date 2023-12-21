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
        Schema::create('statistik', function (Blueprint $table) {
            $table->id();
            $table->string('judul')->nullable();
            $table->string('slug')->nullable();
            $table->string('heading_grafik')->nullable();
            $table->string('deskripsi_grafik')->nullable();
            $table->string('path_grafik')->nullable();
            $table->boolean('tampilkan_grafik')->default(true);
            $table->string('jenis_grafik')->nullable();
            $table->string('heading_tabel')->nullable();
            $table->string('deskripsi_tabel')->nullable();
            $table->string('path_tabel')->nullable();
            $table->boolean('tampilkan_tabel')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('statistik');
    }
};
