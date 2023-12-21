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
        Schema::create('kartu_keluarga', function (Blueprint $table) {
            $table->string('kk_id', 16)->primary();
            $table->foreignId('wilayah_id')->constrained('wilayah', 'wilayah_id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(Penduduk::class, 'kk_kepala')->nullable()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('kk_alamat');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kartu_keluarga');
    }
};
