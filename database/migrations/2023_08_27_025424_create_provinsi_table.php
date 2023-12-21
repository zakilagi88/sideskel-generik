<?php

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
        Schema::create('provinsi', function (Blueprint $table) {
            $table->string('prov_id', 2)->primary();
            $table->string('prov_nama');
            $table->timestamps();
        });

        $jsonFile = file_get_contents(resource_path('json/provinsi.json'));

        $data = json_decode($jsonFile, true);

        foreach ($data['provinsi'] as $provId => $provNama) {
            DB::table('provinsi')->insert([
                'prov_id' => $provId,
                'prov_nama' => $provNama,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provinsi');
    }
};
