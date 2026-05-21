<?php

namespace App\Http\Controllers;

use App\Models\pesan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PesanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pesan::query();
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('pesan', 'like', "%{$search}%");
            });
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        if ($request->has('subject') && $request->subject != '') {
            $query->where('subject', $request->subject);
        }
        $belumDibacaCount = Pesan::where('status', 'belum-dibaca')->count();
        $totalBulanIniCount = Pesan::whereMonth('created_at', Carbon::now()->month)
                                    ->whereYear('created_at', Carbon::now()->year)
                                    ->count();
        $daftarSubject = Pesan::select('subject')->distinct()->pluck('subject');
        $pesans = $query->orderBy('created_at', 'desc')->paginate(25)->withQueryString();

        return view('admin.front_pages.pesan', compact(
            'pesans', 
            'belumDibacaCount', 
            'totalBulanIniCount', 
            'daftarSubject'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'subject' => 'required|string|max:255',
            'pesan' => 'required|string',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
        ]);

        Pesan::create($validated);

        return redirect()->back()->with('success', 'Pesan berhasil dikirim!');
    }

    public function tandaiDibaca($id_pesan)
    {
        $pesan = Pesan::findOrFail($id_pesan);
        $pesan->update(['status' => 'dibaca']);

        return redirect()->back()->with('success', 'Pesan berhasil ditandai dibaca.');
    }

    public function destroy($id_pesan)
    {
        $pesan = Pesan::findOrFail($id_pesan);
        $pesan->delete();

        return redirect()->back()->with('success', 'Pesan berhasil dihapus.');
    }

    public function destroyPeriode(Request $request)
    {
        $request->validate([
            'periode' => 'required|string'
        ]);

        $dateThreshold = match($request->periode) {
            '3_bulan' => now()->subMonths(3),
            '6_bulan' => now()->subMonths(6),
            '1_tahun' => now()->subYear(),
            '2_tahun' => now()->subYears(2),
            default => null
        };

        if (!$dateThreshold) {
            return redirect()->back()->with('error', 'Periode pembersihan tidak valid.');
        }

        $deletedCount = Pesan::where('created_at', '<', $dateThreshold)->delete();

        return redirect()->back()->with('success', "Berhasil menghapus {$deletedCount} pesan lama.");
    }
}
