<?php

namespace App\Exports;

use App\Models\Aktivitas_Inventaris;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class AktivitasInventarisExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $query = Aktivitas_Inventaris::with('barang');

        // 1. PERBAIKAN: Filter berdasarkan id_barang (bukan string teks 'search' lagi)
        if ($this->request->filled('id_barang')) {
            $query->where('id_barang', $this->request->id_barang);
        }

        // 2. Filter Status Aktivitas (masuk / keluar)
        if ($this->request->filled('status') && $this->request->status !== 'semua') {
            $query->where('status', $this->request->status);
        }

        // 3. PERBAIKAN: Filter berdasarkan nama Tujuan / Pengirim spesifik dari DB
        if ($this->request->filled('tujuan')) {
            $query->where('tujuan', $this->request->tujuan);
        }

        // 4. Filter Rentang Tanggal (menggunakan created_at bawaan Eloquent)
        if ($this->request->filled('tanggal_mulai') && $this->request->filled('tanggal_selesai')) {
            $query->whereBetween('created_at', [
                Carbon::parse($this->request->tanggal_mulai)->startOfDay(), 
                Carbon::parse($this->request->tanggal_selesai)->endOfDay()
            ]);
        } elseif ($this->request->filled('tanggal_mulai')) {
            $query->where('created_at', '>=', Carbon::parse($this->request->tanggal_mulai)->startOfDay());
        } elseif ($this->request->filled('tanggal_selesai')) {
            $query->where('created_at', '<=', Carbon::parse($this->request->tanggal_selesai)->endOfDay());
        } else {
            // Default: Jika filter tanggal kosong, download data bulan berjalan saat ini
            $query->whereMonth('created_at', Carbon::now()->month)
                  ->whereYear('created_at', Carbon::now()->year);
        }

        // Urutkan berdasarkan aktivitas terbaru menggunakan primary key Anda
        return $query->latest('id_aktivitas');
    }

    // Menentukan pemetaan isi kolom Excel berdasarkan attribute model Anda
    public function map($aktivitas): array
    {
        return [
            Carbon::parse($aktivitas->created_at)->translatedFormat('d M Y H:i'),
            $aktivitas->barang->nama_barang ?? 'Barang Terhapus',
            ucfirst($aktivitas->status), 
            $aktivitas->stok_awal . ' Qty',
            $aktivitas->status === 'masuk' ? '+' . $aktivitas->jumlah : '-' . $aktivitas->jumlah,
            $aktivitas->sisa_stok . ' Qty',
            $aktivitas->tujuan ?? '-',
            $aktivitas->catatan ?? '-',
        ];
    }

    // Menentukan Judul Header Excel
    public function headings(): array
    {
        return [
            'Waktu Aktivitas',
            'Nama Barang',
            'Status Transaksi',
            'Stok Sebelum Mutasi',
            'Jumlah Perubahan',
            'Stok Akhir',
            'Tujuan / Penerima',
            'Catatan',
        ];
    }

    // Styling Header (Bold)
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}