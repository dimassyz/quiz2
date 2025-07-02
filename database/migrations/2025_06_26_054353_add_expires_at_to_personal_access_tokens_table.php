<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('personal_access_tokens', function (Blueprint $table) {
            // Tambahkan kolom 'expires_at' setelah kolom 'abilities'
            $table->timestamp('expires_at')->nullable()->after('abilities');
        });
    }

    public function down(): void
    {
        Schema::table('personal_access_tokens', function (Blueprint $table) {
            // Jika migrasi dibatalkan, hapus kolomnya
            $table->dropColumn('expires_at');
        });
    }
};
