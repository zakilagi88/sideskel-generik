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
        Schema::create('imports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('imported_by')->constrained('users', 'id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('file_name');
            $table->string('status');
            $table->integer('process_rows');
            $table->integer('success_rows');
            $table->integer('related_rows');    
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imports');
    }
};