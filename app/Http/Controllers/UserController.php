<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;


class UserController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        $userWithKaryawan = User::with('dataKaryawan.lokasi')->get();
        return view('admin.absensi.kelolaUser', compact('users', 'userWithKaryawan'));
    }

    public function indexWithRequest(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');

        $query = User::with(['dataKaryawan.lokasi']);

        // Logika Filter & Search
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                ->orWhereHas('dataKaryawan', function($q2) use ($search) {
                    $q2->where('nama_lengkap', 'like', "%{$search}%");
                });
            });
        }

        if ($status && $status !== 'semua') {
            $query->where('status', $status);
        }
        
        $userWithKaryawan = $query->paginate(10)->withQueryString();

        return view('admin.absensi.kelolaUser', compact('userWithKaryawan'));
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
        $validated = $request->validate([
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|string',
            'status' => 'required|string',
            'device_id' => 'nullable|string|max:255',
        ],[
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'role.required' => 'Role wajib diisi.',
            'status.required' => 'Status wajib diisi.',
        ]);
        User::create([
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => $validated['role'] ?? 'karyawan',
            'status' => $validated['status'] ?? 'non-aktif',
            'device_id' => $validated['device_id'] ?? null,
        ]);
        return redirect()->back()->with('success', 'User berhasil ditambahkan!');
        //return redirect()->route('admin.absensi.kelolaUser')->with('success', 'User berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user = User::findOrFail($user->id);
        $userWithKaryawanLokasi = User::with('dataKaryawan.lokasi')->findOrFail($user->id);
        return view('admin.absensi.kelolaUser', compact('user', 'userWithKaryawanLokasi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'role' => 'required|string',
            'status' => 'required|string',
            'device_id' => 'nullable|string|max:255',
        ],[
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.min' => 'Password minimal 8 karakter.',
            'role.required' => 'Role wajib diisi.',
            'status.required' => 'Status wajib diisi.',
        ]);
        $user->update([
            'email' => $validated['email'],
            'password' => $validated['password'] ?? $user->password,
            'role' => $validated['role'] ?? $user->role,
            'status' => $validated['status'] ?? $user->status,
            'device_id' => $validated['device_id'] ?? $user->device_id,
        ]);
        
        return redirect()->back()->with('success', 'User berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->back()->with('success', 'User berhasil dihapus');
    }
}
