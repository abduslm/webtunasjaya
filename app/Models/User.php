<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{
    use HasFactory, Notifiable;

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

    // Relasi
    public function pengajuanIzin() {
        return $this->hasMany(PengajuanIzin::class, 'id_user');
    }
    public function absensi() {
        return $this->hasMany(Absensi::class, 'id_user');
    }

    public function dataKaryawan() {
        return $this->hasOne(Data_karyawan::class, 'id_user');
    }
}
