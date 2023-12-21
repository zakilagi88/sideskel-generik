<?php

use App\Models\KartuKeluarga;
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
        Schema::create('anggota_keluarga', function (Blueprint $table) {
            $table->id('anggota_id');
            $table->foreignIdFor(Penduduk::class, 'nik')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(KartuKeluarga::class, 'kk_id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('hubungan')->nullable();



            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggota_keluarga');
    }
};
