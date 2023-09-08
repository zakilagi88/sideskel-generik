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
        Schema::create('sls', function (Blueprint $table) {
            $table->id('sls_id');
            $table->unsignedBigInteger('rw_id');
            $table->unsignedBigInteger('rt_id');
            $table->foreign('rw_id')->references('rw_id')->on('rukun_warga')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('rt_id')->references('rt_id')->on('rukun_tetangga')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('sls_kode', 4);
            $table->string('sls_nama', 100);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sls');
    }
};
