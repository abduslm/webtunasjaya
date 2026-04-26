<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Data_karyawan extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_karyawan';

    protected $fillable = [
        'nama_lengkap',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
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
