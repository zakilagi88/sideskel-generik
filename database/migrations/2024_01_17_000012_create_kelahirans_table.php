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
        Schema::create('kelahirans', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 16);
            $table->foreign('nik')->references('nik')->on('penduduk')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('anak_ke');
            $table->string('tempat_lahir');
            $table->string('jenis_lahir');
            $table->string('penolong_lahir');
            $table->string('berat_lahir');
            $table->string('panjang_lahir');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelahirans');
    }
};
