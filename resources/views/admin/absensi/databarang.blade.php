@extends('admin.adminLayout')

@section('content')
<!-- Link Font, Icon, & SweetAlert2 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
    
    .input-custom { 
        background-color: #f0f5ff; 
        border: 1px solid transparent; 
        border-radius: 0.75rem; 
        font-size: 0.875rem; 
        padding: 0.75rem 1rem; 
        transition: all 0.2s;
    }
    .input-custom:focus { 
        background-color: #fff;
        border-color: #3b82f6;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
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

    /* Modal Styling */
    .modal-overlay { transition: opacity 0.3s ease; }
    .modal-container { transition: transform 0.3s ease; }
    .hidden-modal { display: none; }
</style>

<div class="p-6 md:p-10 max-w-7xl mx-auto">
    
    <!-- HEADER -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-[#0f172a]">Aktivitas Inventaris</h1>
        <p class="text-gray-500 mt-1">Pantau seluruh log keluar masuk barang secara real-time.</p>
    </div>

    <!-- FILTER SECTION -->
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex flex-wrap gap-4 items-center mb-8">
        <div class="relative flex-grow min-w-[280px] group">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i class="fa-solid fa-magnifying-glass text-gray-400 group-focus-within:text-blue-500 transition-colors duration-200"></i>
            </div>
            <input type="text" class="input-custom w-full pl-12 focus:ring-0" placeholder="Cari nama barang atau catatan...">
        </div>

        <div class="flex flex-wrap gap-3">
            <select class="input-custom min-w-[140px] cursor-pointer">
                <option>Semua Status</option>
                <option>Masuk</option>
                <option>Keluar</option>
            </select>
            <button class="bg-[#0055d4] hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl font-bold text-sm transition-all shadow-md active:scale-95">
                Filter Data
            </button>
        </div>
    </div>

    <!-- TABEL AKTIVITAS -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-10">
        <div class="p-6 flex justify-between items-center">
            <h2 class="text-xl font-bold text-[#1e293b]">Log Aktivitas Terbaru</h2>
            <span class="text-[11px] font-bold bg-gray-100 text-gray-500 px-3 py-1 rounded-full uppercase tracking-wider">
                10 Data Terakhir
            </span>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#f8faff] text-[11px] font-bold text-gray-500 uppercase tracking-widest border-y border-gray-50">
                        <th class="px-6 py-4 text-center w-16">No</th>
                        <th class="px-6 py-4">Nama Barang</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Jumlah</th>
                        <th class="px-6 py-4">Tujuan</th>
                        <th class="px-6 py-4">Catatan</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <!-- Contoh Row 1 (Masuk) -->
                    <tr class="hover:bg-blue-50/30 transition-colors group" id="row-1">
                        <td class="px-6 py-4 text-center text-sm font-medium text-gray-600">1</td>
                        <td class="px-6 py-4">
                            <span class="font-bold text-[#1e293b] block item-name">Sapu Ijuk Super</span>
                            <span class="text-[10px] px-2 py-0.5 bg-gray-100 text-gray-500 rounded font-mono">#INV-001</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="badge-masuk status-label">Masuk</span>
                        </td>
                        <td class="px-6 py-4 text-center font-bold text-blue-600 item-qty">10</td>
                        <td class="px-6 py-4 text-gray-400 text-center item-target">-</td>
                        <td class="px-6 py-4 text-sm text-gray-600 truncate max-w-[150px] item-note">Restock dari Vendor A</td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-1">
                                <button onclick="openEditModal(1, 'Sapu Ijuk Super', 'Masuk', 10, '-', 'Restock dari Vendor A')" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <button onclick="confirmDelete(1)" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Contoh Row 2 (Keluar) -->
                    <tr class="hover:bg-red-50/30 transition-colors group" id="row-2">
                        <td class="px-6 py-4 text-center text-sm font-medium text-gray-600">2</td>
                        <td class="px-6 py-4">
                            <span class="font-bold text-[#1e293b] block item-name">Cairan Pel Lemon 5L</span>
                            <span class="text-[10px] px-2 py-0.5 bg-gray-100 text-gray-500 rounded font-mono">#INV-042</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="badge-keluar status-label">Keluar</span>
                        </td>
                        <td class="px-6 py-4 text-center font-bold text-red-600 item-qty">5</td>
                        <td class="px-6 py-4 text-sm font-semibold text-[#1e293b] item-target">Hotel Grand Hyatt</td>
                        <td class="px-6 py-4 text-sm text-gray-600 truncate max-w-[150px] item-note">Pembersihan Lobby Utama</td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-1">
                                <button onclick="openEditModal(2, 'Cairan Pel Lemon 5L', 'Keluar', 5, 'Hotel Grand Hyatt', 'Pembersihan Lobby Utama')" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <button onclick="confirmDelete(2)" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL EDIT -->
<div id="editModal" class="fixed inset-0 z-50 hidden-modal overflow-y-auto">
    <!-- Overlay -->
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity"></div>
    
    <!-- Content -->
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-md rounded-[2rem] shadow-2xl overflow-hidden transform transition-all">
            <div class="bg-[#f8faff] p-6 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-xl font-bold text-[#1e293b]">Edit Aktivitas</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark text-xl"></i></button>
            </div>
            
            <form id="formEditActivity" class="p-8 space-y-4">
                <input type="hidden" id="edit_id">
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Nama Barang</label>
                    <input type="text" id="edit_nama" class="input-custom w-full bg-gray-50" readonly>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Jumlah</label>
                        <input type="number" id="edit_jumlah" class="input-custom w-full">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Status</label>
                        <input type="text" id="edit_status" class="input-custom w-full bg-gray-50" readonly>
                    </div>
                </div>

                <div id="tujuan_container">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Tujuan (Klien)</label>
                    <input type="text" id="edit_tujuan" class="input-custom w-full">
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Catatan</label>
                    <textarea id="edit_catatan" class="input-custom w-full h-24 resize-none"></textarea>
                </div>

                <div class="flex flex-row gap-3 pt-6 border-t border-gray-100 mt-6">
    <!-- Tombol Batal -->
    <button type="button" 
            onclick="closeEditModal()" 
            class="flex-1 px-6 py-3.5 border border-gray-200 text-gray-600 font-bold rounded-2xl hover:bg-gray-50 transition-all text-sm">
        Batal
    </button>
    
    <!-- Tombol Simpan (DIPAKSA MENGGUNAKAN STYLE MANUAL) -->
    <button type="submit" 
            style="background-color: #0055d4 !important; color: #ffffff !important; display: block !important;"
            class="flex-1 px-6 py-3.5 font-bold rounded-2xl shadow-lg shadow-blue-200 transition-all hover:opacity-90 active:scale-95 text-sm">
        Simpan Perubahan
    </button>
</div>
        </div>
 
    </div>
</div>

<script>
    // FUNGSI MODAL EDIT
    function openEditModal(id, nama, status, jumlah, tujuan, catatan) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_nama').value = nama;
        document.getElementById('edit_status').value = status;
        document.getElementById('edit_jumlah').value = jumlah;
        document.getElementById('edit_catatan').value = catatan;
        
        const tujuanInput = document.getElementById('edit_tujuan');
        const tujuanContainer = document.getElementById('tujuan_container');

        if(status === 'Masuk') {
            tujuanInput.value = '-';
            tujuanContainer.style.opacity = '0.5';
            tujuanInput.readOnly = true;
        } else {
            tujuanInput.value = tujuan;
            tujuanContainer.style.opacity = '1';
            tujuanInput.readOnly = false;
        }

        document.getElementById('editModal').classList.remove('hidden-modal');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden-modal');
    }

    // SIMULASI SUBMIT FORM EDIT
    document.getElementById('formEditActivity').onsubmit = function(e) {
        e.preventDefault();
        
        // Di sini Anda biasanya mengirim data ke Server via AJAX/Laravel Controller
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Data aktivitas telah diperbarui.',
            showConfirmButton: false,
            timer: 1500
        });
        
        closeEditModal();
    };

    // FUNGSI KONFIRMASI HAPUS
    function confirmDelete(id) {
        Swal.fire({
            title: 'Hapus data ini?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#c21e1e',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            borderRadius: '1.5rem'
        }).then((result) => {
            if (result.isConfirmed) {
                // Di sini letakkan logic hapus Laravel (Redirect atau AJAX)
                // Contoh simulasi hapus row dari tabel:
                document.getElementById('row-' + id).style.opacity = '0';
                setTimeout(() => {
                    document.getElementById('row-' + id).remove();
                }, 300);

                Swal.fire(
                    'Terhapus!',
                    'Data telah berhasil dihapus.',
                    'success'
                )
            }
        })
    }
</script>
@endsection