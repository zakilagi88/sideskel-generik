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
            $table->string('niap')->unique()->nullable();
            $table->string('nip')->unique()->nullable();
            $table->string('foto')->nullable();
            $table->string('jabatan');
            $table->string('pangkat');
            $table->string('golongan');
            $table->string('jenis_kelamin');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('agama');
            $table->string('pendidikan');
            $table->string('no_kep_pengangkatan');
            $table->date('tgl_kep_pengangkatan');
            $table->string('no_kep_pemberhentian');
            $table->date('tgl_kep_pemberhentian');
            $table->string('status_pegawai')->default('Aktif');
            $table->string('keterangan')->nullable();
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