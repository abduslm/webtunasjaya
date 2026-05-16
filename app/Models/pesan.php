<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesan extends Model
{
    use HasFactory;

    // 1. Tentukan nama tabel secara manual (karena di migrasi kamu pakai 'pesan')
    protected $table = 'pesan';

    // 2. Tentukan primary key manual (karena kamu pakai 'id_pesan')
    protected $primaryKey = 'id_pesan';

    // 3. Daftarkan kolom yang boleh diisi (Mass Assignment)
    protected $fillable = [
        'nama_lengkap',
        'email',
        'subject',
        'pesan',
        'status',
    ];
    protected $attributes = [
        'status' => 'belum-dibaca'
    ];

}