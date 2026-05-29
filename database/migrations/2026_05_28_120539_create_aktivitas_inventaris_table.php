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
            // id_aktivitas sebagai Primary Key (menggantikan $table->id())
            $table->id('id_aktivitas'); 
            
            // status (keluar/masuk) menggunakan enum agar datanya konsisten
            $table->enum('status', ['masuk', 'keluar']); 
            
            // jumlah barang
            $table->integer('jumlah'); 
            
            // tujuan (dibuat nullable agar bisa dikosongkan/diisi '-' saat status masuk)
            $table->string('tujuan')->nullable(); 
            
            // catatan (menggunakan text jika barangkali keterangannya panjang)
            $table->text('catatan')->nullable(); 
            
            // id_barang sebagai foreign key (menghubungkan ke tabel barang)
            // Pastikan nama tabel barang Anda adalah 'barang' atau sesuaikan kodenya
            $table->foreignId('id_barang')->constrained('barang')->onDelete('cascade'); 
            
            // timestamp otomatis membuat kolom created_at & updated_at
            $table->timestamps(); 
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