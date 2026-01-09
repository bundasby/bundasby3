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
        Schema::create('external_api_configs', function (Blueprint $table) {
            $table->id();
            $table->string('name');                    // Nama dinas: "Dinas Pendidikan"
            $table->string('code')->unique();          // Kode: "disdik"
            $table->string('base_url');                // URL API: "https://api.disdik.surabaya.go.id"
            $table->json('headers')->nullable();       // Headers termasuk API key
            $table->string('auth_type')->default('none'); // none, api_key, bearer, basic
            $table->text('api_key')->nullable();       // Encrypted API key
            $table->text('api_secret')->nullable();    // Encrypted API secret
            $table->integer('timeout')->default(30);   // Timeout dalam detik
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_sync_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('external_api_configs');
    }
};
