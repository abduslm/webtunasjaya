<?php

namespace App\Exports;

use App\Models\Absensi;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class AbsensiExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $query = Absensi::with('user.dataKaryawan');

        // 1. Filter Nama (Search) - Perbaikan use statement
        if ($this->request->filled('search')) {
            $query->whereHas('user.dataKaryawan', function($q) {
                $q->where('nama_lengkap', 'like', '%' . $this->request->search . '%');
            });
        }

        // 2. Filter Rentang Tanggal (Date Range) - Sinkron dengan Controller Index
        if ($this->request->filled('tanggal_mulai') && $this->request->filled('tanggal_selesai')) {
            $query->whereBetween('tanggal', [$this->request->tanggal_mulai, $this->request->tanggal_selesai]);
        } elseif ($this->request->filled('tanggal_mulai')) {
            $query->where('tanggal', '>=', $this->request->tanggal_mulai);
        } elseif ($this->request->filled('tanggal_selesai')) {
            $query->where('tanggal', '<=', $this->request->tanggal_selesai);
        } else {
            // Jika kosong, default export data bulan ini berjalan
            $query->whereMonth('tanggal', Carbon::now()->month)
                    ->whereYear('tanggal', Carbon::now()->year);
        }

        // 3. Filter Status (Menyesuaikan value 'semua' dari AlpineJS)
        if ($this->request->filled('status') && $this->request->status !== 'semua') {
            $query->where('status', $this->request->status);
        }

        return $query->latest('tanggal');
    }

    // Menentukan isi kolom Excel
    public function map($absensi): array
    {
        return [
            // Memformat bentuk tanggal di excel agar rapi (Contoh: 10 Jun 2026)
            Carbon::parse($absensi->tanggal)->translatedFormat('d M Y'),
            $absensi->user->dataKaryawan->nama_lengkap ?? 'User Dihapus',
            $absensi->absen_masuk ?? '--:--',
            $absensi->absen_keluar ?? '--:--',
            $absensi->total_waktu ?? '-',
            ucfirst($absensi->status),
        ];
    }

    // Menentukan Header Excel
    public function headings(): array
    {
        return [
            'Tanggal',
            'Nama Karyawan',
            'Jam Masuk',
            'Jam Keluar',
            'Total Jam Kerja',
            'Status',
        ];
    }

    // Styling sederhana (Header Bold)
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}