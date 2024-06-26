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
        Schema::table('users', function (Blueprint $table) {
            $table->string('nik', 16)->after('id')->nullable();
            $table->foreign('nik')->references('nik')->on('penduduk')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('wilayah_id')->after('nik')->nullable()->constrained('wilayah', 'wilayah_id')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['nik']);
            $table->dropColumn('nik');
        });
    }
};
