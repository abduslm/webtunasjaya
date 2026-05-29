<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AktivitasInventaris extends Model
{
    use HasFactory;

    // 1. Definisikan nama tabel secara eksplisit karena tidak menggunakan jamak (plural) bahasa Inggris
    protected $table = 'aktivitas_inventaris';

    // 2. Definisikan Primary Key yang kustom (bukan 'id')
    protected $primaryKey = 'id_aktivitas';

    // 3. Daftarkan kolom yang boleh diisi massal (Mass Assignment)
    protected $fillable = [
        'status',
        'jumlah',
        'tujuan',
        'catatan',
        'id_barang',
    ];

    /**
     * Relasi ke model Barang
     * Menyatakan bahwa setiap 1 log aktivitas ini dimiliki oleh 1 barang tertentu.
     */
    public function barang()
    {
        // Parameter kedua adalah foreign key di tabel ini, 
        // parameter ketiga adalah primary key di tabel barang.
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }
}