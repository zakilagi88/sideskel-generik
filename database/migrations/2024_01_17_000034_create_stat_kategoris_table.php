<?php

use App\Models\StatKategori;
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
        Schema::create('stat_kategoris', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->timestamps();
        });

        $default_tables = config('app_data.default_tables');

        foreach ($default_tables['stat_kategoris'] as $key => $value) {
            StatKategori::updateOrCreate(
                ['id' => $key],
                [
                    'nama' => $value['nama'],
                ]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stat_kategoris');
    }
};
