<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class kelola_halaman extends Model
{

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
