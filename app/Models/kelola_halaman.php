<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class kelola_halaman extends Model
{
    use HasFactory;

    protected $table = 'kelola_halaman';
    protected $primaryKey = 'id_kelolaHalaman';
    protected $fillable = [
        'section', 
        'judul', 
        'desk_singkat', 
        'desk_panjang', 
        'poin', 
        'gambar', 
        'lain_lokasi', 
        'lain_tanggal', 
        'lain_jenis'
    ];
    protected $attributes = [
        'desk_singkat' => null,
        'desk_panjang' => null,
        'poin' => null,
        'gambar' => null,
        'lain_lokasi' => null,
        'lain_tanggal' => null,
        'lain_jenis' => null
    ];
}
