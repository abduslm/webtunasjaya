<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    protected $primaryKey = 'id_lokasi';
    protected $fillable = [
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
