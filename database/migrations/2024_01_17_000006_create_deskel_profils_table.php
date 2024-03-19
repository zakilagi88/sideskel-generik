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
        Schema::create('deskel_profils', function (Blueprint $table) {
            $table->id();
            $table->string('deskel_id', 10);
            $table->foreign('deskel_id')->references('deskel_id')->on('desa_kelurahan')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('deskel_sebutan')->nullable();
            $table->string('deskel_tipe')->nullable();
            $table->string('deskel_kepala')->nullable();
            $table->string('deskel_alamat')->nullable();
            $table->string('deskel_thn_pembentukan')->nullable();
            $table->string('deskel_dasar_hukum_pembentukan')->nullable();
            $table->string('deskel_kodepos')->nullable();
            $table->double('deskel_luaswilayah')->nullable();
            $table->integer('deskel_jumlahpenduduk')->nullable();
            $table->string('deskel_batas_utara')->nullable();
            $table->string('deskel_batas_timur')->nullable();
            $table->string('deskel_batas_selatan')->nullable();
            $table->string('deskel_batas_barat')->nullable();
            $table->longText('deskel_visi')->nullable();
            $table->longText('deskel_misi')->nullable();
            $table->longText('deskel_sejarah')->nullable();
            $table->string('deskel_gambar')->nullable();
            $table->string('deskel_logo')->nullable();
            $table->string('deskel_telepon')->nullable();
            $table->string('deskel_email')->nullable();
            $table->boolean('deskel_status')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deskel_profils');
    }
};
