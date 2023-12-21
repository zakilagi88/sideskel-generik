<?php

use App\Models\Penduduk;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::create('kepala_entitas', function (Blueprint $table) {

            $table->id('kepala_id');

            $table->foreignIdFor(Penduduk::class, 'kepala_nik')->cascadeOnUpdate()->cascadeOnDelete();

            $table->string('kepala_type');

            $table->morphs('entitas');

            $table->unique(['kepala_id', 'entitas_id', 'entitas_type']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kepala_entitas');
    }
};
