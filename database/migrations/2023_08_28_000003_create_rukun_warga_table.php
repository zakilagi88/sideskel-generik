<?php

use App\Models\Kelurahan;
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
        Schema::create('rukun_warga', function (Blueprint $table) {
            $table->id('rw_id');
            $table->string('rw_nama');
            $table->foreignId('dusun_id')->nullable()->constrained('dusun', 'dusun_id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(Kelurahan::class, 'kel_id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rukun_warga');
    }
};
