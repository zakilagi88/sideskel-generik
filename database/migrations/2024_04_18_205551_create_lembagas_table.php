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
        Schema::create('lembagas', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->string('slug', 100);
            $table->text('deskripsi')->nullable();
            $table->string('singkatan', 20);
            $table->string('logo_url')->nullable();
            $table->json('kategori_jabatan');
            $table->string('alamat', 100);
            $table->foreignId('dokumen_id')->nullable()->constrained('dokumens')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lembagas');
    }
};