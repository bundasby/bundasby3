<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bunda_paud', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('position')->default('Bunda PAUD Kecamatan');
            $table->string('area'); // nama kecamatan/kelurahan
            $table->enum('type', ['kecamatan', 'kelurahan'])->default('kecamatan');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('photo_url')->nullable();
            $table->unsignedBigInteger('kecamatan_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bunda_paud');
    }
};
