<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('api_clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');                    // "Dinas Pendidikan Kota Surabaya"
            $table->string('organization');            // "Disdik Surabaya"
            $table->string('api_key', 64)->unique();   // UUID-based key
            $table->string('api_secret', 128);         // Hashed secret
            $table->json('allowed_endpoints');         // ["data-paud", "statistics"]
            $table->integer('rate_limit')->default(100); // Requests per minute
            $table->string('contact_email');
            $table->string('contact_person');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_clients');
    }
};
