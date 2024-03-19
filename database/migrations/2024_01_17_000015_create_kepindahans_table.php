<?php

use App\Models\Penduduk;
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
        Schema::create('kepindahans', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 16);
            $table->foreign('nik')->references('nik')->on('penduduk')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('tujuan_pindah')->nullable();
            $table->string('alamat_pindah')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kepindahans');
    }
};
