<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{

    protected $primaryKey = 'id_absensi';

    protected $fillable = [
        'absen_masuk',
        'absen_keluar',
        'total_waktu',
        'tanggal',
        'status',
        'id_user'
    ];
    protected $attributes = [
        'status' => 'hadir'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'id_user');
    }
    public function koreksiAbsensi() {
        return $this->hasMany(Koreksi_absensi::class, 'id_absensi');
    }
}
