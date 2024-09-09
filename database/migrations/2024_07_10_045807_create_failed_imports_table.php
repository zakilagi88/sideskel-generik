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
        Schema::create('failed_imports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('import_id')->constrained('imports', 'id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('attribute');
            $table->unsignedInteger('row');
            $table->json('values');
            $table->json('errors');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('failed_imports');
    }
};