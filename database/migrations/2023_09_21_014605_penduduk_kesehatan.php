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
        Schema::create('penduduk_kesehatan', function (Blueprint $table) {
            $table->id('pddkes_id');
            $table->string('nik', 16);
            $table->unsignedBigInteger('kesehatan_id');

            $table->foreign('nik')->references('nik')->on('penduduks')->onDelete('cascade');
            $table->foreign('kesehatan_id')->references('kesehatan_id')->on('kesehatans')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
