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
        Schema::create('tambahans', function (Blueprint $table) {
            $table->id('tambahan_id');
            $table->string('tambahan_nama');
            $table->string('tambahan_sasaran');
            $table->longText('tambahan_keterangan');
            $table->json('kategori')->nullable();
            $table->date('tambahan_tgl_mulai');
            $table->date('tambahan_tgl_selesai');
            $table->boolean('tambahan_status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tambahans');
    }
};
