<?php

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
        Schema::table('kartu_keluarga', function (Blueprint $table) {
            $table->string('kk_kepala', 16)->after('kk_id')->nullable();
            $table->foreign('kk_kepala')->references('nik')->on('penduduk')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kartu_keluarga', function (Blueprint $table) {
            $table->dropForeign(['kk_kepala']);
            $table->dropColumn('kk_kepala');
        });
    }
};
