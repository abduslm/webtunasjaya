<?php
// app/Models/AktivitasInventaris.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Aktivitas_Inventaris extends Model
{
    protected $table = 'aktivitas_inventaris';
    protected $primaryKey = 'id_aktivitas';
    protected $fillable = [
        'status', 
        'jumlah', 
        'stok_awal',
        'sisa_stok', 
        'tujuan', 
        'catatan', 
        'id_barang'];

    public function barang(): BelongsTo
    {
        return $this->belongsTo(DaftarBarang::class, 'id_barang', 'id_barang');
    }

    
    protected static function booted()
    {
        //create
        static::creating(function ($aktivitas) {
            $barang = DaftarBarang::findOrFail($aktivitas->id_barang);
    
            $aktivitas->stok_awal = $barang->stok;
            if ($aktivitas->status === 'masuk') {
                $barang->stok += $aktivitas->jumlah;
            } else {
                $barang->stok -= $aktivitas->jumlah;
            }
            $barang->save();
            $aktivitas->sisa_stok = $barang->stok;
        });

        // update
        static::updating(function ($aktivitas) {
            $barang = DaftarBarang::findOrFail($aktivitas->id_barang);
            $originalJumlah = $aktivitas->getOriginal('jumlah');
            $originalStatus = $aktivitas->getOriginal('status');

            if ($originalStatus === 'masuk') {
                $barang->stok -= $originalJumlah;
            } else {
                $barang->stok += $originalJumlah;
            }
            
            $aktivitas->stok_awal = $barang->stok;

            if ($aktivitas->status === 'masuk') {
                $barang->stok += $aktivitas->jumlah;
            } else {
                $barang->stok -= $aktivitas->jumlah;
            }
            
            $barang->save();
            
            $aktivitas->sisa_stok = $barang->stok;
        });

        // deleete
        static::deleted(function ($aktivitas) {
            $barang = DaftarBarang::find($aktivitas->id_barang);
            
            if ($barang) {
                if ($aktivitas->status === 'masuk') {
                    $barang->decrement('stok', $aktivitas->jumlah);
                } else {
                    $barang->increment('stok', $aktivitas->jumlah);
                }
            }
        });
    }
}

