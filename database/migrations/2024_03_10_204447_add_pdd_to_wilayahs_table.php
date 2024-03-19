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
        Schema::table('wilayah', function (Blueprint $table) {
            $table->string('wilayah_kepala', 16)->after('wilayah_nama')->nullable();
            $table->foreign('wilayah_kepala')->references('nik')->on('penduduk')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wilayah', function (Blueprint $table) {
            $table->dropForeign(['wilayah_kepala']);
            $table->dropColumn('wilayah_kepala');
        });
    }
};
