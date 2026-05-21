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
        Schema::create('koreksi_absensis', function (Blueprint $table) {
            $table->id('id_koreksi');
            $table->string('jenis_koreksi');
            $table->date('tanggal');
            $table->time('absen_masuk')->nullable();
            $table->time('absen_keluar')->nullable();
            $table->decimal('total_waktu',4,2)->nullable();
            $table->string('alasan');
            $table->string('media_pendukung')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
            $table->foreignId('id_absensi')->constrained('absensis','id_absensi')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('koreksi_absensis');
    }
};
