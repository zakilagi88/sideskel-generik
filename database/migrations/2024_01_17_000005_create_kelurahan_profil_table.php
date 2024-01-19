<?php

use App\Models\{Kab_Kota, KabKota, Kecamatan, Kelurahan, Provinsi};

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
        Schema::create('kelurahan_profil', function (Blueprint $table) {
            $table->id('kel_profil_id');

            $table->string('prov_id', 2);
            $table->string('kabkota_id', 4);
            $table->string('kec_id', 6);
            $table->foreign('prov_id')->references('prov_id')->on('provinsi')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('kabkota_id')->references('kabkota_id')->on('kab_kota')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('kec_id')->references('kec_id')->on('kecamatan')->cascadeOnUpdate()->cascadeOnDelete();

            $table->string('kel_luas_wilayah');
            $table->string('kel_batas_wilayah');
            $table->string('kel_visi_misi');
            $table->string('kel_sejarah');
            $table->string('kel_logo');
            $table->string('kel_telepon');
            $table->string('kel_email');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelurahan_profil');
    }
};