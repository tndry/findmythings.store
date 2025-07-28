<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->string('submitter_name');
            $table->string('submitter_email');
            $table->string('submitter_whatsapp');
            $table->string('product_name');
            $table->text('description');
            $table->decimal('price', 15, 2);
            $table->foreignId('category_id');
            $table->json('attributes')->nullable();
            $table->json('images'); // Menyimpan path ke 3 foto
            $table->string('status')->default('Menunggu Persetujuan'); // Status awal
            $table->text('admin_notes')->nullable(); // Catatan dari admin
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};