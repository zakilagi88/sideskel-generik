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
            $table->string('jenis');
            $table->string('no_ditetapkan')->nullable();
            $table->date('tgl_ditetapkan')->nullable();
            $table->text('tentang')->nullable();
            $table->text('uraian_singkat')->nullable();
            $table->date('tgl_kesepakatan')->nullable();
            $table->string('no_dilaporkan')->nullable();
            $table->date('tgl_dilaporkan')->nullable();
            $table->string('no_diundangkan_l')->nullable();
            $table->date('tgl_diundangkan_l')->nullable();
            $table->string('no_diundangkan_b')->nullable();
            $table->date('tgl_diundangkan_b')->nullable();
            $table->text('keterangan')->nullable();
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
