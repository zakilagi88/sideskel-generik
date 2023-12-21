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
            $table->string('kabkota_id', 4);
            $table->string('kabkota_nama');
            $table->foreignIdFor(Provinsi::class, 'prov_id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });

        $jsonFile = file_get_contents(resource_path('json/kab_kota.json'));

        $data = json_decode($jsonFile, true);

        foreach ($data['kabupaten'] as $provId => $kabupatenData) {
            foreach ($kabupatenData as $kabkotaId => $kabkotaNama) {

                $gabung = $provId . $kabkotaId;


                DB::table('kab_kota')->insert([
                    'kabkota_id' => $gabung,
                    'kabkota_nama' => $kabkotaNama,
                    'prov_id' => $provId,
                ]);
            }
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
