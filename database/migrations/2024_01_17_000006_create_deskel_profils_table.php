<?php

use App\Facades\Deskel;
use App\Models\{DesaKelurahanProfile, Kab_Kota, KabKota, Kecamatan, Kelurahan, Provinsi};

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
            $table->string('deskel_id', 10)->nullable();
            $table->foreign('deskel_id')->references('deskel_id')->on('desa_kelurahan')
                ->nullable()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('sebutan')->nullable();
            $table->string('struktur')->nullable();
            $table->string('kepala')->nullable();
            $table->string('alamat')->nullable();
            $table->string('thn_bentuk')->nullable();
            $table->foreignId('dasar_hukum_id')->nullable()->constrained('dokumens')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('kodepos')->nullable();
            $table->string('koordinat_lat')->nullable();
            $table->string('koordinat_long')->nullable();
            $table->string('tipologi')->nullable();
            $table->string('klasifikasi')->nullable();
            $table->string('kategori')->nullable();
            $table->json('orbitrasi')->nullable();
            $table->json('luaswilayah')->nullable();
            $table->integer('jmlh_pdd')->nullable();
            $table->integer('jmlh_sert_tanah')->nullable();
            $table->double('tanah_kas')->nullable();
            $table->string('bts_utara')->nullable();
            $table->string('bts_timur')->nullable();
            $table->string('bts_selatan')->nullable();
            $table->string('bts_barat')->nullable();
            $table->string('kantor')->nullable();
            $table->longText('visi')->nullable();
            $table->longText('misi')->nullable();
            $table->longText('sejarah')->nullable();
            $table->string('gambar')->nullable();
            $table->string('logo')->nullable();
            $table->string('telepon')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('instagram')->nullable();
            $table->string('youtube')->nullable();
            $table->boolean('status')->default(false);

            $table->timestamps();
        });

        $dk = new DesaKelurahanProfile([
            'id' => 1,
            'luaswilayah' => [
                [
                    'lahan_sawah' => null,
                    'lahan_ladang' => null,
                    'lahan_perkebunan' => null,
                    'lahan_peternakan' => null,
                    'lahan_hutan' => null,
                    'waduk_danau_situ' => null,
                    'lainnya' => null,
                ],
            ],

            'orbitrasi' => [
                [
                    'pusat_kec' => null,
                    'pusat_pemerintahan' => null,
                    'pusat_kota' => null,
                    'pusat_prov' => null,
                ]
            ],

        ]);
        $dk->save();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deskel_profils');
    }
};
