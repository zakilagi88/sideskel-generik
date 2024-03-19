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
        Schema::create('tambahanables', function (Blueprint $table) {
            $table->foreignId('tambahan_id')->constrained('tambahans', 'tambahan_id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('tambahanable_id');
            $table->string('tambahanable_type');
            $table->unique(['tambahan_id', 'tambahanable_id', 'tambahanable_type'], 'tambahanables_unique_constraint');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tambahanables');
    }
};
