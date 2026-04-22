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
        Schema::create('profil_perusahaans', function (Blueprint $table) {
            $table->id('id_profilPerusahaan');
            $table->string('nama_perusahaan');
            $table->string('motto')->nullable();
            $table->string('logo')->nullable();
            $table->string('no_telepon')->nullable();
            $table->string('email')->nullable();
            $table->text('alamat')->nullable();
            $table->string('senin_jumat')->nullable();
            $table->string('sabtu')->nullable();
            $table->string('minggu')->nullable();
            $table->string('facebook')->nullable();
            $table->string('ig')->nullable();
            $table->string('linkedIn')->nullable();
            $table->string('twitter')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profil_perusahaans');
    }
};
