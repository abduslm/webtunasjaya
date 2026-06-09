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
        Schema::create('aktivitas_inventaris', function (Blueprint $table) {
            $table->id('id_aktivitas');
            $table->enum('status', ['masuk', 'keluar']);
            $table->integer('jumlah');
            $table->integer('stok_awal')->default(0);
            $table->integer('sisa_stok');
            $table->string('tujuan')->default('-');
            $table->text('catatan')->nullable();
            $table->unsignedBigInteger('id_barang');
            $table->timestamps();

            $table->foreign('id_barang')->references('id_barang')->on('daftar_barang')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aktivitas_inventaris');
    }
};