<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('mitra_paud', function (Blueprint $table) {
            $table->id();
            $table->string('nama_responden');
            $table->string('jabatan_responden')->nullable();
            $table->string('telepon_responden');
            $table->string('nama_organisasi');
            $table->string('kategori_organisasi')->nullable();
            $table->text('alamat')->nullable();
            $table->string('telepon')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('nama_pimpinan')->nullable();
            $table->text('tujuan_organisasi')->nullable();
            $table->string('logo_url')->nullable();
            $table->json('jenis_kerjasama')->nullable();
            $table->text('catatan_kerjasama')->nullable();
            $table->string('dokumen_komitmen_url')->nullable();
            $table->text('link_pendukung')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mitra_paud');
    }
};
