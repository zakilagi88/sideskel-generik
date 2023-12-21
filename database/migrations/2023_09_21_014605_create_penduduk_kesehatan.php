<?php

use App\Models\Penduduk;
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
        Schema::create('penduduk_kesehatan', function (Blueprint $table) {
            $table->id('pddkes_id');
            $table->foreignIdFor(Penduduk::class, 'nik')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('as_kes_id')->nullable()->constrained('asuransi_kesehatan', 'as_kes_id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('kes_id')->nullable()->constrained('kesehatan', 'kes_id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
