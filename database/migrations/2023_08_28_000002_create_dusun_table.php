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
        Schema::create('dusun', function (Blueprint $table) {
            $table->id('dusun_id');
            $table->string('dusun_nama');
            $table->foreignIdFor(Kelurahan::class, 'kel_id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dusun');
    }
};
