<?php

use App\Models\SaranaPrasarana;
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
        Schema::create('sarana_prasaranas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deskel_profil_id')->constrained('deskel_profils')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('jenis');
            $table->json('data');
            $table->timestamps();
        });

        $default_tables = config('app_data.default_tables');

        foreach ($default_tables['sarana_prasarana'] as $jenis => $value) {
            SaranaPrasarana::updateOrCreate(
                ['jenis' => $jenis],
                [
                    'deskel_profil_id' => 1,
                    'jenis' => $jenis,
                    'data' => array_map(function ($item) {
                        return [
                            'nama' => $item[0],
                            'jumlah' => $item[1],
                            'satuan' => $item[2],
                        ];
                    }, $value),
                ]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sarana_prasaranas');
    }
};