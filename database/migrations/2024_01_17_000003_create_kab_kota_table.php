<?php

use App\Models\Provinsi;
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
        Schema::create('kab_kota', function (Blueprint $table) {
            $table->string('kabkota_id', 4)->primary();
            $table->string('prov_id', 2);
            $table->foreign('prov_id')->references('prov_id')->on('provinsi')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('kabkota_nama');
            $table->timestamps();
        });

        $jsonFile = file_get_contents(resource_path('json/kab_kota.json'));

        $data = json_decode($jsonFile, true);

        $kabkota = [];

        foreach ($data['kabupaten'] as $provId => $kabupatenData) {
            foreach ($kabupatenData as $kabkotaId => $kabkotaNama) {

                $gabung = $provId . $kabkotaId;

                $kabkota[] = [
                    'kabkota_id' => $gabung,
                    'kabkota_nama' => $kabkotaNama,
                    'prov_id' => $provId,
                ];
            }
        }

        foreach (array_chunk($kabkota, 200) as $chunk) {
            DB::table('kab_kota')->insert($chunk);
        }
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kab_kota');
    }
};