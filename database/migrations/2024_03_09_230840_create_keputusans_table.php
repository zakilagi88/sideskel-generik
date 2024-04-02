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
        Schema::create('keputusans', function (Blueprint $table) {
            $table->id();
            $table->string('no');
            $table->date('tgl');
            $table->text('tentang');
            $table->text('uraian_singkat')->nullable();
            $table->string('no_dilaporkan')->nullable();
            $table->date('tgl_dilaporkan')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keputusans');
    }
};
