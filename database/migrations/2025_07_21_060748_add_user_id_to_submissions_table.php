<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            // Tambahkan kolom user_id setelah kolom 'id'
            // Pastikan tipe datanya sama dengan tipe kolom 'id' di tabel users Anda
            $table->unsignedBigInteger('user_id')->after('id')->nullable(); 

            // Opsional: Menambahkan foreign key constraint untuk integritas data
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            // $table->dropForeign(['user_id']); // Aktifkan jika Anda menggunakan foreign key
            $table->dropColumn('user_id');
        });
    }
};