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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable()->after('password')->constrained('roles')->nullOnDelete();
            $table->string('phone')->nullable()->after('email');
            $table->string('kecamatan_id')->nullable()->after('role_id');
            $table->string('kelurahan_id')->nullable()->after('kecamatan_id');
            $table->string('organization')->nullable()->after('kelurahan_id');
            $table->boolean('is_active')->default(true)->after('organization');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn(['role_id', 'phone', 'kecamatan_id', 'kelurahan_id', 'organization', 'is_active']);
        });
    }
};
