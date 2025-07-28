<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            // Hapus kolom yang tidak diperlukan lagi
            $table->dropColumn(['submitter_name', 'submitter_email']);
        });
    }

    public function down(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            // Fungsi down untuk mengembalikan kolom jika migrasi dibatalkan
            $table->string('submitter_name')->nullable();
            $table->string('submitter_email')->nullable();
        });
    }
};