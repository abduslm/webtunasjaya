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
        Schema::create('pesan', function (Blueprint $table) {
            // Menggunakan id_pesan sebagai primary key sesuai gayamu
            $table->id('id_pesan'); 
            
            $table->string('nama_lengkap');
            $table->string('email');
            $table->string('subject');
            $table->text('pesan');
            
            // Kolom waktu (created_at & updated_at)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesans');
    }
};