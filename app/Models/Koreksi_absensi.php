<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Koreksi_absensi extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_koreksi';

    protected $fillable = [
        'jenis_koreksi',
        'absen_masuk',
        'absen_keluar', 
        'total_waktu',
        'tanggal',
        'alasan', 
        'media_pendukung', 
        'status', 
        'id_absensi'
    ];
    protected $attributes = [
        'status' => 'pending',
        'media_pendukung' => null
    ];

    public function absensi() {
        return $this->belongsTo(Absensi::class, 'id_absensi');
    }
}
