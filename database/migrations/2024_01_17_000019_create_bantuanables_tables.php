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
        Schema::create('bantuanables', function (Blueprint $table) {
            $table->foreignId('bantuan_id')->constrained('bantuans', 'bantuan_id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('bantuanable_id');
            $table->string('bantuanable_type');
            $table->unique(['bantuan_id', 'bantuanable_id', 'bantuanable_type']);

            $table->timestamps();

            // ...
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bantuanables');
    }
};