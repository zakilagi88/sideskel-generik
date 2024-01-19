<?php

namespace App\Models;

use App\Enum\Penduduk\Agama;
use App\Enum\Penduduk\JenisKelamin;
use App\Enum\Penduduk\Pekerjaan;
use App\Enum\Penduduk\Pendidikan;
use App\Enum\Penduduk\Pernikahan;
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
            $table->string('nik', 16)->unique();
            $table->string('kk_id', 16)->nullable();
            $table->foreign('kk_id')->references('kk_id')->on('kartu_keluarga')->nullable()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('wilayah_id')->nullable()->constrained('wilayah', 'wilayah_id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('foto')->nullable();
            $table->string('nama_lengkap');
            $table->string('jenis_kelamin');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('agama');
            $table->string('pendidikan');
            $table->string('pekerjaan');
            $table->string('status_perkawinan');
            $table->string('tgl_perkawinan')->nullable();
            $table->string('tgl_perceraian')->nullable();
            $table->string('kewarganegaraan')->default('WNI');
            $table->string('ayah')->nullable();
            $table->string('ibu')->nullable();
            $table->string('golongan_darah')->nullable();
            $table->string('etnis_suku')->nullable();
            $table->string('cacat')->nullable();
            $table->string('penyakit')->nullable();
            $table->string('akseptor_kb')->nullable();
            $table->string('status');
            $table->string('status_pengajuan')->default('BELUM DIVERIFIKASI');
            $table->string('status_tempat_tinggal')->nullable();
            $table->string('alamat');
            $table->boolean('alamatKK')->nullable()->default(false);
            $table->string('telepon')->nullable();
            $table->string('email')->nullable();
            $table->string('status_hubungan')->nullable();
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
