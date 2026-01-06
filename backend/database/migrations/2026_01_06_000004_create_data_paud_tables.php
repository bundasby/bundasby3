<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('data_paud_sekolah', function (Blueprint $table) {
            $table->id();
            $table->string('npsn')->unique();
            $table->string('nama');
            $table->enum('jenis', ['TK', 'KB', 'TPA', 'SPS', 'PAUD']);
            $table->text('alamat')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kelurahan')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->char('akreditasi', 1)->nullable();
            $table->timestamps();
        });

        Schema::create('data_paud_akreditasi', function (Blueprint $table) {
            $table->id();
            $table->string('npsn');
            $table->string('nama');
            $table->char('peringkat', 1);
            $table->integer('nilai')->nullable();
            $table->date('tanggal')->nullable();
            $table->date('berlaku')->nullable();
            $table->timestamps();
        });

        Schema::create('data_paud_ijin', function (Blueprint $table) {
            $table->id();
            $table->string('npsn');
            $table->string('nama');
            $table->string('no_sk');
            $table->date('tanggal_sk')->nullable();
            $table->date('berlaku')->nullable();
            $table->enum('status', ['active', 'expired'])->default('active');
            $table->timestamps();
        });

        Schema::create('data_paud_profiling', function (Blueprint $table) {
            $table->id();
            $table->string('npsn');
            $table->string('nama');
            $table->integer('siswa')->default(0);
            $table->integer('guru')->default(0);
            $table->integer('ruang_kelas')->default(0);
            $table->year('tahun');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_paud_profiling');
        Schema::dropIfExists('data_paud_ijin');
        Schema::dropIfExists('data_paud_akreditasi');
        Schema::dropIfExists('data_paud_sekolah');
    }
};
