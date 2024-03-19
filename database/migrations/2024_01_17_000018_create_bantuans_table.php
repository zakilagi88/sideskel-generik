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
        Schema::create('bantuans', function (Blueprint $table) {
            $table->id('bantuan_id');
            $table->string('bantuan_program');
            $table->string('bantuan_sasaran');
            $table->longText('bantuan_keterangan');
            $table->date('bantuan_tgl_mulai');
            $table->date('bantuan_tgl_selesai');
            $table->boolean('bantuan_status')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bantuans');
    }
};