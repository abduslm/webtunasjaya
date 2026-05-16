@extends('admin.adminLayout')

@section('content')
<div x-data="layananApp()" x-init="initData()" class="p-8">
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

    <div class="mb-8 flex items-center justify-between">
        <div>
            <h2 class="text-2xl text-gray-900 mb-1 font-bold">Kelola Layanan</h2>
            <p class="text-gray-500">Atur daftar layanan yang ditawarkan</p>
        </div>
        <div class="flex gap-3">
            <button @click="simpanSemua" class="px-8 py-4 bg-[#0a4d3c] text-white rounded-xl hover:bg-[#0a4d3c]/60 transition-all shadow-xl font-bold flex items-center gap-2">
                <i class="bi bi-save"></i> Simpan Semua Perubahan
            </button>
            <button @click="tambahLayanan" class="flex items-center gap-2 px-4 py-3 bg-white text-[#0a4d3c] border border-[#0a4d3c] rounded-lg hover:bg-gray-200 transition-colors">
                <i class="bi bi-plus-lg"></i> Tambah Layanan
            </button>
        </div>
    </div>

    <div class="space-y-6">
        <template x-for="(item, idx) in layananList" :key="idx">
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <div class="flex items-start justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-[#e8f5f1] text-[#0a4d3c] flex items-center justify-center font-bold" x-text="idx+1"></div>
                        <h3 class="text-gray-900 font-semibold text-lg" x-text="item.nama ? item.nama : 'Layanan Baru'"></h3>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-6">
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700">Nama Layanan</label>
                            <input name="judul" type="text" x-model="item.nama" @input="item.isDirty = true" required
                                class="w-full px-4 py-3 bg-gray-50 rounded-lg border border-gray-200 focus:ring-2 focus:ring-[#0a4d3c] focus:outline-none">
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700">Deskripsi Singkat</label>
                            <textarea name="desk_singkat" x-model="item.desk_singkat" rows="2" @input="item.isDirty = true" required
                                class="w-full px-4 py-3 bg-gray-50 rounded-lg border border-gray-200 focus:ring-2 focus:ring-[#0a4d3c] focus:outline-none"></textarea>
                        </div>
                    </div>

                    {{-- Upload Gambar --}}
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Gambar Layanan</label>
                        <input name="gambar" type="file" :id="'file-' + idx" class="hidden" accept="image/*" @change="uploadGambar($event, item, idx)">
                        
                        <div @click="item.id ? document.getElementById('file-' + idx).click() : alert('Simpan data terlebih dahulu')" 
                            class="relative border-2 border-dashed border-gray-300 rounded-lg p-4 hover:border-[#0a4d3c] transition-all cursor-pointer flex items-center justify-center min-h-[180px] bg-gray-50">
                            
                            <template x-if="item.gambar_url">
                                <img :src="item.gambar_url" class="max-h-40 w-full object-contain rounded-lg">
                            </template>

                            <template x-if="!item.gambar_url">
                                <div class="text-center">
                                    <i class="bi bi-cloud-upload text-3xl text-gray-400"></i>
                                    <p class="text-sm text-gray-500 mt-2">Pilih Gambar</p>
                                </div>
                            </template>

                            {{-- Loading Overlay --}}
                            <div x-show="item.uploading" class="absolute inset-0 bg-white/80 flex items-center justify-center rounded-lg">
                                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-[#0a4d3c]"></div>
                            </div>
                        </div>
                        <p x-show="!item.id" class="mt-2 text-xs text-amber-600 italic font-medium">
                            * Tombol upload akan aktif setelah layanan disimpan pertama kali.
                        </p>
                    </div>
                </div>

                {{-- Deskripsi Panjang (CKEditor) --}}
                <div class="mt-6" wire:ignore>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Deskripsi Lengkap</label>
                    <div>
                        <div x-init="setupCKEditor($el, idx)" 
                            class="ck-editor-container">
                            <textarea name="desk_panjang" id="editor" x-model="item.desk_panjang" required></textarea>
                        </div>
                    </div>
                </div>

                <div class="mt-5 pt-4 border-t border-gray-100 flex items-center justify-between">
                        {{-- Tombol Hapus (Kiri) --}}
                        <button @click="hapusLayanan(idx)" 
                            class="text-sm text-red-500 hover:text-red-700 transition-colors flex items-center gap-1">
                            <i class="bi bi-trash"></i> Hapus
                        </button>

                        {{-- Tombol Simpan Per Item (Kanan) --}}
                        <button @click="simpanSatuItem(idx)" 
                            :class="item.isDirty ? 'bg-[#0a4d3c] shadow-md' : 'bg-gray-400'"
                            class="flex items-center gap-2 px-4 py-2 text-white rounded-lg text-sm font-medium transition-all">
                            <i class="bi bi-check2-circle"></i>
                            <span x-text="item.id ? 'Update' : 'Simpan'"></span>
                        </button>
                    </div>
            </div>
        </template>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>
<script>
    function layananApp() {
        let ckInstances = {};
        return {
            layananList: [],

            initData() {
                let data = @json($layananList);
                this.layananList = data.length > 0 ? data.map(i => ({...i, uploading: false})) : [];
            },

            setupCKEditor(element, idx) {
                const textarea = element.querySelector('textarea');
                if (!textarea) return;

                ClassicEditor.create(textarea, {
                    toolbar: ['undo', 'redo','|','heading', '|', 'bold', 'italic', '|', 'bulletedList', 'numberedList', 'blockQuote','|','link', 'insertTable'],
                })
                .then(editor => {
                    ckInstances[idx] = editor;
                    editor.setData(this.layananList[idx].desk_panjang || '');
                    editor.model.document.on('change:data', () => {
                        this.layananList[idx].desk_panjang = editor.getData();
                        this.layananList[idx].isDirty = true;
                    });
                })
                .catch(error => {
                    console.error('Error CKEditor:', error);
                });
            },

            tambahLayanan() {
                this.layananList.push({
                    id: null,
                    nama: '',
                    desk_singkat: '',
                    desk_panjang: '',
                    gambar_url: null,
                    uploading: false,
                    isDirty: true
                });
                this.$nextTick(() => {
                    const kartuLayanan = document.querySelectorAll('.space-y-6 > .bg-white');
                    const kartuTerbaru = kartuLayanan[kartuLayanan.length - 1];
                    
                    if (kartuTerbaru) {
                        kartuTerbaru.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            },

            async uploadGambar(event, item, idx) {
                const file = event.target.files[0];
                if (!file || !item.id) return;

                item.uploading = true;
                let formData = new FormData();
                formData.append('gambar', file);

                try {
                    const response = await fetch( '{{ route('admin.layanan.uploadGambar', ':id') }}'.replace(':id', item.id) , {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: formData
                    });
                    
                    const result = await response.json();
                    if (result.success) {
                        this.layananList[idx].gambar_url = result.url;
                        this.layananList[idx].isDirty = false;
                        alert('Upload Gambar Berhasil!');
                    } else {
                        alert(result.message);
                    }
                } catch (error) {
                    alert('Terjadi Kesalahan');
                } finally {
                    item.uploading = false;
                }
            },

            async simpanSatuItem(idx) {
                const item = this.layananList[idx];
                try {
                    const res = await fetch('{{ route("admin.layanan.store_single") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(item)
                    });
                    const data = await res.json();
                    if(data.success) {
                        this.layananList[idx].id = data.new_id; 
                        this.layananList[idx].isDirty = false;
                        alert('Item berhasil disimpan');
                    }
                } catch (e) { alert('Gagal'); }
            },
            async simpanSemua() {
                try {
                    this.layananList.forEach((item, idx) => {
                        if (ckInstances[idx]) {
                            item.desk_panjang = ckInstances[idx].getData();
                        }
                    });

                    const response = await fetch('{{ route("admin.layanan.store") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ layanan: this.layananList })
                    });

                    const result = await response.json();
                    if (response.ok && result.success) {
                        alert('Data berhasil disimpan!');
                        window.location.reload();
                    } else {
                        alert('Gagal menyimpan: ' + (result.message || 'Cek server log'));
                    }

                } catch (err) {
                    console.error("Kesalahan fatal:", err);
                    alert('Terjadi kesalahan sistem. Cek konsol browser.');
                }
            },
            hapusLayanan(index) {
                if (confirm('Hapus layanan ini?')) {
                    if (ckInstances[index]) {
                        ckInstances[index].destroy();
                        delete ckInstances[index];
                    }
                    this.layananList.splice(index, 1);
                }
            }
        }
    }
</script>
@endpush