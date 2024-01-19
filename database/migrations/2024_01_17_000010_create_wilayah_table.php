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
            $table->string('wilayah_nama', 100)->nullable();
            $table->string('wilayah_kodepos', 5)->nullable();
            $table->string('prov_id', 2)->nullable();
            $table->string('kabkota_id', 4)->nullable();
            $table->string('kec_id', 6)->nullable();
            $table->string('kel_id', 10)->nullable();
            $table->foreign('prov_id')->references('prov_id')->on('provinsi')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('kabkota_id')->references('kabkota_id')->on('kab_kota')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('kec_id')->references('kec_id')->on('kecamatan')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('kel_id')->references('kel_id')->on('kelurahan')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('dusun_id')->nullable()->constrained('dusun', 'dusun_id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('rw_id')->nullable()->constrained('rukun_warga', 'rw_id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('rt_id')->nullable()->constrained('rukun_tetangga', 'rt_id')->cascadeOnUpdate()->cascadeOnDelete();

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