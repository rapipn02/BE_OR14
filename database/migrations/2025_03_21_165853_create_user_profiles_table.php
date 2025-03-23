<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration untuk membuat tabel user_profiles.
     */
    public function up()
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique(); // Hubungan ke users
            $table->string('nama_lengkap');
            $table->string('panggilan');
            $table->string('nim')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('program_studi')->nullable();
            $table->string('divisi')->nullable();
            $table->string('sub_divisi')->nullable();
            $table->string('twibbon')->nullable();
            $table->string('photo')->nullable(); // Untuk menyimpan foto profil
            $table->timestamps();
    
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
    
};
