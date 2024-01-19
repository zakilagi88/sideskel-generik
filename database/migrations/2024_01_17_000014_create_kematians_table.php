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
        Schema::create('kematian', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 16)->nullable();
            $table->foreign('nik')->references('nik')->on('penduduk')->cascadeOnUpdate()->cascadeOnDelete();
            $table->date('tanggal_kematian')->nullable();
            $table->string('tempat_kematian')->nullable();
            $table->string('sebab_kematian')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kematian');
    }
};