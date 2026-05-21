@extends('admin.adminLayout')

@section('content')
<div x-data="layananApp()" x-init="initData()" class="p-8">
    {{-- Pesan Success --}}
    @if(session('success'))
        <div class="p-4 mb-4 bg-green-50 text-[#0a4d3c] rounded-xl border border-green-100 flex items-center gap-3 animate-fade-in">
            <i class="bi bi-check-circle-fill"></i> 
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl text-gray-900 mb-1 font-bold">Kelola Layanan</h2>
            <p class="text-gray-500">Atur daftar layanan yang ditawarkan</p>
        </div>
        
        <div class="flex flex-wrap items-center gap-3">
  
        <button @click="simpanSemua" class="px-6 py-2 bg-[#0a4d3c] text-white rounded-lg hover:bg-[#0a4d3c]/80 transition-all shadow-md font-bold flex items-center gap-2 text-sm">
                <i class="bi bi-save"></i> Simpan Semua
            </button>
            <button @click="tambahLayanan" class="flex items-center gap-2 px-4 py-2 bg-white text-[#0a4d3c] border border-[#0a4d3c] rounded-lg hover:bg-gray-100 transition-colors text-sm font-semibold">
                <i class="bi bi-plus-lg"></i> Tambah Layanan
            </button>
        </div>
    </div>

    

    <div class="space-y-6">
        {{-- Menggunakan layananList secara langsung --}}
        <template x-for="(item, idx) in layananList" :key="item.uid">
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
                            <input type="text" x-model="item.nama" @input="item.isDirty = true" required
                                class="w-full px-4 py-3 bg-gray-50 rounded-lg border border-gray-200 focus:ring-2 focus:ring-[#0a4d3c] focus:outline-none">
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700">Deskripsi Singkat</label>
                            <textarea x-model="item.desk_singkat" rows="2" @input="item.isDirty = true" required
                                class="w-full px-4 py-3 bg-gray-50 rounded-lg border border-gray-200 focus:ring-2 focus:ring-[#0a4d3c] focus:outline-none"></textarea>
                        </div>
                    </div>

                    {{-- Upload Gambar --}}
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Gambar Layanan</label>
                        <input type="file" :id="'file-' + item.uid" class="hidden" accept="image/*" @change="uploadGambar($event, item)">
                        
                        <div @click="item.id ? document.getElementById('file-' + item.uid).click() : alert('Simpan data terlebih dahulu')" 
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

                            <div x-show="item.uploading" class="absolute inset-0 bg-white/80 flex items-center justify-center rounded-lg">
                                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-[#0a4d3c]"></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Deskripsi Panjang --}}
                <div class="mt-6">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Deskripsi Lengkap</label>
                    <div wire:ignore>
                        <div x-init="setupCKEditor($el, item)" class="ck-editor-container">
                            <textarea x-model="item.desk_panjang"></textarea>
                        </div>
                    </div>
                </div>

                <div class="mt-5 pt-4 border-t border-gray-100 flex items-center justify-between">
                    <button @click="hapusLayanan(item.uid)" class="text-sm text-red-500 hover:text-red-700 transition-colors flex items-center gap-1">
                        <i class="bi bi-trash"></i> Hapus
                    </button>

                    <button @click="simpanSatuItem(item)" 
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
 
                this.layananList = data.map(i => ({
                    ...i, 
                    uid: i.id || Math.random().toString(36).substr(2, 9),
                    uploading: false,
                    isDirty: false
                }));
            },

            setupCKEditor(element, item) {
                const textarea = element.querySelector('textarea');
                ClassicEditor.create(textarea, {
                    toolbar: ['undo', 'redo','|','heading', '|', 'bold', 'italic', '|', 'bulletedList', 'numberedList', 'blockQuote','|','link', 'insertTable'],
                })
                .then(editor => {
                    ckInstances[item.uid] = editor;
                    editor.setData(item.desk_panjang || '');
                    editor.model.document.on('change:data', () => {
                        item.desk_panjang = editor.getData();
                        item.isDirty = true;
                    });
                });
            },

            tambahLayanan() {
                const newUid = Math.random().toString(36).substr(2, 9);
                this.layananList.push({
                    id: null,
                    uid: newUid,
                    nama: '',
                    desk_singkat: '',
                    desk_panjang: '',
                    gambar_url: null,
                    uploading: false,
                    isDirty: true
                });
  
            },

            async uploadGambar(event, item) {
                const file = event.target.files[0];
                if (!file || !item.id) return;
                item.uploading = true;
                let formData = new FormData();
                formData.append('gambar', file);
                try {
                    const response = await fetch('{{ route('admin.layanan.uploadGambar', ':id') }}'.replace(':id', item.id), {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: formData
                    });
                    const result = await response.json();
                    if (result.success) {
                        item.gambar_url = result.url;
                        alert('Gambar berhasil diupload');
                    }
                } catch (error) { alert('Gagal upload'); }
                finally { item.uploading = false; }
            },

            async simpanSatuItem(item) {
                try {
                    const res = await fetch('{{ route("admin.layanan.store_single") }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify(item)
                    });
                    const data = await res.json();
                    if(data.success) {
                        item.id = data.new_id;
                        item.isDirty = false;
                        alert('Tersimpan');
                    }
                } catch (e) { alert('Gagal'); }
            },

            async simpanSemua() {
 
            this.layananList.forEach(item => {
                    if (ckInstances[item.uid]) {
                        item.desk_panjang = ckInstances[item.uid].getData();
                    }
                });

                try {
                    const response = await fetch('{{ route("admin.layanan.store") }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ layanan: this.layananList })
                    });
                    if (response.ok) {
                        alert('Semua perubahan disimpan!');
                        window.location.reload();
                    }
                } catch (err) { alert('Kesalahan sistem'); }
            },

            hapusLayanan(uid) {
                if (confirm('Hapus layanan ini?')) {
                    const index = this.layananList.findIndex(i => i.uid === uid);
                    if (index !== -1) {
                        if (ckInstances[uid]) {
                            ckInstances[uid].destroy();
                            delete ckInstances[uid];
                        }
                        this.layananList.splice(index, 1);
                    }
                }
            }
        }
    }
</script>

@endpush
