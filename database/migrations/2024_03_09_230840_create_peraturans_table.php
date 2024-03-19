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
        Schema::create('peraturans', function (Blueprint $table) {
            $table->id();
            $table->string('per_file');
            $table->string('per_jenis');
            $table->text('per_tentang')->nullable();
            $table->text('per_uraian_singkat')->nullable();
            $table->string('per_no_ditetapkan');
            $table->date('per_tgl_ditetapkan');
            $table->date('per_tgl_kesepakatan')->nullable();
            $table->string('per_no_dilaporkan')->nullable();
            $table->date('per_tgl_dilaporkan')->nullable();
            $table->string('per_no_diundangkan_l')->nullable();
            $table->date('per_tgl_diundangkan_l')->nullable();
            $table->string('per_no_diundangkan_b')->nullable();
            $table->date('per_tgl_diundangkan_b')->nullable();
            $table->text('per_keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peraturans');
    }
};