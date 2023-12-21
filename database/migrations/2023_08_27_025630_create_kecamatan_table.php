<?php

use App\Models\Kab_Kota;
use App\Models\KabKota;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

use function Safe\file_get_contents;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kecamatan', function (Blueprint $table) {
            $table->string('kec_id', 6)->primary();
            $table->string('kec_nama');
            $table->foreignIdFor(KabKota::class, 'kabkota_id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });

        $jsonFile = file_get_contents(resource_path('json/kecamatan.json'));

        $data = json_decode($jsonFile, true);

        foreach ($data['kecamatan'] as $kabkotaId => $kecamatanData) {
            foreach ($kecamatanData as $kecId => $kecNama) {

                $gabung = $kabkotaId . $kecId;

                DB::table('kecamatan')->insert([
                    'kec_id' => $gabung,
                    'kec_nama' => $kecNama,
                    'kabkota_id' => $kabkotaId,
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kecamatan');
    }
};
