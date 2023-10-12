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
        Schema::create('kab_kotas', function (Blueprint $table) {
            $table->string('kabkota_id')->primary();
            $table->string('prov_id');
            $table->string('kabkota_nama');
            $table->foreign('prov_id')->references('prov_id')->on('provinsis')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kab_kotas');
    }
};
