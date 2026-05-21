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
        Schema::create('absensis', function (Blueprint $table) {
            $table->id('id_absensi');
            $table->time('absen_masuk')->nullable();
            $table->time('absen_keluar')->nullable();
            $table->decimal('total_waktu',4,2)->nullable();
            $table->date('tanggal');
            $table->string('status')->default('hadir');
            $table->timestamps();
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};
