{{-- resources/views/admin/front_pages/portofolio.blade.php --}}
@extends('admin.adminLayout')

@section('content')
<div x-data="portofolioApp()" x-init="initData()" class="p-8">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h2 class="text-2xl text-gray-900 mb-1">Kelola Portofolio</h2>
            <p class="text-gray-500">Showcase proyek yang telah dikerjakan</p>
        </div>
        <button @click="tambahPortfolio" class="flex items-center gap-2 px-4 py-3 bg-[#0a4d3c] text-white rounded-lg hover:bg-[#0a4d3c]/90 transition-colors">
            <i class="bi bi-plus-lg"></i>
            Tambah Proyek
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <template x-for="(item, idx) in portfolioList" :key="idx">
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <!-- Upload Gambar (Placeholder) -->
                <div class="aspect-video bg-[#fafbfc] border-b border-gray-200">
                    <div class="h-full flex flex-col items-center justify-center p-6">
                        <div class="w-16 h-16 mb-3 rounded-full bg-[#e8f5f1] flex items-center justify-center">
                            <i class="bi bi-cloud-upload text-[#0a4d3c] text-2xl"></i>
                        </div>
                        <p class="text-sm text-gray-900 mb-1">Upload Gambar Proyek</p>
                        <p class="text-xs text-gray-500">PNG, JPG hingga 5MB</p>
                        <button class="mt-3 px-4 py-2 bg-[#0a4d3c] text-white rounded-lg hover:bg-[#0a4d3c]/90 transition-colors text-sm">
                            Pilih File
                        </button>
                    </div>
                </div>

                <div class="p-6">
                    <div class="space-y-4">
                        <!-- Klien -->
                        <div>
                            <label class="block mb-2 text-sm text-gray-500">Klien</label>
                            <input type="text" 
                                x-model="item.klien" 
                                @input="updatePortfolio(idx, 'klien', item.klien)"
                                class="w-full px-4 py-2 bg-gray-50 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#0a4d3c] focus:border-transparent"
                                placeholder="Nama klien">
                        </div>

                        <!-- Deskripsi Singkat -->
                        <div>
                            <label class="block mb-2 text-sm text-gray-500">Deskripsi Singkat</label>
                            <textarea rows="3" 
                                    x-model="item.deskripsiSingkat" 
                                    @input="updatePortfolio(idx, 'deskripsiSingkat', item.deskripsiSingkat)"
                                    class="w-full px-4 py-2 bg-gray-50 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#0a4d3c] focus:border-transparent resize-none"
                                    placeholder="Deskripsi singkat tentang proyek"></textarea>
                        </div>

                        <!-- Actions -->
                        <div class="pt-2 flex gap-2">
                            <button @click="simpanPortfolio(idx)" class="flex-1 px-4 py-2 bg-[#0a4d3c] text-white rounded-lg hover:bg-[#0a4d3c]/90 transition-colors">
                                Simpan
                            </button>
                            <button @click="hapusPortfolio(idx)" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <div x-show="portfolioList.length === 0" class="col-span-full bg-white rounded-xl border border-gray-200 p-12 text-center">
            <p class="text-gray-500">Belum ada portfolio. Klik "Tambah Proyek" untuk menambahkan.</p>
        </div>
    </div>
</div>


@php
$defaultPortfolio = [
    [
        'klien' => 'PT. ABC Indonesia',
        'deskripsiSingkat' => 'Pembersihan rutin gedung perkantoran 15 lantai dengan 200+ karyawan'
    ],
    [
        'klien' => 'RS XYZ',
        'deskripsiSingkat' => 'Sanitasi dan disinfeksi area rumah sakit sesuai standar kesehatan'
    ],
    [
        'klien' => 'PT. Metropolitan',
        'deskripsiSingkat' => 'Deep cleaning dan maintenance mall dengan area 10.000 m²'
    ]
];
@endphp
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
    function portofolioApp() {
        return {
            portfolioList: [],
            initData() {
                this.portfolioList = @json($portfolioList ?? $defaultPortfolio);
            },
            tambahPortfolio() {
                this.portfolioList.push({
                    klien: '',
                    deskripsiSingkat: ''
                });
            },
            hapusPortfolio(index) {
                if (confirm('Hapus proyek ini?')) {
                    this.portfolioList.splice(index, 1);
                    // Opsional: langsung simpan perubahan ke server
                    this.simpanSemua();
                }
            },
            updatePortfolio(index, field, value) {
                // Auto-save dengan debounce
                clearTimeout(this.saveTimeout);
                this.saveTimeout = setTimeout(() => {
                    this.simpanSemua();
                }, 1000);
            },
            simpanPortfolio(index) {
                // Simpan satu item (bisa juga via fetch)
                const item = this.portfolioList[index];
                fetch('{{ route("admin.portofolio") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ index: index, data: item })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) alert('Data tersimpan');
                    else alert('Gagal menyimpan');
                })
                .catch(err => console.error(err));
            },
            simpanSemua() {
                // Simpan seluruh list ke server
                fetch('{{ route("admin.portofolio") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ list: this.portfolioList })
                })
                .then(res => res.json())
                .then(data => {
                    if (!data.success) console.error('Gagal sinkronisasi');
                })
                .catch(err => console.error(err));
            }
        }
    }
</script>
@endpush

@endsection