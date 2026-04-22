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
        Schema::create('pengajuan_izins', function (Blueprint $table) {
            $table->id('id_pengajuanIzin');
            $table->string('jenis_izin');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->string('media_pendukung')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_izins');
    }
};
