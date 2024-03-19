<?php

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
        Schema::create('statistiks', function (Blueprint $table) {
            $table->id()->index();
            $table->string('stat_key')->nullable()->index();
            $table->string('stat_heading')->nullable()->index();
            $table->string('stat_subheading')->nullable();
            $table->string('stat_slug')->unique()->nullable();
            $table->string('stat_deskripsi')->nullable();
            $table->string('stat_grafik_jenis')->nullable();
            $table->boolean('stat_tampil')->default(true);
            $table->timestamps();
        });

        $default_tables = config('app_data.default_tables');

        foreach ($default_tables['statistiks'] as $key => $value) {
            Statistik::updateOrCreate(
                ['id' => $key],
                [
                    'stat_key' => $value['stat_key'],
                    'stat_slug' => $value['stat_slug'],
                    'stat_heading' => $value['stat_heading'],
                    'stat_subheading' => $value['stat_subheading'],
                    'stat_deskripsi' => $value['stat_deskripsi'],
                    'stat_grafik_jenis' => $value['stat_grafik_jenis'],
                    'stat_tampil' => $value['stat_tampil'],
                ]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('statistiks');
    }
};
