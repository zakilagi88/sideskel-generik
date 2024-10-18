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
        Schema::create('aparaturs', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('slug')->unique();
            $table->string('niap')->unique()->nullable();
            $table->string('nip')->unique()->nullable();
            $table->string('foto')->nullable();
            $table->foreignId('jabatan_id')->nullable()->constrained('jabatans')->nullOnDelete();
            $table->string('pangkat_golongan')->nullable();
            $table->string('jenis_kelamin');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('agama');
            $table->string('pendidikan');
            $table->string('no_kep_pengangkatan')->nullable();
            $table->date('tgl_kep_pengangkatan')->nullable();
            $table->string('no_kep_pemberhentian')->nullable();
            $table->date('tgl_kep_pemberhentian')->nullable();
            $table->string('status_pegawai')->default('Aktif');
            $table->string('masa_jabatan')->nullable();
            $table->string('keterangan')->nullable();
            $table->integer('sort')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aparaturs');
    }
};
