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
            $table->string('deskel_id', 10)->nullable();
            $table->foreign('deskel_id')->references('deskel_id')->on('desa_kelurahan')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('tingkatan', 10)->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreign('parent_id')->references('wilayah_id')->on('wilayah')->cascadeOnUpdate()->cascadeOnDelete();

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