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

        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Kontak</h3>
            <div class="space-y-6">

                {{-- Dinamis Nomor Telepon --}}
                <div id="wrapper-telepon">
                    <label class="block mb-2 text-sm font-semibold text-gray-700 flex justify-between">
                        Nomor Telepon
                        <button type="button" onclick="addItem('wrapper-telepon', 'no_telepon[]')" class="text-blue-600 text-xs hover:underline">+ Tambah No</button>
                    </label>
                    @php $telepons = explode('|', $data->no_telepon ?? ''); @endphp
                    @foreach($telepons as $telp)
                        <div class="flex gap-2 mb-2 item-row">
                            <input type="text" name="no_telepon[]" value="{{ $telp }}" placeholder="Contoh: 081234567890"
                                class="w-full px-4 py-3 bg-gray-50 rounded-lg border border-gray-200">
                            <button type="button" onclick="removeItem(this)" class="px-3 text-red-500 hover:bg-red-50 rounded-lg">×</button>
                        </div>
                    @endforeach
                </div>

                {{-- Dinamis Email --}}
                <div id="wrapper-email">
                    <label class="block mb-2 text-sm font-semibold text-gray-700 flex justify-between">
                        Email
                        <button type="button" onclick="addItem('wrapper-email', 'email[]')" class="text-blue-600 text-xs hover:underline">+ Tambah Email</button>
                    </label>
                    @php $emails = explode('|', $data->email ?? ''); @endphp
                    @foreach($emails as $em)
                        <div class="flex gap-2 mb-2 item-row">
                            <input type="email" name="email[]" value="{{ $em }}" placeholder="admin@gmail.com"
                                class="w-full px-4 py-3 bg-gray-50 rounded-lg border border-gray-200">
                            <button type="button" onclick="removeItem(this)" class="px-3 text-red-500 hover:bg-red-50 rounded-lg">×</button>
                        </div>
                    @endforeach
                </div>

                {{-- Dinamis Alamat --}}
                <div id="wrapper-alamat">
                    <label class="block mb-2 text-sm font-semibold text-gray-700 flex justify-between">
                        Alamat
                        <button type="button" onclick="addItem('wrapper-alamat', 'alamat[]', true)" class="text-blue-600 text-xs hover:underline">+ Tambah Alamat</button>
                    </label>
                    @php $alamats = explode('|', $data->alamat ?? ''); @endphp
                    @foreach($alamats as $al)
                        <div class="flex gap-2 mb-2 item-row">
                            <textarea name="alamat[]" rows="2" class="w-full px-4 py-3 bg-gray-50 rounded-lg border border-gray-200">{{ $al }}</textarea>
                            <button type="button" onclick="removeItem(this)" class="px-3 text-red-500 hover:bg-red-50 rounded-lg">×</button>
                        </div>
                    @endforeach
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
                                    value="{{ old('senin_jumat_mulai', $jamSeninJumat['mulai']) }}"
                                    class="w-full px-4 py-3 bg-gray-50 rounded-lg border border-gray-200 focus:ring-2 focus:ring-[#0a4d3c] focus:outline-none">
                            </div>
                            <span class="text-gray-400">s/d</span>
                            <div class="flex-1">
                                <input type="time" name="senin_jumat_selesai"
                                    value="{{ old('senin_jumat_selesai', $jamSeninJumat['selesai']) }}"
                                    class="w-full px-4 py-3 bg-gray-50 rounded-lg border border-gray-200 focus:ring-2 focus:ring-[#0a4d3c] focus:outline-none">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Sabtu</label>
                        <div class="flex items-center gap-3">
                            <div class="flex-1">
                                <input type="time" name="sabtu_mulai"
                                    value="{{ old('sabtu_mulai', $jamSabtu['mulai']) }}"
                                    class="w-full px-4 py-3 bg-gray-50 rounded-lg border border-gray-200 focus:ring-2 focus:ring-[#0a4d3c] focus:outline-none">
                            </div>
                            <span class="text-gray-400">s/d</span>
                            <div class="flex-1">
                                <input type="time" name="sabtu_selesai"
                                    value="{{ old('sabtu_selesai', $jamSabtu['selesai']) }}"
                                    class="w-full px-4 py-3 bg-gray-50 rounded-lg border border-gray-200 focus:ring-2 focus:ring-[#0a4d3c] focus:outline-none">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Minggu</label>
                        <div class="flex items-center gap-3">
                            <div class="flex-1">
                                <input type="time" name="minggu_mulai"
                                    value="{{ old('minggu_mulai', $jamMinggu['mulai']) }}"
                                    class="w-full px-4 py-3 bg-gray-50 rounded-lg border border-gray-200 focus:ring-2 focus:ring-[#0a4d3c] focus:outline-none">
                            </div>
                            <span class="text-gray-400">s/d</span>
                            <div class="flex-1">
                                <input type="time" name="minggu_selesai"
                                    value="{{ old('minggu_selesai', $jamMinggu['selesai']) }}"
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


<script>
    function addItem(wrapperId, name, isTextArea = false) {
        const wrapper = document.getElementById(wrapperId);
        const div = document.createElement('div');
        div.className = 'flex gap-2 mb-2 item-row animate-fade-in';
        
        const input = isTextArea 
            ? `<textarea name="${name}" rows="2" class="w-full px-4 py-3 bg-gray-50 rounded-lg border border-gray-200"></textarea>`
            : `<input type="${name.includes('email') ? 'email' : 'text'}" name="${name}" class="w-full px-4 py-3 bg-gray-50 rounded-lg border border-gray-200">`;

        div.innerHTML = `
            ${input}
            <button type="button" onclick="removeItem(this)" class="px-3 text-red-500 hover:bg-red-50 rounded-lg">×</button>
        `;
        wrapper.appendChild(div);
    }

    function removeItem(btn) {
        const row = btn.closest('.item-row');
        if (row.parentNode.querySelectorAll('.item-row').length > 1) {
            row.remove();
        } else {
            alert('Minimal harus ada satu data.');
        }
    }
</script>
@endsection