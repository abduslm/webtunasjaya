{{-- resources/views/admin/front_pages/hubungiKami.blade.php --}}
@extends('admin.adminLayout')

@section('content')
<div class="p-8">
    <div class="mb-8">
        <h2 class="text-2xl text-gray-900 mb-1">Kelola Hubungi Kami</h2>
        <p class="text-gray-500">Atur informasi kontak perusahaan</p>
    </div>

    {{-- action="{{ route('hubungi-kami.update') }}" --}}
    <form method="POST" >
        @csrf
        @method('PUT')

        <div class="space-y-6">
            {{-- Informasi Kontak --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Kontak</h3>
                <div class="space-y-4">
                    {{-- Telepon --}}
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-lg bg-[#e8f5f1] flex items-center justify-center flex-shrink-0">
                            <i class="bi bi-telephone-fill text-[#0a4d3c] text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <label class="block mb-2 text-gray-900 font-medium">Nomor Telepon</label>
                            <input type="text" 
                                name="telepon"
                                value="{{ old('telepon', $kontak['telepon'] ?? '+62 21 1234 5678') }}"
                                class="w-full px-4 py-3 bg-gray-50 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#0a4d3c] focus:border-transparent">
                            @error('telepon') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-lg bg-[#e8f5f1] flex items-center justify-center flex-shrink-0">
                            <i class="bi bi-envelope-fill text-[#0a4d3c] text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <label class="block mb-2 text-gray-900 font-medium">Email</label>
                            <input type="email" 
                                name="email"
                                value="{{ old('email', $kontak['email'] ?? 'info@cleaningservice.com') }}"
                                class="w-full px-4 py-3 bg-gray-50 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#0a4d3c] focus:border-transparent">
                            @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Alamat --}}
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-lg bg-[#e8f5f1] flex items-center justify-center flex-shrink-0">
                            <i class="bi bi-geo-alt-fill text-[#0a4d3c] text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <label class="block mb-2 text-gray-900 font-medium">Alamat</label>
                            <textarea name="alamat" 
                                    rows="3"
                                    class="w-full px-4 py-3 bg-gray-50 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#0a4d3c] focus:border-transparent">{{ old('alamat', $kontak['alamat'] ?? 'Jl. Sudirman No. 123, Jakarta Pusat, DKI Jakarta 10220') }}</textarea>
                            @error('alamat') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Jam Operasional --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Jam Operasional</h3>
                <div class="space-y-3">
                    @php
                        $defaultJam = [
                            ['hari' => 'Senin - Jumat', 'jam' => '08:00 - 17:00'],
                            ['hari' => 'Sabtu', 'jam' => '08:00 - 14:00'],
                            ['hari' => 'Minggu', 'jam' => 'Tutup'],
                        ];
                        $jamOperasional = $jamOperasional ?? $defaultJam;
                    @endphp
                    @foreach($jamOperasional as $index => $item)
                    <div class="flex items-center justify-between p-4 bg-[#fafbfc] rounded-lg">
                        <span class="text-gray-900 font-medium">{{ $item['hari'] }}</span>
                        <input type="text" 
                            name="jam_operasional[{{ $index }}][jam]"
                            value="{{ old('jam_operasional.' . $index . '.jam', $item['jam']) }}"
                            class="w-64 px-4 py-2 bg-white rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#0a4d3c] focus:border-transparent">
                        <input type="hidden" name="jam_operasional[{{ $index }}][hari]" value="{{ $item['hari'] }}">
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Media Sosial --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Media Sosial</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @php
                        $defaultSosmed = [
                            'Facebook' => 'facebook.com/cleaningservice',
                            'Instagram' => 'instagram.com/cleaningservice',
                            'LinkedIn' => 'linkedin.com/company/cleaningservice',
                            'Twitter' => 'twitter.com/cleaningservice',
                        ];
                        $mediaSosial = $mediaSosial ?? $defaultSosmed;
                    @endphp
                    @foreach($mediaSosial as $platform => $url)
                    <div>
                        <label class="block mb-2 text-sm text-gray-500">{{ $platform }}</label>
                        <input type="text" 
                            name="media_sosial[{{ $platform }}]"
                            value="{{ old('media_sosial.' . $platform, $url) }}"
                            class="w-full px-4 py-3 bg-gray-50 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#0a4d3c] focus:border-transparent">
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex gap-3">
                <button type="submit" class="px-6 py-3 bg-[#0a4d3c] text-white rounded-lg hover:bg-[#0a4d3c]/90 transition-colors">
                    Simpan Perubahan
                </button>
                <button type="reset" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    Reset
                </button>
            </div>
        </div>
    </form>
</div>
@endsection