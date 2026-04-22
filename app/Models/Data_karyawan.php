<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Data_karyawan extends Model
{

    protected $primaryKey = 'id_karyawan';

    protected $fillable = [
        'nama_lengkap',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'email',
        'no_hp',
        'foto',
        'id_lokasi',
        'id_user'
    ];

    protected $attributes = [
        'foto' => null
    ];

    public function user() {
        return $this->belongsTo(User::class, 'id_user');
    }
    public function lokasi() {
        return $this->belongsTo(Lokasi::class, 'id_lokasi');
    }
}
