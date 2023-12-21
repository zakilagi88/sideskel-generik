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

            $table->foreignId('wilayah_id')->nullable()->constrained('wilayah', 'wilayah_id')->cascadeOnUpdate()->cascadeOnDelete();

            $table->string('foto')->nullable();
            // nama varchar
            $table->string('nama_lengkap');
            // jenis_kelamin enum
            $table->string('jenis_kelamin');
            // tempat_lahir varchar 
            $table->string('tempat_lahir');
            // tanggal_lahir date
            $table->date('tanggal_lahir');
            // agama enum
            $table->string('agama');
            // pendidikan enum
            $table->string('pendidikan');
            // status_pernikahan enum
            $table->string('pekerjaan');

            $table->string('status_perkawinan');
            // pekerjaan varchar
            $table->string('kewarganegaraan')->default('WNI');

            $table->string('ayah')->nullable();

            $table->string('ibu')->nullable();

            $table->string('golongan_darah')->nullable();

            $table->string('status');

            $table->string('status_pengajuan')->default('BELUM DIVERIFIKASI');
            
            $table->string('status_tempat_tinggal')->nullable();

            $table->string('etnis_suku')->nullable();

            $table->string('alamat');

            $table->boolean('alamatKK')->nullable()->default(false);

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