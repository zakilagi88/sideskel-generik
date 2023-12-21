<?php

use App\Models\KabKota;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Provinsi;
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
        Schema::create('wilayah', function (Blueprint $table) {
            $table->id('wilayah_id');
            $table->foreignIdFor(Kelurahan::class, 'kel_id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(Kecamatan::class, 'kec_id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(KabKota::class, 'kabkota_id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(Provinsi::class, 'prov_id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('dusun_id')->nullable()->constrained('dusun', 'dusun_id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('rw_id')->nullable()->constrained('rukun_warga', 'rw_id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('rt_id')->nullable()->constrained('rukun_tetangga', 'rt_id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('wilayah_nama', 100)->nullable();
            $table->string('wilayah_kodepos', 5)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wilayah');
    }
};