<?php

use App\Models\{Kab_Kota, KabKota, Kecamatan, Provinsi};

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

            $table->foreignIdFor(Provinsi::class, 'prov_id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(KabKota::class, 'kab_id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(Kecamatan::class, 'kec_id')->cascadeOnUpdate()->cascadeOnDelete();

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
