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
        Schema::create('timelines', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Judul timeline (ex: Pendaftaran, Verifikasi, etc.)
            $table->string('start_date')->nullable(); // Format tanggal (ex: 1 - 7 April 2025)
            $table->integer('order')->default(0); // Urutan tampilan
            $table->boolean('is_enabled')->default(true); // Apakah timeline ini ditampilkan
            $table->boolean('is_active')->default(false); // Apakah timeline ini sedang aktif
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timelines');
    }
};
