<?php

use App\Models\StatSDM;
use App\Models\StatSDMistik;
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
        Schema::create('stat_sdms', function (Blueprint $table) {
            $table->id()->index();
            $table->foreignId('stat_kategori_id')->constrained('stat_kategoris', 'id')->cascadeOnDelete();
            $table->string('key')->nullable();
            $table->string('nama')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->string('deskripsi')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

       
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stat_sdms');
    }
};
