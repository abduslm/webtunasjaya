<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aktivitas_Inventaris;
use App\Models\DaftarBarang;


class AktivitasinventarisController extends Controller
{
    public function index(Request $request)
    {
        $query = Aktivitas_Inventaris::with('barang')->latest('id_aktivitas');
        if ($request->has('status') && $request->status != null) {
            $query->where('status', $request->status);
        }

        if ($request->has('id_barang') && $request->id_barang != null) {
            $query->where('id_barang', $request->id_barang);
        }

        if ($request->has('tujuan') && $request->tujuan != null) {
            $query->where('tujuan', $request->tujuan);
        }
        $aktivitas = $query->paginate(25)->withQueryString();

        $daftarBarangFilter = DaftarBarang::orderBy('nama_barang', 'asc')->get();
        $daftarTujuanFilter = Aktivitas_Inventaris::select('tujuan')
                                ->distinct()
                                ->whereNotNull('tujuan')
                                ->orderBy('tujuan', 'asc')
                                ->pluck('tujuan');

        $logTerakhirPerBarang = Aktivitas_Inventaris::selectRaw('MAX(id_aktivitas) as id_terakhir, id_barang')
                                ->groupBy('id_barang')
                                ->pluck('id_terakhir')
                                ->toArray();

        return view('admin.absensi.aktivitasInventaris', compact('aktivitas', 'daftarBarangFilter', 'daftarTujuanFilter', 'logTerakhirPerBarang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_barang' => 'required|exists:daftar_barang,id_barang',
            'status'    => 'required|in:masuk,keluar',
            'jumlah'    => 'required|integer|min:1',
            'tujuan'    => 'required|string|max:255',
            'catatan'   => 'nullable|string',
        ]);
        if ($request->status === 'keluar') {
            $barang = DaftarBarang::findOrFail($request->id_barang);
            if ($barang->stok < $request->jumlah) {
                return redirect()->back()->withErrors(['stok' => 'Transaksi gagal. Jumlah pengeluaran melebihi sisa stok barang saat ini.'])->withInput();
            }
        }

        Aktivitas_Inventaris::create($request->all());

        return redirect()->route('admin.aktivitas.index')->with('success', 'Log mutasi inventaris berhasil direkam.');
    }

    public function update(Request $request, $id)
    {
        $log = Aktivitas_Inventaris::findOrFail($id);
        $logTerakhirId = Aktivitas_Inventaris::where('id_barang', $log->id_barang)->max('id_aktivitas');

        $request->validate([
            'jumlah'  => 'required|integer|min:1',
            'tujuan'  => 'required|string|max:255',
            'catatan' => 'nullable|string',
        ]);

        if ($log->id_aktivitas !== $logTerakhirId && $request->jumlah != $log->jumlah) {
            return redirect()->back()->withErrors(['error' => 'Gagal. Hanya jumlah pada log mutasi paling terakhir dari barang ini yang boleh diubah.']);
        }
        $log->update($request->only(['jumlah', 'tujuan', 'catatan']));

        return redirect()->back()->with('success', 'Rincian data log aktivitas berhasil disesuaikan.');
    }

    public function destroy($id)
    {
        $log = Aktivitas_Inventaris::findOrFail($id);
        $log->delete();

        return redirect()->back()->with('success', 'Log transaksi berhasil dihapus, stok barang otomatis dikembalikan.');
    }

}