<?php

use App\Models\Penduduk;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::create('kepala_wilayah', function (Blueprint $table) {

            $table->id();

            $table->string('kepala_nik', 16)->nullable();

            $table->foreign('kepala_nik')->references('nik')->on('penduduk')->cascadeOnUpdate()->cascadeOnDelete();

            $table->morphs('kepala');

            $table->unique(['kepala_nik', 'kepala_id', 'kepala_type']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kepala_wilayah');
    }
};