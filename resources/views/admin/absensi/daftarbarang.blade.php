@extends('admin.adminLayout')

@section('content')
<!-- Script Icon Lucide -->
<script src="https://unpkg.com/lucide@latest"></script>

<div class="max-w-6xl mx-auto py-10 px-6 font-sans">
    <!-- Header Section -->
    <div class="mb-10">
        <h1 class="text-[32px] font-bold text-slate-900 tracking-tight">Daftar Barang</h1>
        <p class="text-slate-500 text-lg mt-1 font-medium">Kelola stok dan inventaris peralatan kebersihan gudang utama secara terpadu.</p>
    </div>

    <!-- Section 1: Form Card -->
    <div class="bg-white rounded-[24px] border border-slate-100 shadow-sm p-8 mb-10">
        
        <!-- Tab Buttons: Dibuat menyatu seperti di gambar -->
        <!-- Pastikan CDN Tailwind & Lucide Icons terpasang di <head> jika mencoba secara lokal -->
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/lucide@latest"></script>

<div class="inline-flex bg-slate-200 p-1.5 rounded-xl mb-10 border border-slate-300 shadow-sm">
    <button class="flex items-center gap-2 px-6 py-2.5 bg-[#003D9B] hover:bg-[#002d72] text-white rounded-lg font-bold text-sm shadow-md transition-all active:scale-95">
        <i data-lucide="log-in" class="w-4 h-4"></i>
        <span>Barang Masuk</span>
    </button>
</div>

<script>
  // Inisialisasi icon lucide
  lucide.createIcons();
</script>
            <button class="flex items-center gap-2 px-6 py-2.5 text-slate-600 hover:text-slate-900 rounded-lg font-bold text-sm transition-all">
                <i data-lucide="log-out" class="w-4 h-4"></i>
                Barang Keluar
            </button>
        </div>

        <!-- Form Grid -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-x-8 gap-y-10">
            <!-- Pilih Barang -->
            <div class="md:col-span-6">
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-widest mb-4">Pilih Barang</label>
                <div class="relative group">
                    <select class="w-full bg-[#f8fafc] border border-transparent focus:bg-white focus:border-blue-500/50 rounded-2xl px-5 py-4 text-slate-600 appearance-none outline-none transition-all cursor-pointer">
                        <option>Pilih item dari daftar...</option>
                    </select>
                    <i data-lucide="chevron-down" class="absolute right-5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none"></i>
                </div>
            </div>

            <!-- Jumlah -->
            <div class="md:col-span-2 text-center">
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-widest mb-4">Jumlah</label>
                <input type="number" value="0" class="w-full text-center py-4 text-slate-700 font-medium focus:outline-none text-2xl bg-transparent border-none" />
            </div>

            <!-- Satuan -->
            <div class="md:col-span-4">
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-widest mb-4">Satuan</label>
                <div class="relative">
                    <select class="w-full bg-[#f8fafc] border border-transparent focus:bg-white focus:border-blue-500/50 rounded-2xl px-5 py-4 text-slate-600 appearance-none outline-none transition-all cursor-pointer">
                        <option>Pcs</option>
                        <option>Box</option>
                    </select>
                    <i data-lucide="chevron-down" class="absolute right-5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none"></i>
                </div>
            </div>

            <!-- Sumber / Vendor -->
            <div class="md:col-span-12">
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-widest mb-2">Sumber / Vendor</label>
                <input type="text" placeholder="Masukkan nama supplier atau unit pengirim" class="w-full bg-transparent border-none px-0 py-3 text-slate-600 text-lg italic focus:ring-0 outline-none placeholder:text-slate-300 transition-all" />
                <!-- Garis bawah halus -->
                <div class="h-[1px] bg-slate-100 w-full group-focus-within:bg-blue-500"></div>
            </div>
        </div>

        <!-- Tombol Simpan Utama -->
        <button class="w-full mt-12 bg-[#003D9B] hover:bg-[#00358a] text-white flex items-center justify-center gap-3 py-[18px] rounded-[20px] font-bold text-lg shadow-lg shadow-[#003D9B]/20 transition-all active:scale-[0.98]">
            <i data-lucide="save" class="w-6 h-6 text-white"></i>
            Simpan Barang 
        </button>
    </div>

    <!-- Section 2: Daftar Barang Card -->
    <div class="bg-white rounded-[24px] border border-slate-100 shadow-sm overflow-hidden mt-10">
        <!-- Table Header (Search & Filter) -->
        <div class="p-8 flex flex-col md:flex-row justify-between items-center gap-6">
            <h2 class="text-2xl font-bold text-slate-900">Daftar Barang</h2>
            
            <div class="flex gap-4 w-full md:w-auto">
                <div class="relative flex-1 md:w-80 group">
                    <i data-lucide="search" class="absolute left-5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 group-focus-within:text-[#003D9B] transition-colors"></i>
                    <input type="text" placeholder="Cari nama atau kode..." class="w-full pl-12 pr-5 py-3.5 bg-white border border-slate-200 rounded-2xl text-sm focus:ring-4 focus:ring-[#003D9B]/5 focus:border-[#003D9B] outline-none transition-all">
                </div>
                <button class="p-3.5 border border-slate-200 rounded-2xl hover:bg-slate-50 text-slate-600 transition-all active:bg-slate-100">
                    <i data-lucide="sliders-horizontal" class="w-5 h-5"></i>
                </button>
            </div>
        </div>

        <!-- Tabel -->
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-sm">
                <thead>
                    <tr class="bg-[#f0f7ff] border-y border-slate-100">
                        <th class="px-8 py-5 text-left font-bold text-slate-500 uppercase tracking-widest">No</th>
                        <th class="px-8 py-5 text-left font-bold text-slate-500 uppercase tracking-widest">Nama Barang</th>
                        <th class="px-8 py-5 text-left font-bold text-slate-500 uppercase tracking-widest">Stok</th>
                        <th class="px-8 py-5 text-left font-bold text-slate-500 uppercase tracking-widest">Satuan</th>
                        <th class="px-8 py-5 text-left font-bold text-slate-500 uppercase tracking-widest">vendor</th>
                        <th class="px-8 py-5 text-right font-bold text-slate-500 uppercase tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="5" class="px-8 py-32 text-center">
                            <p class="text-slate-400 font-medium text-lg">Belum ada data barang tersedia</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Garis pemisah tipis sebelum footer -->
        <div class="h-[1px] bg-slate-100 mx-8"></div>

        
                
                <button class="p-2 text-slate-300 hover:text-slate-600 transition-colors"><i data-lucide="chevron-right" class="w-6 h-6"></i></button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
</script>

<style>
    /* Menghilangkan panah spinner pada input number agar bersih seperti di gambar */
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; 
        margin: 0; 
    }
</style>
@endsection