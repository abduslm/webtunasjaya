<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PT. Tunas Jaya Bersinar Cemerlang</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300,400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .sidebar-nav a {
            transition: all 0.2s ease;
        }
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>

{{-- header --}}
<body class="bg-[#F4F7FF]">

    <div class="bg-[#0B3C5D] text-white py-2 px-4 md:px-16 text-xs md:text-sm">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex gap-4 md:gap-6">
                <div class="flex items-center gap-1 md:gap-2">
                    <i class="bi bi-envelope-fill w-3 h-3 md:w-4 md:h-4 text-white"></i>
                    <span>tunasjayaclean@gmail.com</span>
                </div>
                <div class="flex items-center gap-1 md:gap-2">
                    <i class="bi bi-telephone-fill w-3 h-3 md:w-4 md:h-4 text-white"></i>
                    <span>081235188282 / 081331733891</span>
                </div>
            </div>
            <div class="hidden md:block italic">Profesional & Terpercaya</div>
        </div>
    </div>

    <nav class="bg-white shadow-sm border-b border-gray-100 px-4 md:px-16 py-4 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            
            <div class="flex items-center">
                <div class="w-48 h-12 flex items-center">
                    <img src="{{ optional($profil)->logo ? asset('storage/' . $profil->logo) : asset('assets/images/landing_page/logo.png') }}" alt="Logo Tunas Jaya" class="w-auto h-full object-contain">
                    </div>
            </div>

            <ul class="hidden md:flex items-center space-x-8 text-gray-600 font-medium">
                <li><a href="#beranda" class="text-blue-600 border-b-2 border-blue-600 pb-1">Beranda</a></li>
                <li><a href="#tentang-kami" class="hover:text-blue-600 transition-colors">Tentang Kami</a></li>
                <li><a href="#layanan" class="hover:text-blue-600 transition-colors">Layanan</a></li>
                <li><a href="#portofolio" class="hover:text-blue-600 transition-colors">Portfolio</a></li>
                <li><a href="#hubungi-kami" class="hover:text-blue-600 transition-colors">Hubungi Kami</a></li>
            </ul>
        </div>
    </nav>


{{-- welcome --}}
@php
    // Ambil data dari database untuk bagian beranda
    $beranda = \App\Models\kelola_halaman::where('section', 'beranda_hero')->first();
@endphp

