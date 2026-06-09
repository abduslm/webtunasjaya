@extends('admin.adminLayout')

@push('styles')
<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
    .input-custom { 
        background-color: #f1f5f9; 
        border: 1px solid transparent; 
        border-radius: 0.75rem; 
        font-size: 0.875rem; 
        padding: 0.75rem 1rem; 
        transition: all 0.2s;
    }
    .input-custom:focus { 
        background-color: #fff;
        border-color: #0055d4;
        box-shadow: 0 0 0 4px rgba(0, 85, 212, 0.1);
        outline: none; 
    }
    select.input-custom {
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%2364748b' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 0.75rem center;
        background-repeat: no-repeat;
        background-size: 1.2rem;
        padding-right: 2.5rem;
    }
    .badge-masuk { background-color: #dcfce7; color: #15803d; padding: 4px 12px; border-radius: 99px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
    .badge-keluar { background-color: #fee2e2; color: #b91c1c; padding: 4px 12px; border-radius: 99px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
</style>
@endpush

@section('content')
<div class="p-6 md:p-10 max-w-7xl mx-auto">
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-[#0f172a]">Log Aktivitas Inventaris</h1>
            <p class="text-gray-500 mt-1">Pantau seluruh log keluar masuk barang secara real-time.</p>
        </div>
        <div class="flex gap-3 w-full sm:w-auto">
            <button @click="exportLogData" class="flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors shadow-sm text-sm font-medium">
                <i class="bi bi-download"></i>
                <span>Export Excel</span>
            </button>
        </div>
    </div>


    <form action="{{ route('admin.aktivitas.index') }}" method="GET" class="mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end bg-slate-50 p-4 rounded-2xl border border-slate-100">
            
            <div class="flex flex-col">
                <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2">Filter Status</label>
                <select name="status" class="input-custom w-full cursor-pointer">
                    <option value="">Semua Status</option>
                    <option value="masuk" {{ request('status') == 'masuk' ? 'selected' : '' }}>Barang Masuk</option>
                    <option value="keluar" {{ request('status') == 'keluar' ? 'selected' : '' }}>Barang Keluar</option>
                </select>
            </div>

            <div class="flex flex-col">
                <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2">Filter Nama Barang</label>
                <select name="id_barang" class="input-custom w-full cursor-pointer">
                    <option value="">Semua Barang</option>
                    @foreach($daftarBarangFilter as $b)
                        <option value="{{ $b->id_barang }}" {{ request('id_barang') == $b->id_barang ? 'selected' : '' }}>
                            {{ $b->nama_barang }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-col">
                <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2">Filter Tujuan / Pengirim</label>
                <select name="tujuan" class="input-custom w-full cursor-pointer">
                    <option value="">Semua Tujuan/Pengirim</option>
                    @foreach($daftarTujuanFilter as $tujuan)
                        <option value="{{ $tujuan }}" {{ request('tujuan') == $tujuan ? 'selected' : '' }}>
                            {{ $tujuan }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-2 w-full">
                <button type="submit" class="flex-1 bg-[#003D9B] hover:bg-[#00358a] text-white font-bold py-3 px-4 rounded-xl transition-all flex items-center justify-center gap-2 shadow-sm text-sm">
                    <i class="fa-solid fa-filter"></i> Filter
                </button>
                
                @if(request()->has('status') || request()->has('id_barang') || request()->has('tujuan'))
                    <a href="{{ route('admin.aktivitas.index') }}" class="bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold py-3 px-4 rounded-xl transition-all flex items-center justify-center text-sm" title="Clear Filter">
                        <i class="fa-solid fa-filter-circle-xmark"></i>
                    </a>
                @endif
            </div>

        </div>
    </form>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-5">
        <div class="p-6 flex justify-between items-center border-b border-gray-50">
            <h2 class="text-xl font-bold text-[#1e293b]">Histori Mutasi Terbaru</h2>
            <span class="text-[11px] font-bold bg-slate-100 text-slate-600 px-3 py-1 rounded-full uppercase tracking-wider">
                Total: {{ $aktivitas->total() }} Log
            </span>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#f8faff] text-[11px] font-bold text-gray-500 uppercase tracking-widest border-b border-gray-50">
                        <th class="px-6 py-4 text-center w-16">No</th>
                        <th class="px-6 py-4">Nama Barang</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Stok Awal</th>
                        <th class="px-6 py-4 text-center">Jumlah</th>
                        <th class="px-6 py-4 text-center">Stok Akhir</th>
                        <th class="px-6 py-4">Tujuan / Pengirim</th>
                        <th class="px-6 py-4">Catatan</th>
                        <th class="px-6 py-4 text-center">Terakhir Diubah</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($aktivitas as $index => $log)
                    <tr class="hover:bg-slate-50/50 transition-colors" id="row-{{ $log->id_aktivitas }}">
                        <td class="px-6 py-4 text-center text-sm font-medium text-gray-500">{{ $aktivitas->firstItem() + $index }}</td>
                        <td class="px-6 py-4">
                            <span class="font-bold text-[#1e293b] block">{{ $log->barang->nama_barang ?? 'Barang Terhapus' }}</span>
                            <span class="text-[10px] px-2 py-0.5 bg-gray-100 text-gray-500 rounded font-mono">ID-{{ $log->id_barang }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="{{ $log->status == 'masuk' ? 'badge-masuk' : 'badge-keluar' }}">{{ $log->status }}</span>
                        </td>
                        <td class="px-6 py-4 text-center font-medium text-slate-600 bg-slate-50/30">
                            {{ $log->stok_awal ?? ($log->sisa_stok - $log->jumlah) }}
                        </td>
                        <td class="px-6 py-4 text-center font-bold {{ $log->status == 'masuk' ? 'text-emerald-600' : 'text-red-600' }}">
                            {{ $log->status == 'masuk' ? '+' : '-' }}{{ $log->jumlah }}
                        </td>
                        <td class="px-6 py-4 text-center font-semibold text-slate-700 bg-slate-50/50">{{ $log->sisa_stok }}</td>
                        <td class="px-6 py-4 text-sm text-slate-800 font-medium">{{ $log->tujuan }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600 truncate max-w-[150px]">{{ $log->catatan ?? '-' }}</td>
                        <td class="px-6 py-4 text-center whitespace-nowrap text-xs text-slate-500">
                            <div class="flex items-center justify-center gap-1.5">
                                <span>{{ $log->updated_at->translatedFormat('d M Y, H:i') }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-1">
                                @if(in_array($log->id_aktivitas, $logTerakhirPerBarang))
                                    <button type="button" 
                                            onclick="openEditModal('{{ $log->id_aktivitas }}', '{{ $log->barang->nama_barang }}', '{{ $log->status }}', '{{ $log->jumlah }}', '{{ $log->tujuan }}', '{{ $log->catatan }}')" 
                                            class="text-blue-600 hover:text-blue-900 font-semibold bg-blue-50 px-3 py-1.5 rounded-lg transition-all">
                                        <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                                    </button>
                                @else
                                    <button type="button" 
                                            disabled 
                                            class="text-slate-400 bg-slate-100 px-3 py-1.5 rounded-lg cursor-not-allowed inline-flex items-center gap-1" 
                                            title="Hanya riwayat terbaru yang dapat diubah untuk menjaga validasi stok">
                                        <i class="fa-solid fa-lock text-[11px]"></i> Terkunci
                                    </button>
                                @endif
                                <form action="{{ route('admin.aktivitas.destroy', $log->id_aktivitas) }}" method="POST" id="delete-form-{{ $log->id_aktivitas }}" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="button" onclick="confirmDelete('{{ $log->id_aktivitas }}')" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-6 py-16 text-center text-gray-400">Belum ada riwayat aktivitas log inventaris.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4">
        {{ $aktivitas->links() }}
    </div>
</div>

<div id="editModal" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-md rounded-[2rem] shadow-2xl overflow-hidden transform transition-all">
        <div class="bg-[#f8faff] p-6 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-xl font-bold text-[#1e293b]">Edit Log Aktivitas</h3>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark text-xl"></i></button>
        </div>
        
        <form id="formEditActivity" action="" method="POST" class="p-8 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Nama Barang</label>
                <input type="text" id="edit_nama" class="input-custom w-full bg-gray-100 text-slate-500" readonly>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Jumlah Kuantitas</label>
                    <input type="number" name="jumlah" id="edit_jumlah" class="input-custom w-full" min="1" required>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Status (Kunci)</label>
                    <input type="text" name="status" id="edit_status" class="input-custom w-full bg-gray-100 text-slate-500" readonly>
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Tujuan / Pengirim</label>
                <input type="text" name="tujuan" id="edit_tujuan" class="input-custom w-full" required>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Catatan</label>
                <textarea name="catatan" id="edit_catatan" class="input-custom w-full h-20 resize-none"></textarea>
            </div>

            <div class="flex flex-row gap-3 pt-4 border-t border-gray-100">
                <button type="button" onclick="closeEditModal()" class="flex-1 px-6 py-3 border border-gray-200 text-gray-600 font-bold rounded-2xl hover:bg-gray-50 transition-all text-sm">Batal</button>
                <button type="submit" class="flex-1 px-6 py-3 bg-[#0055d4] text-white font-bold rounded-2xl shadow-lg shadow-blue-200 hover:bg-blue-700 transition-all text-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openEditModal(id, nama, status, jumlah, tujuan, catatan) {
        let urlAction = "{{ route('admin.aktivitas.update', ['aktivitas' => '___ID___']) }}";
        document.getElementById('formEditActivity').action = urlAction.replace('___ID___', id);
        document.getElementById('edit_nama').value = nama;
        document.getElementById('edit_status').value = status;
        document.getElementById('edit_jumlah').value = jumlah;
        document.getElementById('edit_tujuan').value = tujuan;
        document.getElementById('edit_catatan').value = catatan;
        document.getElementById('editModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }

    function confirmDelete(id) {
        Swal.fire({
            title: 'Hapus log aktivitas ini?',
            text: "Penghapusan akan merollback otomatis kuantitas stok pada master barang!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#b91c1c',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus Rollback!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        })
    }

    @if(session('success'))
        Swal.fire({ icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}", showConfirmButton: false, timer: 2500 });
    @endif
    @if($errors->any())
        Swal.fire({ icon: 'error', title: 'Gagal!', text: "{{ $errors->first() }}", showConfirmButton: true });
    @endif

    function exportLogData() {
        const currentParams = new URLSearchParams(window.location.search).toString();
        window.location.href = `{{ route('admin.aktivitas.export') }}?${currentParams}`;
    }
</script>
@endpush