{{-- resources/views/admin/front_pages/hubungiKami.blade.php --}}
@extends('admin.adminLayout')

@section('content')
<div class="p-8">
    <div class="mb-8">
        <h2 class="text-2xl text-gray-900 mb-1">Kelola Hubungi Kami</h2>
        <p class="text-gray-500">Atur informasi kontak perusahaan</p>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 rounded-lg bg-green-100 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ url('/admin/hubungi-kami/update') }}" enctype="multipart/form-data">
        @csrf

        <div class="space-y-6">

            {{-- Informasi Perusahaan --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Perusahaan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div>
                        <label class="block mb-2 text-gray-900 font-medium">Nama Perusahaan</label>
                        <input type="text" name="nama_perusahaan"
                            value="{{ old('nama_perusahaan', $data->nama_perusahaan ?? '') }}"
                            class="w-full px-4 py-3 bg-gray-50 rounded-lg border border-gray-200">
                    </div>

                    <div>
                        <label class="block mb-2 text-gray-900 font-medium">Motto</label>
                        <input type="text" name="motto"
                            value="{{ old('motto', $data->motto ?? '') }}"
                            class="w-full px-4 py-3 bg-gray-50 rounded-lg border border-gray-200">
                    </div>

                    <div>
                        <label class="block mb-2 text-gray-900 font-medium">Logo</label>
                        <input type="file" name="logo"
                            class="w-full px-4 py-3 bg-gray-50 rounded-lg border border-gray-200">
                        @if(!empty($data?->logo))
                            <img src="{{ asset('storage/'.$data->logo) }}" class="mt-3 h-16">
                        @endif
                    </div>
                </div>
            </div>

            {{-- Informasi Kontak --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Kontak</h3>
                <div class="space-y-4">

                    <div>
                        <label class="block mb-2 text-gray-900 font-medium">Nomor Telepon</label>
                        <input type="text" name="no_telepon"
                            value="{{ old('no_telepon', $data->no_telepon ?? '') }}"
                            class="w-full px-4 py-3 bg-gray-50 rounded-lg border border-gray-200">
                    </div>

                    <div>
                        <label class="block mb-2 text-gray-900 font-medium">Email</label>
                        <input type="email" name="email"
                            value="{{ old('email', $data->email ?? '') }}"
                            class="w-full px-4 py-3 bg-gray-50 rounded-lg border border-gray-200">
                    </div>

                    <div>
                        <label class="block mb-2 text-gray-900 font-medium">Alamat</label>
                        <textarea name="alamat" rows="3"
                            class="w-full px-4 py-3 bg-gray-50 rounded-lg border border-gray-200">{{ old('alamat', $data->alamat ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Jam Operasional --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Jam Operasional</h3>

                <div class="space-y-3">
                    <div>
                        <label>Senin - Jumat</label>
                        <input type="text" name="senin_jumat"
                            value="{{ old('senin_jumat', $data->senin_jumat ?? '') }}"
                            class="w-full px-4 py-3 bg-gray-50 rounded-lg border border-gray-200">
                    </div>

                    <div>
                        <label>Sabtu</label>
                        <input type="text" name="sabtu"
                            value="{{ old('sabtu', $data->sabtu ?? '') }}"
                            class="w-full px-4 py-3 bg-gray-50 rounded-lg border border-gray-200">
                    </div>

                    <div>
                        <label>Minggu</label>
                        <input type="text" name="minggu"
                            value="{{ old('minggu', $data->minggu ?? '') }}"
                            class="w-full px-4 py-3 bg-gray-50 rounded-lg border border-gray-200">
                    </div>
                </div>
            </div>

            {{-- Media Sosial --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Media Sosial</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input type="text" name="facebook" placeholder="Facebook"
                        value="{{ old('facebook', $data->facebook ?? '') }}"
                        class="w-full px-4 py-3 bg-gray-50 rounded-lg border border-gray-200">

                    <input type="text" name="ig" placeholder="Instagram"
                        value="{{ old('ig', $data->ig ?? '') }}"
                        class="w-full px-4 py-3 bg-gray-50 rounded-lg border border-gray-200">

                    <input type="text" name="linkedIn" placeholder="LinkedIn"
                        value="{{ old('linkedIn', $data->linkedIn ?? '') }}"
                        class="w-full px-4 py-3 bg-gray-50 rounded-lg border border-gray-200">

                    <input type="text" name="twitter" placeholder="Twitter"
                        value="{{ old('twitter', $data->twitter ?? '') }}"
                        class="w-full px-4 py-3 bg-gray-50 rounded-lg border border-gray-200">
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