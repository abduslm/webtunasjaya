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
        Schema::create('data_karyawans', function (Blueprint $table) {
            $table->id('id_karyawan');
            $table->string('nama_lengkap');
            $table->date('tanggal_lahir');
            $table->string('jenis_kelamin');
            $table->text('alamat');
            $table->string('no_hp');
            $table->string('foto')->nullable();
            $table->foreignId('id_lokasi')->nullable()->constrained('lokasis','id_lokasi')->onDelete('set null');
            $table->foreignId('id_user')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_karyawans');
    }
};
