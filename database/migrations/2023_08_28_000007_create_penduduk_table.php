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
        Schema::create('penduduks', function (Blueprint $table) {
            $table->id('penduduk_id', 16);

            $table->unsignedBigInteger('kk_id')->nullable();

            // nik varchar
            $table->string('nik')->unique();
            // nama varchar
            $table->string('nama_lengkap');
            // jenis_kelamin enum
            $table->enum('jenis_kelamin', ['L', 'P']);
            // tempat_lahir varchar 
            $table->string('tempat_lahir');
            // tanggal_lahir date
            $table->date('tanggal_lahir');
            // agama enum
            $table->enum('agama', ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Budha', 'Konghucu']);
            // pendidikan enum
            $table->enum('pendidikan', ['Tidak Sekolah', 'SD', 'SMP', 'SMA', 'D1', 'D2', 'D3', 'D4', 'S1', 'S2', 'S3']);
            // golongan_darah enum
            $table->enum('golongan_darah', ['A', 'B', 'AB', 'O']);
            // status_pernikahan enum
            $table->enum('status_pernikahan', ['Kawin', 'Belum Kawin', 'Cerai Hidup', 'Cerai Mati']);
            // pekerjaan varchar
            $table->string('pekerjaan');
            // status_hubungan_dalam_keluarga enum
            $table->enum('status_hubungan_dalam_keluarga', ['Kepala Keluarga', 'Istri', 'Anak', 'Menantu', 'Cucu', 'Orang Tua', 'Mertua', 'Famili Lain', 'Pembantu', 'Lainnya']);
            // alamat varchar
            $table->text('alamat');


            $table->foreign('kk_id')->references('kk_id')->on('kartu_keluarga')->cascadeOnUpdate()->cascadeOnDelete();


            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penduduks');
    }
};