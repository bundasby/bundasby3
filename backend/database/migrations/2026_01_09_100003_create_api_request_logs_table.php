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
        Schema::create('api_request_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('api_client_id')->constrained()->onDelete('cascade');
            $table->string('endpoint');
            $table->string('method', 10);
            $table->string('ip_address', 45);
            $table->integer('response_code');
            $table->integer('response_time_ms');
            $table->json('request_params')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamp('created_at');

            $table->index(['api_client_id', 'created_at']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_request_logs');
    }
};
