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

        // Filter Nama (Search)
        if ($this->request->has('search') && $this->request->search != '') {
            $query->whereHas('user.dataKaryawan', function($q) use ($search) {
                $q->where('nama_lengkap', 'like', '%' . $this->request->search . '%');
            });
        }

        $filterTanggal = $this->request->get('tanggal', Carbon::today()->toDateString());
        $query->whereDate('tanggal', $filterTanggal);

        // Filter Tanggal
        if ($this->request->has('tanggal') && $this->request->tanggal != '') {
            $query->whereDate('tanggal', $this->request->tanggal);
        }

        // Filter Status
        if ($this->request->has('status') && $this->request->status != 'Semua') {
            $query->where('status', $this->request->status);
        }

        return $query->latest('tanggal');
        
    }

    // Menentukan isi kolom Excel
    public function map($absensi): array
    {
        return [
            $absensi->tanggal,
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
