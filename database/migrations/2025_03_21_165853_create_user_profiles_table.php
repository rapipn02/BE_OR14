<?php
// database/migrations/2025_03_23_000002_create_user_profiles_table.php

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
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            $table->string('nama_lengkap');
            $table->string('panggilan');
            $table->string('nim')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('program_studi')->nullable();
            $table->string('divisi')->nullable();
            $table->string('sub_divisi')->nullable();
            $table->string('departemen')->nullable();
            $table->string('twibbon')->nullable();
            $table->string('photo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
