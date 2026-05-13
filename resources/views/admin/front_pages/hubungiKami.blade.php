{{-- resources/views/admin/front_pages/hubungiKami.blade.php --}}
@extends('admin.adminLayout')

@section('content')
<div class="p-8">
    @if(session('success'))
        <div class="p-4 bg-green-50 text-[#0a4d3c] rounded-xl border border-green-100 flex items-center gap-3 animate-fade-in">
            <i class="bi bi-check-circle-fill"></i> 
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif
    {{-- Pesan Error --}}
    @if($errors->any())
        <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-2xl font-bold shadow-sm">
            <div class="flex items-center gap-3 mb-2">
                <i class="fas fa-exclamation-circle"></i>
                <span>Terjadi kesalahan:</span>
            </div>

            <ul class="list-disc list-inside font-medium space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="mb-8">
        <h2 class="text-2xl text-gray-900 mb-1">Kelola Hubungi Kami</h2>
        <p class="text-gray-500">Atur informasi kontak perusahaan</p>
    </div>

    <form method="POST" action="{{ route('admin.hubungi-kami.update') }}" enctype="multipart/form-data">
        @csrf

        <div class="space-y-6">
            {{-- Informasi Kontak --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Kontak</h3>
                <div class="space-y-4">

                    <div>
                        <label class="block mb-2 text-sm font-semibold text-gray-700">Nomor Telepon</label>
                        <input type="text" name="no_telepon"
                            pattern="^(?:\+62|0)8[0-9]{8,12}$"
                            title="No HP terdiri dari 10 - 14 digit angka dan harus dimulai dengan 08 atau +62"
                            value="{{ old('no_telepon', $data->no_telepon ?? '') }}"
                            placeholder="Contoh: 081234567890"
                            class="w-full px-4 py-3 bg-gray-50 rounded-lg border border-gray-200">
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-semibold text-gray-700">Email</label>
                        <input type="email" name="email"
                            value="{{ old('email', $data->email ?? '') }}"
                            class="w-full px-4 py-3 bg-gray-50 rounded-lg border border-gray-200">
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-semibold text-gray-700">Alamat</label>
                        <textarea name="alamat" rows="3" aria-describedby="pemisahAlamat"
                            class="w-full px-4 py-3 bg-gray-50 rounded-lg border border-gray-200">{{ old('alamat', $data->alamat ?? '') }}</textarea>
                        <small id="pemisahAlamat">pakai tanda " | " untuk memisah jika ada lebih dari 1 alaman</small>
                    </div>
                </div>
            </div>

            {{-- Jam Operasional --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Jam Operasional</h3>

                <div class="space-y-3">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Senin - Jumat</label>
                        <div class="flex items-center gap-3">
                            <div class="flex-1">
                                <input type="time" name="senin_jumat_mulai"
                                    value="{{ old('senin_jumat_mulai', $data->senin_jumat_mulai ?? '') }}"
                                    class="w-full px-4 py-3 bg-gray-50 rounded-lg border border-gray-200 focus:ring-2 focus:ring-[#0a4d3c] focus:outline-none">
                            </div>
                            <span class="text-gray-400">s/d</span>
                            <div class="flex-1">
                                <input type="time" name="senin_jumat_selesai"
                                    value="{{ old('senin_jumat_selesai', $data->senin_jumat_selesai ?? '') }}"
                                    class="w-full px-4 py-3 bg-gray-50 rounded-lg border border-gray-200 focus:ring-2 focus:ring-[#0a4d3c] focus:outline-none">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Sabtu</label>
                        <div class="flex items-center gap-3">
                            <div class="flex-1">
                                <input type="time" name="sabtu_mulai"
                                    value="{{ old('sabtu_mulai', $data->mulai_mulai ?? '') }}"
                                    class="w-full px-4 py-3 bg-gray-50 rounded-lg border border-gray-200 focus:ring-2 focus:ring-[#0a4d3c] focus:outline-none">
                            </div>
                            <span class="text-gray-400">s/d</span>
                            <div class="flex-1">
                                <input type="time" name="sabtu_selesai"
                                    value="{{ old('sabtu_selesai', $data->sabtu_selesai ?? '') }}"
                                    class="w-full px-4 py-3 bg-gray-50 rounded-lg border border-gray-200 focus:ring-2 focus:ring-[#0a4d3c] focus:outline-none">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Minggu</label>
                        <div class="flex items-center gap-3">
                            <div class="flex-1">
                                <input type="time" name="minggu_mulai"
                                    value="{{ old('minggu_mulai', $data->minggu_mulai ?? '') }}"
                                    class="w-full px-4 py-3 bg-gray-50 rounded-lg border border-gray-200 focus:ring-2 focus:ring-[#0a4d3c] focus:outline-none">
                            </div>
                            <span class="text-gray-400">s/d</span>
                            <div class="flex-1">
                                <input type="time" name="minggu_selesai"
                                    value="{{ old('minggu_selesai', $data->minggu_selesai ?? '') }}"
                                    class="w-full px-4 py-3 bg-gray-50 rounded-lg border border-gray-200 focus:ring-2 focus:ring-[#0a4d3c] focus:outline-none">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Media Sosial --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center gap-2 mb-6">
                    <i class="bi bi-share text-[#0a4d3c] text-xl"></i>
                    <h3 class="text-lg font-bold text-gray-900">Media Sosial</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Facebook -->
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                            <i class="bi bi-facebook text-blue-600"></i> Facebook
                        </label>
                        <input type="text" name="facebook" placeholder="https://facebook.com/..."
                            value="{{ old('facebook', $data->facebook ?? '') }}"
                            class="w-full px-4 py-2.5 bg-gray-50 rounded-lg border border-gray-200 focus:border-[#0a4d3c] focus:ring-2 focus:ring-[#0a4d3c]/10 transition-all outline-none">
                    </div>

                    <!-- Instagram -->
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                            <i class="bi bi-instagram text-pink-600"></i> Instagram
                        </label>
                        <input type="text" name="ig" placeholder="https://instagram.com/..."
                            value="{{ old('ig', $data->ig ?? '') }}"
                            class="w-full px-4 py-2.5 bg-gray-50 rounded-lg border border-gray-200 focus:border-[#0a4d3c] focus:ring-2 focus:ring-[#0a4d3c]/10 transition-all outline-none">
                    </div>

                    <!-- LinkedIn -->
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                            <i class="bi bi-linkedin text-blue-700"></i> LinkedIn
                        </label>
                        <input type="text" name="linkedIn" placeholder="https://linkedin.com/in/..."
                            value="{{ old('linkedIn', $data->linkedIn ?? '') }}"
                            class="w-full px-4 py-2.5 bg-gray-50 rounded-lg border border-gray-200 focus:border-[#0a4d3c] focus:ring-2 focus:ring-[#0a4d3c]/10 transition-all outline-none">
                    </div>

                    <!-- Twitter / X -->
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                            <i class="bi bi-twitter-x text-gray-900"></i> Twitter / X
                        </label>
                        <input type="text" name="twitter" placeholder="https://x.com/..."
                            value="{{ old('twitter', $data->twitter ?? '') }}"
                            class="w-full px-4 py-2.5 bg-gray-50 rounded-lg border border-gray-200 focus:border-[#0a4d3c] focus:ring-2 focus:ring-[#0a4d3c]/10 transition-all outline-none">
                    </div>
                </div>
            </div>

            {{-- Tombol --}}
            <div class="flex gap-3">
                <button type="submit"
                    class="px-6 py-3 bg-[#0a4d3c] text-white rounded-lg hover:bg-[#083d30]">
                    Simpan Perubahan
                </button>

                <button type="reset"
                    class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    Reset
                </button>
            </div>
        </div>
    </form>
</div>
@endsection