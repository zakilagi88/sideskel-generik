<?php

namespace App\Models;

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
        Schema::create('penduduk', function (Blueprint $table) {
            $table->string('nik', 16)->unique()->primary();
            $table->string('kk_id', 16)->nullable()->index();
            $table->foreign('kk_id')->references('kk_id')->on('kartu_keluarga')->cascadeOnUpdate()->cascadeOnDelete();
            $table->boolean('status_identitas')->default(false);
            $table->string('jenis_identitas')->nullable();
            $table->string('status_rekam_identitas')->nullable();
            $table->string('foto')->nullable();
            $table->string('nama_lengkap');
            $table->string('jenis_kelamin');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->integer('umur');
            $table->string('agama');
            $table->string('pendidikan');
            $table->string('pekerjaan');
            $table->string('status_perkawinan');
            $table->date('tgl_perkawinan')->nullable();
            $table->date('tgl_perceraian')->nullable();
            $table->string('kewarganegaraan')->default('WNI');
            $table->string('nama_ayah')->nullable();
            $table->string('nama_ibu')->nullable();
            $table->string('nik_ayah', 16)->nullable();
            $table->string('nik_ibu', 16)->nullable();
            $table->string('golongan_darah')->nullable();
            $table->string('etnis_suku')->nullable();
            $table->string('cacat')->nullable();
            $table->string('penyakit')->nullable();
            $table->string('akseptor_kb')->nullable();
            $table->string('status_hubungan')->nullable();
            $table->string('status_penduduk')->default('Tetap');
            $table->string('status_dasar')->default('HIDUP');
            $table->string('status_pengajuan')->default('BELUM DIVERIFIKASI');
            $table->string('status_tempat_tinggal')->nullable();
            $table->string('alamat_sekarang');
            $table->string('alamat_sebelumnya')->nullable();
            $table->boolean('is_nik_sementara')->default(false);
            $table->string('telepon')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penduduk');
    }
};