<section id="beranda" class="min-h-screen flex items-center justify-center p-6 md:p-12">
    <div class="max-w-7xl w-full grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
        
        <div class="order-2 lg:order-1 space-y-8">
            <div>
                <span class="bg-[#E8EFFF] text-[#4285F4] px-5 py-2 rounded-full text-xs font-bold tracking-widest uppercase shadow-sm">
                    Cleaning Solution Partner
                </span>
            </div>
            
            {{-- JUDUL DINAMIS --}}
            <h1 class="text-5xl md:text-7xl font-extrabold text-[#0B3C5D] leading-[1.1]">
                {!! nl2br(e($beranda->judul ?? 'Profesional Outsourcing & Cleaning Service')) !!}
            </h1>
            
            <div class="w-20 h-1.5 bg-[#4285F4] rounded-full"></div>
            
            {{-- DESKRIPSI DINAMIS --}}
            <p class="text-gray-500 text-lg md:text-xl leading-relaxed max-w-lg">
                {{ $beranda->desk_singkat ?? 'Solusi Terpadu Layanan Profesional untuk Bisnis Anda. Kami menghadirkan standar kebersihan kelas dunia untuk menunjang produktivitas Anda.' }}
            </p>
            
            <div class="flex flex-wrap gap-4 pt-4">
                <a href="#hubungi-kami" class="bg-[#4285F4] hover:bg-blue-600 text-white px-10 py-4 rounded-2xl font-bold flex items-center gap-3 transition-all duration-300 shadow-xl shadow-blue-200 transform hover:-translate-y-1">
                    <i class="bi bi-envelope-fill w-5 h-5"></i>    
                    Hubungi Kami
                </a>
                <a href="#layanan" class="bg-white border border-gray-100 text-[#4285F4] hover:bg-gray-50 px-10 py-4 rounded-2xl font-bold transition-all duration-300 shadow-sm transform hover:-translate-y-1">
                    Lihat Layanan
                </a>
            </div>
            
            <div class="flex gap-12 pt-10 border-t border-gray-200">
                <div>
                    <h3 class="text-3xl font-extrabold text-[#0B3C5D]">100%</h3>
                    <p class="text-sm font-medium text-gray-400 uppercase tracking-wide">Terpercaya</p>
                </div>
                <div>
                    <h3 class="text-3xl font-extrabold text-[#0B3C5D]">Bersertifikasi</h3>
                    <p class="text-sm font-medium text-gray-400 uppercase tracking-wide">Tim Profesional</p>
                </div>
            </div>
        </div>

        <div class="order-1 lg:order-2 relative group">
            <div class="absolute inset-0 bg-blue-200 rounded-[50px] rotate-3 scale-95 blur-xl opacity-50 group-hover:rotate-0 transition-transform duration-700"></div>
            
            <div class="relative rounded-[50px] overflow-hidden shadow-2xl transition-transform duration-700 group-hover:scale-[1.02] bg-gray-200">
                {{-- GAMBAR DINAMIS --}}
                @if($beranda && $beranda->gambar)
                    <img 
                        src="{{ Storage::url($beranda->gambar) }}" 
                        alt="Hero Image" 
                        class="w-full h-[450px] md:h-[600px] object-cover"
                    >
                @else
                    {{-- Gambar Default jika database kosong --}}
                    <img 
                        src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=1000" 
                        alt="Default Hero" 
                        class="w-full h-[450px] md:h-[600px] object-cover"
                    >
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-[#0B3C5D]/30 to-transparent"></div>
            </div>

            <div class="absolute bottom-8 left-8 right-8 md:right-auto md:w-72 bg-white/90 backdrop-blur-xl p-5 rounded-[30px] shadow-2xl flex items-center gap-5 border border-white transform transition-all duration-500 hover:-translate-y-2">
                <div class="bg-[#4285F4] p-4 rounded-2xl text-white shadow-lg shadow-blue-200">
                    <i class="bi bi-shield-check w-8 h-8"></i>
                </div>
                <div>
                    <p class="text-[10px] uppercase tracking-[0.2em] text-gray-400 font-black mb-1">Kualitas Terjamin</p>
                    <p class="text-[#0B3C5D] font-extrabold text-xl leading-none">Layanan 24/7</p>
                </div>
            </div>
        </div>

    </div>
</section>



{{-- tentang kami --}}
<section id="tentang-kami" class="py-12 px-4 md:px-12 lg:px-20">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-start">
                
                <div class="space-y-8">
                    <div>
                        <h2 class="text-3xl md:text-4xl font-extrabold text-[#0B3C5D] mb-1 uppercase tracking-tight">Tentang Kami</h2>
                        <div class="w-16 h-1 bg-[#0B3C5D] mb-6"></div>
                        
                        <h3 class="text-lg font-bold text-gray-800 mb-3 leading-snug">
                            Tunas Jaya adalah sebuah perusahaan yang bergerak dalam bidang jasa.
                        </h3>
                        
                        <div class="text-gray-600 space-y-4 text-justify md:text-left leading-relaxed">
                            <p>
                                Didirikan pada Agustus 2007, <strong>PT. Tunas Jaya Bersinar Cemerlang</strong> berkomitmen menghadirkan standar baru dalam industri Outsourcing dan Cleaning Service dengan menyeimbangkan keahlian teknis dan kekuatan karakter.
                            </p>
                            <p>
                                Di tengah persaingan industri, kami memastikan setiap personil dibekali pelatihan intensif agar memiliki etika kerja, integritas, dan tanggung jawab yang tinggi sebagai mitra strategis perusahaan Anda.
                            </p>
                        </div>
                    </div>

                    <div class="bg-[#F4F7FF] p-5 rounded-xl flex flex-col sm:flex-row gap-5 items-center shadow-sm border border-blue-100">
                        <div class="w-full sm:w-[40%]">
                            <img src="assets/images/landing_page/tentangKami1.png" alt="tentangkami 1" class="w-auto h-full object-contain bg-blue-800/20">
                        </div>

                        <div class="w-full sm:w-[60%]">
                            <div class="flex items-center gap-3 mb-2">
                                <h4 class="text-2xl font-bold text-[#0B3C5D]">Visi</h4>
                                <div class="flex-grow h-px bg-blue-200"></div>
                            </div>
                            <p class="italic text-gray-700 leading-relaxed text-xs md:text-sm">
                                "Menjadi Perusahaan Outsourcing & Cleaning Service yang terbaik dengan menciptakan tenaga kerja yang berkualitas & selalu mengutamakan kepuasan pelanggan."
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-[#F4F7FF] p-6 md:p-10 rounded-[32px] border border-blue-100 shadow-sm">
                    <div class="flex items-center justify-center gap-6 mb-8">
                        <div class="flex-grow h-px bg-blue-200"></div>
                        <h4 class="text-3xl font-bold text-[#0B3C5D]">Misi</h4>
                        <div class="flex-grow h-px bg-blue-200"></div>
                    </div>

                    <div class="space-y-6">
                        <div class="flex gap-4 items-start">
                            <div class="flex-shrink-0 w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm text-blue-600">
                                <i class="bi bi-people" class="w-5 h-5"></i>
                            </div>
                            <p class="text-gray-700 text-sm md:text-base leading-snug pt-1">
                                Mewujudkan sistem kerja yang sistematis dan mantap sehingga menghasilkan SDM berkualitas.
                            </p>
                        </div>

                        <div class="flex gap-4 items-start">
                            <div class="flex-shrink-0 w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm text-blue-600">
                                <i class="bi bi-cash-stack" class="w-5 h-5"></i>
                            </div>
                            <p class="text-gray-700 text-sm md:text-base leading-snug pt-1">
                                Memberikan hasil berkualitas dan pelayanan memuaskan dengan harga terjangkau.
                            </p>
                        </div>

                        <div class="flex gap-4 items-start">
                            <div class="flex-shrink-0 w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm text-blue-600">
                                <i class="bi bi-chat-dots w-5 h-5"></i>
                            </div>
                            <p class="text-gray-700 text-sm md:text-base leading-snug pt-1">
                                Menciptakan komunikasi terarah dan kerjasama tim yang baik antara pimpinan dan karyawan.
                            </p>
                        </div>

                        <div class="flex gap-4 items-start">
                            <div class="flex-shrink-0 w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm text-blue-600">
                                <i class="bi bi-heart w-5 h-5"></i>
                            </div>
                            <p class="text-gray-700 text-sm md:text-base leading-snug pt-1">
                                Mewujudkan kesejahteraan karyawan guna meningkatkan motivasi dan loyalitas kerja.
                            </p>
                        </div>

                        <div class="flex gap-4 items-start">
                            <div class="flex-shrink-0 w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm text-blue-600">
                                <i class="bi bi-lightbulb w-5 h-5"></i>
                            </div>
                            <p class="text-gray-700 text-sm md:text-base leading-snug pt-1">
                                Melatih karyawan agar bermental tangguh, disiplin, jujur, ramah, rapi, kreatif, dan inovatif.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>



{{-- layanan --}}
<section id="layanan" class="py-20 px-4 md:px-12 lg:px-24 bg-gray-50">
        <div class="max-w-7xl mx-auto">
            <div class="mb-16">
                <h2 class="text-5xl font-extrabold text-gray-900 mb-4 tracking-tight">Our Core Services</h2>
                <div class="w-24 h-1.5 bg-[#0B3C5D] mb-6"></div>
                <p class="text-gray-500 max-w-xl text-lg">Elevate your environment with our curated suite of professional maintenance and aesthetic solutions.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-10 rounded-3xl border-2 border-[#0B3C5D] relative overflow-hidden group">
                    <div class="bg-blue-100 text-[#0B3C5D] w-14 h-14 rounded-xl flex items-center justify-center mb-8">
                        <i class="bi bi-recycle w-8 h-8"></i>
                    </div>
                    <h4 class="text-2xl font-bold mb-2">Cleaning Service</h4>
                    <p class="text-gray-400 text-xs uppercase font-bold tracking-widest mb-10">Commercial & Industrial</p>
                    <a href="#" class="text-[#0B3C5D] font-bold flex items-center gap-2 text-sm">
                        Selected Service <i class="bi bi-checks-circle w-4 h-4"></i>
                    </a>
                    <div class="absolute -top-4 -right-4 w-20 h-20 bg-gray-100 rounded-full opacity-50"></div>
                </div>

                <div class="bg-white p-10 rounded-3xl border border-transparent shadow-sm relative overflow-hidden">
                    <div class="bg-blue-100 text-blue-400 w-14 h-14 rounded-xl flex items-center justify-center mb-8">
                        <i class="bi bi-flower1 w-8 h-8"></i>
                    </div>
                    <h4 class="text-2xl font-bold mb-2 text-gray-700">Gardening</h4>
                    <p class="text-gray-400 text-xs uppercase font-bold tracking-widest mb-10">Precision Horticulture</p>
                    <a href="#" class="text-blue-500 font-bold flex items-center gap-2 text-sm">
                        View details <i class="bi bi-arrow-right w-4 h-4"></i>
                    </a>
                </div>

                <div class="bg-white p-10 rounded-3xl border border-transparent shadow-sm relative overflow-hidden">
                    <div class="bg-blue-100 text-blue-400 w-14 h-14 rounded-xl flex items-center justify-center mb-8">
                        <i class="bi bi-tree w-8 h-8"></i>
                    </div>
                    <h4 class="text-2xl font-bold mb-2 text-gray-700">Landscape</h4>
                    <p class="text-gray-400 text-xs uppercase font-bold tracking-widest mb-10">Architectural Design</p>
                    <a href="#" class="text-blue-500 font-bold flex items-center gap-2 text-sm">
                        View details <i class="bi bi-arrow-right w-4 h-4"></i>
                    </a>
                </div>
            </div>

            <div class="mt-24 grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div class="rounded-[2.5rem] overflow-hidden shadow-2xl h-96 bg-gray-300">
                    <img src="/assets/images/landing_page/layanan1.png" alt="Industrial Cleaning" class="w-full h-full object-cover">
                </div>

                <div class="space-y-8">
                    <span class="text-blue-600 font-bold text-xs uppercase tracking-[0.2em]">Spesialisasi Pembersihan</span>
                    <h2 class="text-4xl font-bold text-gray-900 leading-tight">Perhatian Mendalam pada Standar Industri</h2>
                    <p class="text-gray-500 leading-relaxed">Divisi pembersihan khusus kami menggunakan protokol disinfeksi kelas medis dan peralatan standar industri untuk mengubah lingkungan komersial dengan trafik tinggi.</p>
                    
                    <div class="space-y-6">
                        <div class="flex gap-4">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600">
                                <i class="bi bi-layers w-5 h-5"></i>
                            </div>
                            <div>
                                <h5 class="font-bold text-gray-900">Pembersihan Lantai Mendalam</h5>
                                <p class="text-gray-500 text-sm">Poles dan restorasi untuk marmer, beton, dan permukaan lainnya.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600">
                                <i class="bi bi-shield-check w-5 h-5"></i>
                            </div>
                            <div>
                                <h5 class="font-bold text-gray-900">Sanitasi Area Kerja</h5>
                                <p class="text-gray-500 text-sm">Kontrol patogen spektrum penuh untuk kantor korporat.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600">
                                <i class="bi bi-trash2 w-5 h-5"></i>
                            </div>
                            <div>
                                <h5 class="font-bold text-gray-900">Pengelolaan Sampah Profesional</h5>
                                <p class="text-gray-500 text-sm">Sistem pembuangan berkelanjutan dan optimalisasi daur ulang.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



{{-- portofolio --}}
<section id="portofolio" class="py-16 px-4 md:px-8 lg:px-16">
        <div class="max-w-7xl mx-auto">
            
            <div class="mb-12">
                <h1 class="text-4xl font-bold text-[#0B3C5D] inline-block border-b-4 border-[#0B3C5D] pb-1">
                    Portofolio
                </h1>
                <p class="mt-4 text-gray-700 text-lg max-w-3xl">
                    Dedikasi kami tercermin dalam pengalaman, keahlian, dan hasil yang terbukti bersama klien kami
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                
                <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300">
                    <div class="w-full aspect-[4/3] bg-gray-200 rounded-[2rem] overflow-hidden mb-6 flex items-center justify-center border border-gray-100">
                        <img src="assets/images/landing_page/porto1.png" alt="porto 1" class="w-auto h-full object-contain">
                    </div>
                    <div class="space-y-2">
                        <h3 class="text-xl font-bold text-gray-900">Intiland Tower Surabaya</h3>
                        <p class="text-gray-600 italic text-sm leading-relaxed">
                            Gedung perkantoran (office tower) prestisius berkonsep hijau (eco-friendly)
                        </p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300">
                    <div class="w-full aspect-[4/3] bg-gray-200 rounded-[2rem] overflow-hidden mb-6 flex items-center justify-center border border-gray-100">
                        <img src="assets/images/landing_page/porto2.png" alt="porto 2" class="w-auto h-full object-contain">
                    </div>
                    <div class="space-y-2">
                        <h3 class="text-xl font-bold text-gray-900 text-left">Boncafe Surabaya</h3>
                        <p class="text-gray-600 italic text-sm leading-relaxed">
                            Boncafe adalah restoran steak legendaris di Surabaya yang berdiri sejak 28 Februari 1977. Terkenal dengan cita rasa steak khas Eropa.
                        </p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300">
                    <div class="w-full aspect-[4/3] bg-gray-200 rounded-[2rem] overflow-hidden mb-6 flex items-center justify-center border border-gray-100">
                        <img src="assets/images/landing_page/porto3.png" alt="porto 3" class="w-auto h-full object-contain">
                    </div>
                    <div class="space-y-2">
                        <h3 class="text-xl font-bold text-gray-900 uppercase">RSUD BANGIL</h3>
                        <p class="text-gray-600 italic text-sm leading-relaxed">
                            Rumah sakit milik Pemerintah Kabupaten Pasuruan, Jawa Timur, yang berlokasi di Jl. Raya Raci Bangil
                        </p>
                    </div>
                </div>

            </div>

            <div class="mt-12 flex justify-center items-center space-x-3">
                <span class="w-3 h-3 rounded-full bg-gray-300 cursor-pointer"></span>
                <span class="w-3 h-3 rounded-full bg-[#008080] cursor-pointer"></span>
                <span class="w-3 h-3 rounded-full bg-gray-300 cursor-pointer"></span>
                <span class="w-3 h-3 rounded-full bg-gray-300 cursor-pointer"></span>
                <span class="w-3 h-3 rounded-full bg-gray-300 cursor-pointer"></span>
            </div>
        </div>
    </section>

{{-- Dokumentasi --}}
    <section class="py-16 px-4 md:px-8 lg:px-16 bg-white">
        <div class="max-w-7xl mx-auto">
            
            <div class="mb-10">
                <h2 class="text-4xl font-bold text-[#0B3C5D] inline-block border-b-4 border-[#0B3C5D] pb-2">
                    Dokumentasi
                </h2>
            </div>

            <div class="flex flex-wrap justify-center gap-6 mb-12 text-sm md:text-base font-semibold text-gray-600">
                <button class="text-[#008080] border-b-2 border-[#008080] pb-1">All</button>
                <button class="hover:text-[#008080] transition-colors">Cleaning Service</button>
                <button class="hover:text-[#008080] transition-colors">Gardening</button>
                <button class="hover:text-[#008080] transition-colors">Landscape</button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                
                <div class="group cursor-pointer">
                    <div class="w-full aspect-[4/3] bg-gray-100 rounded-lg overflow-hidden mb-4 border border-gray-200 flex items-center justify-center relative">
                        <img src="assets/images/landing_page/dokumentasi1.jpeg" alt="dokumentasi 1" class="w-auto h-full object-contain">
                        <div class="absolute inset-0 bg-black/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    </div>
                    <div class="space-y-1">
                        <h3 class="font-bold text-lg text-gray-900">1 Maret 2026 - PT sentosa Jaya</h3>
                        <p class="text-gray-500 text-sm">Cleaning Service</p>
                    </div>
                </div>

                <div class="group cursor-pointer">
                    <div class="w-full aspect-[4/3] bg-gray-100 rounded-lg overflow-hidden mb-4 border border-gray-200 flex items-center justify-center relative">
                        <img src="assets/images/landing_page/dok2.jpeg" alt="dokumentasi 2" class="w-auto h-full object-contain">
                        <div class="absolute inset-0 bg-black/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    </div>
                    <div class="space-y-1">
                        <h3 class="font-bold text-lg text-gray-900">1 Maret 2026 - Yamaha Kalsel</h3>
                        <p class="text-gray-500 text-sm">Cleaning Service</p>
                    </div>
                </div>

                <div class="group cursor-pointer">
                    <div class="w-full aspect-[4/3] bg-gray-100 rounded-lg overflow-hidden mb-4 border border-gray-200 flex items-center justify-center relative">
                        <img src="assets/images/landing_page/dok3.jpeg" alt="dokumentasi 3" class="w-auto h-full object-contain">
                        <div class="absolute inset-0 bg-black/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    </div>
                    <div class="space-y-1">
                        <h3 class="font-bold text-lg text-gray-900">1 Maret 2026 - Yamaha Kaltim</h3>
                        <p class="text-gray-500 text-sm">Cleaning Service</p>
                    </div>
                </div>

                <div class="group cursor-pointer">
                    <div class="w-full aspect-[4/3] bg-gray-100 rounded-lg overflow-hidden mb-4 border border-gray-200 flex items-center justify-center relative">
                        <img src="assets/images/landing_page/dok4.jpeg" alt="dokumentasi 4" class="w-auto h-full object-contain">
                        <div class="absolute inset-0 bg-black/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    </div>
                    <div class="space-y-1">
                        <h3 class="font-bold text-lg text-gray-900">1 Maret 2026 - Yamaha Banyuwangi</h3>
                        <p class="text-gray-500 text-sm">Cleaning Service</p>
                    </div>
                </div>

                <div class="group cursor-pointer">
                    <div class="w-full aspect-[4/3] bg-gray-100 rounded-lg overflow-hidden mb-4 border border-gray-200 flex items-center justify-center relative">
                        <img src="assets/images/landing_page/dok5.jpeg" alt="dokumentasi 5" class="w-auto h-full object-contain">
                        <div class="absolute inset-0 bg-black/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    </div>
                    <div class="space-y-1">
                        <h3 class="font-bold text-lg text-gray-900">28 Februari 2026 - Yamaha Kaltim</h3>
                        <p class="text-gray-500 text-sm">Cleaning Service</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    



{{-- hubungi kami --}}
    <section id="hubungi-kami" class="max-w-6xl mx-auto px-6 py-16">
        <div class="py-16 px-4 text-center border-b border-gray-100">
            <h1 class="text-4xl font-extrabold mb-4 text-slate-900">Hubungi Kami</h1>
            <p class="text-slate-500 max-w-2xl mx-auto text-lg">
                Kami siap melayani kebutuhan energi dan konstruksi Anda dengan solusi terbaik dan profesional.
            </p>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">
            
            <section >
                <h2 class="text-3xl font-bold mb-4 text-slate-900">Informasi Kontak</h2>
                <p class="text-slate-500 mb-10 leading-relaxed">
                    Silakan kunjungi kantor kami atau hubungi kami melalui saluran di bawah ini untuk respon yang lebih cepat.
                </p>

                <div class="space-y-5">
                    <div class="flex items-center p-5 bg-white rounded-2xl shadow-sm border border-slate-100 transition-hover hover:shadow-md">
                        <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center mr-5 shrink-0">
                            <i class="bi bi-geo-alt w-6 h-6"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900">Alamat</h3>
                            <p class="text-slate-500">{{ optional($profil)->alamat ?? 'Alamat belum tersedia' }}</p>
                        </div>
                    </div>

                    <div class="flex items-center p-5 bg-white rounded-2xl shadow-sm border border-slate-100 transition-hover hover:shadow-md">
                        <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center mr-5 shrink-0">
                            <i class="bi bi-phone w-6 h-6"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900">Telepon</h3>
                            <p class="text-slate-500">{{ optional($profil)->no_telepon ?? 'Telepon belum tersedia' }}</p>
                        </div>
                    </div>

                    <div class="flex items-center p-5 bg-white rounded-2xl shadow-sm border border-slate-100 transition-hover hover:shadow-md">
                        <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center mr-5 shrink-0">
                            <i class="bi bi-envelope w-6 h-6"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900">Email</h3>
                            <p class="text-indigo-600 font-medium">{{ optional($profil)->email ?? 'Email belum tersedia' }}</p>
                        </div>
                    </div>

                    <div class="flex items-center p-5 bg-white rounded-2xl shadow-sm border border-slate-100 transition-hover hover:shadow-md">
                        <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center mr-5 shrink-0">
                            <i class="bi bi-clock w-6 h-6"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900">Jam Kerja</h3>
                            <p class="text-slate-500">{{ optional($profil)->senin_jumat ?? 'Senin - Sabtu : 08.00 - 16.00 WIB' }}</p>
                        </div>
                    </div>
                </div>
            </section>

            <section id="" class="bg-white p-10 rounded-3xl shadow-2xl shadow-slate-200 border border-slate-100">
                <h2 class="text-2xl font-bold mb-8 text-slate-900">Kirim Pesan kepada Kami</h2>
                
                <form action="#" method="POST" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Lengkap</label>
                            <input type="text" name="nama" placeholder="Contoh: Budi Santoso" 
                                class="w-full p-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Email</label>
                            <input type="email" name="email" placeholder="budi@email.com" 
                                class="w-full p-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Subject</label>
                        <div class="relative">
                            <select name="subject" class="w-full p-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white appearance-none transition-all">
                                <option>Pertanyaan Umum</option>
                                <option>Layanan Energi</option>
                                <option>Proyek Konstruksi</option>
                                <option>Lainnya</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Pesan</label>
                        <textarea name="pesan" rows="5" placeholder="Tuliskan pesan Anda di sini..." 
                            class="w-full p-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all"></textarea>
                    </div>

                    <button type="submit" class="w-full md:w-auto px-10 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition-all shadow-lg shadow-indigo-200 active:scale-95">
                        Kirim Pesan
                    </button>
                </form>
            </section>

        </div>
</section>
{{-- footer --}}
<footer class="bg-[#0B3C5D] text-white pt-16 pb-8 px-6 md:px-12 lg:px-24">
        <div class="max-w-7xl mx-auto">
            
            {{-- Grid Footer Utama --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
                
                {{-- Kolom 1: Logo & Deskripsi --}}
                <div class="space-y-6">
                    <div class="bg-white p-1 rounded-sm w-full max-w-[280px] h-16 flex items-center justify-center">
                        <img src="{{ optional($profil)->logo ? asset('storage/' . $profil->logo) : asset('assets/images/landing_page/logo.png') }}" alt="Logo Tunas Jaya" class="w-auto h-full object-contain">
                    </div>

                    <p class="text-gray-300 text-sm leading-relaxed max-w-xs">
                        Ada pertanyaan atau ide? <br>
                        Jangan ragu untuk menghubungi kami kami senang mendengar dari Anda!
                    </p>

                    {{-- Social Media Icons --}}
                    <div class="flex gap-3">
                        <a href="{{ optional($profil)->twitter ?: '#' }}" class="w-10 h-10 border border-white/20 rounded-full flex items-center justify-center hover:bg-white/10 transition-all {{ optional($profil)->twitter ? '' : 'pointer-events-none opacity-50' }}">
                            <i class="bi bi-twitter w-5 h-5 text-white"></i>
                        </a>
                        <a href="{{ optional($profil)->facebook ?: '#' }}" class="w-10 h-10 border border-white/20 rounded-full flex items-center justify-center hover:bg-white/10 transition-all {{ optional($profil)->facebook ? '' : 'pointer-events-none opacity-50' }}">
                            <i class="bi bi-facebook w-5 h-5 text-white"></i>
                        </a>
                        <a href="{{ optional($profil)->ig ?: '#' }}" class="w-10 h-10 border border-white/20 rounded-full flex items-center justify-center hover:bg-white/10 transition-all {{ optional($profil)->ig ? '' : 'pointer-events-none opacity-50' }}">
                            <i class="bi bi-instagram w-5 h-5 text-white"></i>
                        </a>
                        <a href="{{ optional($profil)->linkedIn ?: '#' }}" class="w-10 h-10 border border-white/20 rounded-full flex items-center justify-center hover:bg-white/10 transition-all {{ optional($profil)->linkedIn ? '' : 'pointer-events-none opacity-50' }}">
                            <i class="bi bi-linkedin w-5 h-5 text-white"></i>
                        </a>
                    </div>
                </div>

                {{-- Kolom 2: Useful Links --}}
                <div>
                    <h4 class="text-lg font-bold mb-6">Useful Links</h4>
                    <ul class="space-y-4 text-gray-300 text-sm">
                        <li><a href="#beranda" class="hover:text-white transition-colors">Beranda</a></li>
                        <li><a href="#tentang-kami" class="hover:text-white transition-colors">Tentang Kami</a></li>
                        <li><a href="#jasa-produk" class="hover:text-white transition-colors">Jasa & Produk</a></li>
                        <li><a href="#portofolio" class="hover:text-white transition-colors">Portofolio</a></li>
                        <li><a href="#hubungi-kami" class="hover:text-white transition-colors">Hubungi Kami</a></li>
                    </ul>
                </div>

                {{-- Kolom 3: Our Services --}}
                <div>
                    <h4 class="text-lg font-bold mb-6">Our Services</h4>
                    <ul class="space-y-4 text-gray-300 text-sm">
                        <li><a href="#" class="hover:text-white transition-colors">Web Design</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Web Development</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Product Management</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Marketing</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Graphic Design</a></li>
                    </ul>
                </div>

                {{-- Kolom 4: Hubungi Kami --}}
                <div class="space-y-6">
                    <h4 class="text-lg font-bold mb-6">Hubungi Kami</h4>
                    
                        <div class="text-gray-300 text-sm leading-relaxed">
                        <p>{{ optional($profil)->alamat ?? 'Alamat belum tersedia' }}</p>
                    </div>

                    <div class="text-sm">
                        <p class="font-bold text-white mb-1">Telepon:</p>
                        <p class="text-gray-300">{{ optional($profil)->no_telepon ?? 'Telepon belum tersedia' }}</p>
                    </div>

                    <div class="text-sm">
                        <p class="font-bold text-white mb-1">Email:</p>
                        <p class="text-gray-300">{{ optional($profil)->email ?? 'Email belum tersedia' }}</p>
                    </div>
                </div>

            </div>

            {{-- Garis Pemisah --}}
            <div class="border-t border-white/10 pt-8 flex flex-col items-center justify-center text-center space-y-2">
                <p class="text-sm text-gray-400">
                    © Copyright <span class="font-bold text-white">I am</span> All Rights Reserved
                </p>
                <p class="text-xs text-gray-500">
                    Designed by MyTeam
                </p>
            </div>

        </div>
    </footer>
</body>
