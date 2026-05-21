<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lokasi extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_lokasi';
    protected $fillable = [
        'klien',
        'alamat',
        'longitude',
        'latitude',
        'radius',
        'gambar'
    ];
    protected $attributes = [
        'gambar' => null
    ];
    public function dataKaryawan() {
        return $this->hasMany(Data_karyawan::class, 'id_lokasi');
    }
}
