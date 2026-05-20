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
        Schema::create('kelola_halaman', function (Blueprint $table) {
            $table->id('id_kelolaHalaman');
            $table->string('section');
            $table->string('judul');
            $table->text('desk_singkat')->nullable();
            $table->text('desk_panjang')->nullable();
            $table->text('poin')->nullable();
            $table->string('gambar')->nullable();
            $table->string('lain_lokasi')->nullable();
            $table->date('lain_tanggal')->nullable();
            $table->string('lain_jenis')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelola_halaman');
    }
};
