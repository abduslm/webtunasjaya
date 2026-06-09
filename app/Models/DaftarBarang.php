<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DaftarBarang extends Model
{
    use HasFactory;

    // Menghubungkan model ini secara spesifik ke tabel 'daftar_barang'
    protected $table = 'daftar_barang';

    // Menentukan primary key kustom
    protected $primaryKey = 'id_barang';

    // Kolom yang boleh diisi (Mass Assignment)
    protected $fillable = [
        'nama_barang',
        'kategori',
        'stok',
        'satuan',
    ];

    public function aktivitas(): HasMany
    {
        return $this->hasMany(Aktivitas_Inventaris::class, 'id_barang', 'id_barang');
    }
}