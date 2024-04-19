<?php

use App\Models\Stat;
use App\Models\Statistik;
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
        Schema::create('stats', function (Blueprint $table) {
            $table->id()->index();
            $table->foreignId('stat_kategori_id')->constrained('stat_kategoris', 'id')->cascadeOnDelete();
            $table->string('key')->nullable();
            $table->string('nama')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->string('deskripsi')->nullable();
            $table->string('grafik_path')->nullable();
            $table->string('tabel_path')->nullable();
            $table->boolean('tampil')->default(true);
            $table->timestamps();
        });

        $default_tables = config('app_data.default_tables');

        foreach ($default_tables['stats'] as $key => $value) {
            Stat::updateOrCreate(
                ['id' => $key],
                [
                    'stat_kategori_id' => $value['stat_kategori_id'],
                    'key' => $value['key'],
                    'nama' => $value['nama'],
                    'slug' => $value['slug'],
                    'deskripsi' => $value['deskripsi'],
                    'tampil' => $value['tampil'],
                ]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stats');
    }
};
