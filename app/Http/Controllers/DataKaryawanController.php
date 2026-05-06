<?php

namespace App\Http\Controllers;

use App\Models\Data_karyawan;
use App\Models\User;
use App\Models\Lokasi;
use Illuminate\Http\Request;

class DataKaryawanController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $karyawanList = Data_karyawan::with('lokasi')->orderBy('nama_lengkap', 'asc')->paginate(10);
        $daftarLokasi = Lokasi::orderBy('alamat', 'asc')->get();

        $users = User::whereDoesntHave('dataKaryawan')->orderBy('email', 'asc')->get();
        return view('admin.absensi.kelolaKaryawan', compact('karyawanList', 'users', 'daftarLokasi'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'alamat' => 'required|string|max:500',
            'no_hp' => 'required|string|max:15',
            'foto' => 'nullable|image|max:2048',
            'id_lokasi' => 'nullable|exists:lokasis,id_lokasi',
            'id_user' => 'nullable|exists:users,id',
        ],[
            'id_user.required' => 'Akun user harus dipilih.',
            'id_user.exists' => 'Akun user yang dipilih tidak valid.',
            'foto.image' => 'File yang diterima yakni Image',
        ]);

        Data_karyawan::create([
            'nama_lengkap' => $validatedData['nama_lengkap'],
            'tanggal_lahir' => $validatedData['tanggal_lahir'],
            'jenis_kelamin' => $validatedData['jenis_kelamin'],
            'alamat' => $validatedData['alamat'],
            'no_hp' => $validatedData['no_hp'],
            'foto' => $validatedData['foto'] ?? null,
            'id_lokasi' => $validatedData['id_lokasi'] ?? null,
            'id_user' => $validatedData['id_user'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Data karyawan berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show($id_karyawan)
    {
        $karyawanList = Data_karyawan::with('lokasi')->findOrFail($id_karyawan);
        return view('admin.absensi.kelolaKaryawan', compact('karyawanList'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Data_karyawan $data_karyawan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id_karyawan)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'alamat' => 'required|string|max:500',
            'no_hp' => 'required|string|max:20',
            'foto' => 'nullable|image|max:2048',
            'id_lokasi' => 'nullable|exists:lokasis,id_lokasi',
            'id_user' => 'nullable|exists:users,id',
        ],[
            'id_user.required' => 'Akun user harus dipilih.',
            'id_user.exists' => 'Akun user yang dipilih tidak valid.',
            'id_lokasi.exists' => 'Lokasi Absensi yang dipilih tidak valid.',
            'jenis_kelamin.in' => 'Jenis kelamin yang tersedia hanya Laki-laki dan Perempuan'
        ]);

        $data_karyawan = Data_karyawan::findOrFail($id_karyawan);
        $data_karyawan->update([
            'nama_lengkap' => $validated['nama_lengkap'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'alamat' => $validated['alamat'],
            'no_hp' => $validated['no_hp'],
            'foto' => $validated['foto'] ?? null,
            'id_lokasi' => $validated['id_lokasi'] ?? null,
            'id_user' => $validated['id_user'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Data karyawan berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_karyawan)
    {
        $data_karyawan = Data_karyawan::findOrFail($id_karyawan);
        $data_karyawan->delete();
        return redirect()->back()->with('success', 'Data karyawan berhasil dihapus');
    }


    public function createKaryawanWithUser(Request $request)
    {
        $validatedData = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'alamat' => 'required|string|max:500',
            'no_hp' => 'required|string|max:20',
            'foto' => 'nullable|image|max:2048',
            'id_lokasi' => 'nullable|exists:lokasis,id_lokasi',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|string',
            'status' => 'required|string',
            'device_id' => 'nullable|string|max:255',
        ]);

        $user = User::create([
            'email' => $validatedData['email'],
            'password' => $validatedData['password'],
            'role' => $validatedData['role'] ?? 'karyawan',
            'status' => $validatedData['status'] ?? 'non-aktif',
            'device_id' => $validatedData['device_id'] ?? null,
        ]);

        Data_karyawan::create([
            'nama_lengkap' => $validatedData['nama_lengkap'],
            'tanggal_lahir' => $validatedData['tanggal_lahir'],
            'jenis_kelamin' => $validatedData['jenis_kelamin'],
            'alamat' => $validatedData['alamat'],
            'no_hp' => $validatedData['no_hp'],
            'foto' => $validatedData['foto'] ?? null,
            'id_lokasi' => $validatedData['id_lokasi'] ?? null,
            'id_user' => $user->id,
        ]);

        return redirect()->back()->with('success', 'Data karyawan dan akun user berhasil ditambahkan');
    }

    public function updateKaryawanWithUser(Request $request, $id_karyawan)
    {
        $validatedData = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'alamat' => 'required|string|max:500',
            'no_hp' => 'required|string|max:20',
            'foto' => 'nullable|image|max:2048',
            'id_lokasi' => 'nullable|exists:lokasis,id_lokasi',
            'email' => 'required|string|email|max:255|unique:users,email,' . ($data_karyawan->id_user ?? ''),
            'password' => 'nullable|string|min:8',
            'role' => 'required|string',
            'status' => 'required|string',
            'device_id' => 'nullable|string|max:255',
        ]);

        $data_karyawan = Data_karyawan::findOrFail($id_karyawan);
        $user = User::updateOrCreate(
            ['id' => $data_karyawan->id_user],
            [
                'email' => $validatedData['email'],
                'password' => $validatedData['password'] ?? null,
                'role' => $validatedData['role'] ?? 'karyawan',
                'status' => $validatedData['status'] ?? 'non-aktif',
                'device_id' => $validatedData['device_id'] ?? null,
            ]
        );
        
        $data_karyawan->update([
            'nama_lengkap' => $validatedData['nama_lengkap'],
            'tanggal_lahir' => $validatedData['tanggal_lahir'],
            'jenis_kelamin' => $validatedData['jenis_kelamin'],
            'alamat' => $validatedData['alamat'],
            'no_hp' => $validatedData['no_hp'],
            'foto' => $validatedData['foto'] ?? null,
            'id_lokasi' => $validatedData['id_lokasi'] ?? null,
            'id_user' => $user->id,
        ]);

        return redirect()->back()->with('success', 'Data karyawan dan akun user berhasil diperbarui');
    }

}
