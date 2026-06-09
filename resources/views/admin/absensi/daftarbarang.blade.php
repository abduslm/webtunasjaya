@extends('admin.adminLayout')

@push('styles')
<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
    .input-custom { 
        background-color: #f1f5f9; 
        border: 1px solid transparent; 
        border-radius: 1rem; 
        font-size: 0.875rem; 
        padding: 1rem 1.25rem; 
        transition: all 0.2s;
    }
    .input-custom:focus { 
        background-color: #fff;
        border-color: #003D9B;
        box-shadow: 0 0 0 4px rgba(0, 61, 155, 0.1);
        outline: none; 
    }
    select.input-custom {
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%2364748b' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 1rem center;
        background-repeat: no-repeat;
        background-size: 1.2rem;
        padding-right: 2.5rem;
    }
</style>
@endpush

@section('content')
<div class="max-w-6xl mx-auto py-10 px-6">
    <div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Master Daftar Barang</h1>
            <p class="text-slate-500 mt-1">Kelola list inventaris properti dan peralatan gudang utama.</p>
        </div>
        <button onclick="toggleBarangModal(false)" class="flex items-center gap-2 px-5 py-3 bg-[#003D9B] hover:bg-[#002d72] text-white rounded-xl font-bold text-sm shadow-md transition-all active:scale-95">
            <i class="fa-solid fa-plus"></i>
            <span>Tambah Barang Baru</span>
        </button>
    </div>

    <div class="bg-white rounded-[24px] border border-slate-100 shadow-sm p-4 sm:p-6 md:p-8 mb-10">
        <h2 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
            <i class="fa-solid fa-right-left text-[#003D9B]"></i> Pencatatan Log Inventaris
        </h2>

        <form action="{{ route('admin.aktivitas.store') }}" method="POST">
            @csrf
            
            <div class="flex flex-col sm:flex-row bg-slate-100 p-1.5 rounded-xl mb-8 border border-slate-200 w-full sm:inline-flex gap-1 sm:gap-0">
                <button type="button" id="btn-tab-masuk" onclick="setStatusTransaksi('masuk')" class="flex items-center justify-center gap-2 px-6 py-2.5 bg-[#003D9B] text-white rounded-lg font-bold text-sm shadow-sm transition-all w-full sm:w-auto">
                    <i class="fa-solid fa-arrow-right-to-bracket text-xs"></i>
                    <span>Barang Masuk</span>
                </button>
                <button type="button" id="btn-tab-keluar" onclick="setStatusTransaksi('keluar')" class="flex items-center justify-center gap-2 px-6 py-2.5 text-slate-600 hover:text-slate-900 rounded-lg font-bold text-sm transition-all w-full sm:w-auto">
                    <i class="fa-solid fa-arrow-right-from-bracket text-xs"></i>
                    <span>Barang Keluar</span>
                </button>
            </div>
            <input type="hidden" name="status" id="tx-status" value="masuk">

            <div class="grid grid-cols-1 sm:grid-cols-12 gap-5 sm:gap-6">
                
                <div class="sm:col-span-7 lg:col-span-6 flex flex-col">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2">Pilih Barang</label>
                    <select name="id_barang" class="input-custom w-full cursor-pointer" required>
                        <option value="">Pilih item dari daftar...</option>
                        @foreach($barang as $item)
                            <option value="{{ $item->id_barang }}">{{ $item->nama_barang }} (Stok: {{ $item->stok }} {{ $item->satuan }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:col-span-5 lg:col-span-3 flex flex-col">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2 text-center sm:text-left md:text-center">Jumlah</label>
                    <div class="flex items-center bg-slate-100 rounded-2xl px-3 border border-transparent focus-within:border-[#003D9B] focus-within:bg-white transition-all w-full">
                        <button type="button" onclick="stepDown()" class="p-2 text-slate-400 hover:text-slate-600"><i class="fa-solid fa-minus"></i></button>
                        <input type="number" name="jumlah" id="tx-jumlah" value="1" min="1" class="w-full text-center py-3.5 bg-transparent border-none text-xl font-bold text-slate-800 focus:outline-none" required />
                        <button type="button" onclick="stepUp()" class="p-2 text-slate-400 hover:text-slate-600"><i class="fa-solid fa-plus"></i></button>
                    </div>
                </div>

                <div class="sm:col-span-12 lg:col-span-3 flex flex-col" id="container-tx-tujuan">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2">Tujuan / Pengirim</label>
                    <input type="text" name="tujuan" id="tx-tujuan" placeholder="Masukkan nama unit / supplier" class="input-custom w-full" required />
                </div>

                <div class="sm:col-span-12 flex flex-col">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2">Catatan Tambahan</label>
                    <input type="text" name="catatan" placeholder="Ketik keterangan pelengkap transaksi di sini..." class="input-custom w-full" />
                </div>
            </div>

            <button type="submit" class="w-full mt-10 bg-[#003D9B] hover:bg-[#00358a] text-white flex items-center justify-center gap-3 py-4 rounded-2xl font-bold text-base shadow-lg shadow-[#003D9B]/10 transition-all active:scale-[0.99]">
                <i class="fa-solid fa-floppy-disk"></i>
                <span>Simpan Transaksi Inventaris</span> 
            </button>
        </form>
    </div>

    <div class="bg-white rounded-[24px] border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="text-xl font-bold text-slate-900">Stok Real-Time Terkini</h2>
            
            <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                <select id="filter-kategori" onchange="filterTableBarang()" class="input-custom py-2.5 px-4 text-xs font-semibold cursor-pointer min-w-[150px]">
                    <option value="">Semua Kategori</option>
                    @foreach($barang->pluck('kategori')->unique() as $kat)
                        <option value="{{ strtolower($kat) }}">{{ $kat }}</option>
                    @endforeach
                </select>

                <div class="relative w-full sm:w-64">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input type="text" id="search-barang" onkeyup="filterTableBarang()" placeholder="Cari nama barang..." class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-[#003D9B] transition-all">
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-sm text-left" id="table-barang">
                <thead>
                    <tr class="bg-slate-50 border-y border-slate-100 text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                        <th class="px-8 py-4 text-center w-16">No</th>
                        <th class="px-8 py-4">Nama Barang</th>
                        <th class="px-8 py-4">Kategori</th>
                        <th class="px-8 py-4 text-center">Stok Akhir</th>
                        <th class="px-8 py-4 text-center">Satuan</th>
                        <th class="px-8 py-4 text-center">Perubahan Terakhir</th>
                        <th class="px-8 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($barang as $index => $item)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-8 py-4 text-center text-slate-500">{{ $index + 1 }}</td>
                        <td class="px-8 py-4 font-bold text-slate-800 target-nama">{{ $item->nama_barang }}</td>
                        <td class="px-8 py-4 text-slate-600"><span class="bg-slate-100 px-2.5 py-1 rounded-md text-xs font-medium target-kategori">{{ $item->kategori }}</span></td>
                        <td class="px-8 py-4 text-center font-bold text-slate-900">
                            <span class="{{ $item->stok <= 5 ? 'text-red-600 bg-red-50' : 'text-emerald-600 bg-emerald-50' }} px-3 py-1 rounded-full text-xs">
                                {{ $item->stok }}
                            </span>
                        </td>
                        <td class="px-8 py-4 text-center text-slate-500 font-medium">{{ $item->satuan }}</td>
                        <td class="px-8 py-4 text-center whitespace-nowrap text-xs text-slate-500">
                            <div class="flex items-center justify-center gap-1.5">
                                <span>{{ $item->updated_at->translatedFormat('d M Y, H:i') }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <button onclick="editBarangMaster('{{ $item->id_barang }}', '{{ $item->nama_barang }}', '{{ $item->kategori }}', '{{ $item->satuan }}')" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <form action="{{ route('admin.barang.destroy', $item->id_barang) }}" method="POST" onsubmit="return confirm('Hapus barang ini beserta seluruh history aktivitasnya?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-8 py-20 text-center">
                            <p class="text-slate-400 font-medium text-base">Belum ada master data barang tersedia</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="barangModal" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-md rounded-[24px] shadow-2xl overflow-hidden transform transition-all">
        <div class="bg-slate-50 p-6 border-b border-slate-100 flex justify-between items-center">
            <h3 id="modal-title" class="text-lg font-bold text-slate-800">Tambah Barang Baru</h3>
            <button onclick="toggleBarangModal(true)" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>
        
        <form id="formBarang" action="{{ route('admin.barang.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="_method" id="form-method" value="POST">
            
            <div>
                <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2">Nama Barang</label>
                <input type="text" name="nama_barang" id="input-nama" class="input-custom w-full" placeholder="Contoh: Sapu Ijuk Super" required>
            </div>
            <div>
                <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2">Kategori</label>
                <input type="text" name="kategori" id="input-kategori" class="input-custom w-full" placeholder="Contoh: Alat Kebersihan" required>
            </div>
            <div>
                <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2">Satuan Ukur</label>
                <input type="text" name="satuan" id="input-satuan" class="input-custom w-full" placeholder="Contoh: Pcs / Box / Liter" required>
            </div>

            <div class="flex gap-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="toggleBarangModal(true)" class="flex-1 px-5 py-3 border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-50 transition-all text-sm">Batal</button>
                <button type="submit" class="flex-1 px-5 py-3 bg-[#003D9B] text-white font-bold rounded-xl hover:bg-blue-800 transition-all text-sm shadow-md">Simpan Data</button>
            </div>
        </form>
    </div>
</div>
@endsection


@push('scripts')
<script>
    function setStatusTransaksi(status) {
        document.getElementById('tx-status').value = status;
        const btnMasuk = document.getElementById('btn-tab-masuk');
        const btnKeluar = document.getElementById('btn-tab-keluar');
        const tujuanInput = document.getElementById('tx-tujuan');

        const activeClasses = ['bg-[#003D9B]', 'text-white', 'shadow-sm'];
        const inactiveClasses = ['text-slate-600', 'hover:text-slate-900'];

        if(status === 'masuk') {
            btnMasuk.classList.add(...activeClasses);
            btnMasuk.classList.remove(...inactiveClasses);

            btnKeluar.classList.remove(...activeClasses);
            btnKeluar.classList.add(...inactiveClasses);
            tujuanInput.placeholder = "Masukkan nama supplier / unit pengirim";
        } else {
            btnKeluar.classList.add(...activeClasses);
            btnKeluar.classList.remove(...inactiveClasses);

            btnMasuk.classList.remove(...activeClasses);
            btnMasuk.classList.add(...inactiveClasses);
            tujuanInput.placeholder = "Masukkan nama instansi / wilayah tujuan";
        }
    }

    function stepUp() { document.getElementById('tx-jumlah').stepUp(); }
    function stepDown() { document.getElementById('tx-jumlah').stepDown(); }

    function toggleBarangModal(hide) {
        const modal = document.getElementById('barangModal');
        if(hide) {
            modal.classList.add('hidden');
        } else {
            document.getElementById('formBarang').reset();
            document.getElementById('modal-title').innerText = "Tambah Barang Baru";
            document.getElementById('form-method').value = "POST";
            document.getElementById('formBarang').action = "{{ route('admin.barang.store') }}";
            modal.classList.remove('hidden');
        }
    }

    function editBarangMaster(id, nama, kategori, satuan) {
        toggleBarangModal(false);
        document.getElementById('modal-title').innerText = "Ubah Data Master Barang";
        document.getElementById('form-method').value = "PUT";
        
        let urlAction = "{{ route('admin.barang.update', ['barang' => '___ID___']) }}";
        document.getElementById('formBarang').action = urlAction.replace('___ID___', id);
        document.getElementById('input-nama').value = nama;
        document.getElementById('input-kategori').value = kategori;
        document.getElementById('input-satuan').value = satuan;
    }

    function filterTableBarang() {
        let searchInput = document.getElementById("search-barang").value.toLowerCase();
        let kategoriInput = document.getElementById("filter-kategori").value;
        let rows = document.querySelectorAll("#table-barang tbody tr");
        
        rows.forEach(row => {
            let nameTxt = row.querySelector(".target-nama") ? row.querySelector(".target-nama").textContent.toLowerCase() : '';
            let katTxt = row.querySelector(".target-kategori") ? row.querySelector(".target-kategori").textContent.toLowerCase() : '';
            
            let matchSearch = nameTxt.includes(searchInput);
            let matchKategori = kategoriInput === "" || katTxt === kategoriInput;
            
            if (matchSearch && matchKategori) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    }

    @if(session('success'))
        Swal.fire({ icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}", showConfirmButton: false, timer: 2500 });
    @endif
    @if($errors->any())
        Swal.fire({ icon: 'error', title: 'Aksi Gagal!', text: "{{ $errors->first() }}", showConfirmButton: true });
    @endif
</script>
@endpush