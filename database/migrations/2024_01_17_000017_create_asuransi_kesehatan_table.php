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
        Schema::create('asuransi_kesehatans', function (Blueprint $table) {
            $table->id('as_kes_id');
            $table->string('as_kes_nama');
            $table->string('as_kes_nomor');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asuransi_kesehatans');
    }
};
