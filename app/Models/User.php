<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // ← TAMBAHKAN INI

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable; // ← TAMBAHKAN HasApiTokens

    protected $fillable = [
        'email',    
        'password',
        'role',
        'status',
        'device_id'
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    protected $attributes = [
        'role' => 'karyawan',
        'status' => 'non-aktif'
    ];

    public function pengajuanIzin() {
        return $this->hasMany(Pengajuan_izin::class, 'id_user');
    }

    public function absensi() {
        return $this->hasMany(Absensi::class, 'id_user');
    }

    public function dataKaryawan() {
        return $this->hasOne(Data_karyawan::class, 'id_user');
    }
}