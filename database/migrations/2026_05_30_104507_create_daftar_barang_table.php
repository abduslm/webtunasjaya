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
        // Nama tabel diganti menjadi daftar_barang
        Schema::create('daftar_barang', function (Blueprint $table) {
            $table->id('id_barang'); // id_barang (Primary Key)
            $table->string('nama_barang'); 
            $table->string('kategori'); 
            $table->integer('stok')->default(0); 
            $table->string('satuan'); 
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daftar_barang');
    }
};