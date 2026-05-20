<?php

namespace App\Http\Controllers;

abstract class UserController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return view('admin.absensi.kelolaUser', compact('users'));
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
        ]);
        User::create([
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => $validated['role'] ?? 'karyawan',
            'status' => $validated['status'] ?? 'non-aktif',
            'device_id' => $validated['device_id'] ?? null,
        ]);
        return redirect()->route('admin.absensi.kelolaUser')->with('success', 'User berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user = User::findOrFail($user->id);
        return view('admin.absensi.kelolaUser', compact('user'));
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
        ]);
        $user->update([
            'email' => $validated['email'],
            'password' => $validated['password'] ?? $user->password,
            'role' => $validated['role'] ?? $user->role,
            'status' => $validated['status'] ?? $user->status,
            'device_id' => $validated['device_id'] ?? $user->device_id,
        ]);
        return redirect()->route('admin.absensi.kelolaUser')->with('success', 'User berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.absensi.kelolaUser')->with('success', 'User berhasil dihapus');
    }
}
