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
        Schema::create('penduduks', function (Blueprint $table) {
            $table->string('nik', 16)->unique();
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
            $table->string('status_pernikahan');
            // pekerjaan varchar
            $table->string('pekerjaan');

            $table->string('status');

            $table->string('alamat');

            $table->boolean('alamatKK')->nullable()->default(false);

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
