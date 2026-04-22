<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengajuan_izin extends Model
{
    protected $primaryKey = 'id_pengajuanIzin';
    protected $fillable = [
        'jenis_izin',
        'tanggal_mulai',
        'tanggal_selesai',
        'media_pendukung',
        'status',
        'id_user'
    ];
    protected $attributes = [
        'status' => 'pending',
        'media_pendukung' => null
    ];
    
    public function user() {
        return $this->belongsTo(User::class, 'id_user');
    }
}
