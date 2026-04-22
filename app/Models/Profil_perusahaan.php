<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Profil_perusahaan extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_profilPerusahaan';
    protected $fillable = [
        'nama_perusahaan', 
        'motto',
        'logo', 
        'no_telepon',
        'email', 
        'alamat', 
        'senin_jumat', 
        'sabtu', 
        'minggu', 
        'facebook', 
        'ig', 
        'linkedIn', 
        'twitter'
    ];
    protected $attributes = [
        'motto' => null,
        'logo' => null,
        'no_telepon' => null,
        'email' => null,
        'alamat' => null,
        'senin_jumat' => null,
        'sabtu' => null,
        'minggu' => null,
        'facebook' => null,
        'ig' => null,
        'linkedIn' => null,
        'twitter' => null
    ];
}
