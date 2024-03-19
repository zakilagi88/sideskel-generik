<?php

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\KelurahanInfo;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('desa_kelurahan', function (Blueprint $table) {
            $table->string('deskel_id', 10)->primary()->index();
            $table->string('deskel_nama');
            $table->string('kec_id', 6);
            $table->foreign('kec_id')->references('kec_id')->on('kecamatan')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });

        $jsonFile = file_get_contents(resource_path('json/desa_kelurahan.json'));

        $data = json_decode($jsonFile, true);

        $kelurahan = [];

        foreach ($data['kelurahan'] as $kecId => $kelurahanData) {
            foreach ($kelurahanData as $kelId => $kelNama) {

                $gabung = $kecId . $kelId;
                $kelurahan[] = [
                    'deskel_id' => $gabung,
                    'deskel_nama' => $kelNama,
                    'kec_id' => $kecId,
                ];
            }
        }

        foreach (array_chunk($kelurahan, 10000) as $chunk) {
            DB::table('desa_kelurahan')->insert($chunk);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('desa_kelurahan');
    }
};
