<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PT. Tunas Jaya Bersinar Cemerlang</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300,400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <style>
        .sidebar-nav a {
            transition: all 0.2s ease;
        }
        body { font-family: 'Inter', sans-serif; }
        .portofolioSwiper .swiper-pagination {
            position: relative;
            bottom: 0 !important;
            margin-top: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100% !important;
            left: 0 !important;
        }

        .portofolioSwiper .swiper-pagination-bullet {
            width: 12px;
            height: 12px;
            background-color: #d1d5db;
            opacity: 1;
            margin: 0 8px !important;
            transition: all 0.3s ease;
            cursor: pointer;
            border: 2px solid transparent;
        }

        .portofolioSwiper .swiper-pagination-bullet-active {
            background-color: #008080 !important;
            width: 30px;
            border-radius: 20px;
        }

        .portofolioSwiper .swiper-pagination-bullet:hover {
            background-color: #008080;
            transform: scale(1.2);
        }

        .portofolioSwiper {
            padding: 10px 10px 50px 10px !important;
        }
    </style>
</head>

{{-- header --}}
<body class="bg-[#F4F7FF]">

    <div class="bg-[#0B3C5D] text-white py-2 px-4 md:px-16 text-xs md:text-sm">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex gap-4 md:gap-6">
                <div class="flex items-center gap-1 md:gap-2">
                    <i class="bi bi-envelope-fill w-3 h-3 md:w-4 md:h-4 text-white"></i>
                    @forelse ($dataHubungi['email'] as $email)
                    <a href="mailto:{{ $email ?? '' }}">| {{$email}} </a>
                    @empty
                    <span></span>
                    @endforelse
                </div>
                <div class="flex items-center gap-1 md:gap-2">
                    <i class="bi bi-telephone-fill w-3 h-3 md:w-4 md:h-4 text-white"></i>
                    @forelse ($dataHubungi['no_telepon'] as $no_telepon)
                    <a href="https://wa.me/{{ str_replace([' ', '+', '-'], '', $no_telepon ?? '') }}" >| {{$no_telepon}} </a>
                    @empty
                    <span></span>
                    @endforelse
                </div>
            </div>
            <div class="hidden md:block italic">Profesional & Terpercaya</div>
        </div>
    </div>

    <nav class="bg-white shadow-sm border-b border-gray-100 px-4 md:px-16 py-4 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            
            <div class="flex items-center">
                <div class="w-48 h-12 flex items-center">
                    <img src="{{ optional($dataHubungi)->logo ? $dataHubungi->logo : asset('assets/images/landing_page/logo.png') }}" alt="Logo Tunas Jaya" class="w-auto h-full object-contain">
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
                {!! nl2br(e($dataBeranda['judulHero'] ?? 'Profesional Outsourcing & Cleaning Service')) !!}
            </h1>
            
            <div class="w-20 h-1.5 bg-[#4285F4] rounded-full"></div>
            
            {{-- DESKRIPSI DINAMIS --}}
            <p class="text-gray-500 text-lg md:text-xl leading-relaxed max-w-lg">
                {{ $dataBeranda['deskripsi'] ?? 'Solusi Terpadu Layanan Profesional untuk Bisnis Anda. Kami menghadirkan standar kebersihan kelas dunia untuk menunjang produktivitas Anda.' }}
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
                @if($dataBeranda && $dataBeranda['gambar'])
                    <img 
                        src="{{ $dataBeranda['gambar'] }}" 
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
                        <h2 class="text-4xl font-bold text-[#0B3C5D] inline-block border-b-4 border-[#0B3C5D] pb-1">Tentang Kami</h2>
                        <div class="mt-4 text-gray-700 text-lg max-w-3xl"></div>
                        
                        <h3 class="text-lg font-bold text-gray-800 mb-3 leading-snug">
                            {{ $dataHubungi['motto'] }}
                        </h3>
                        
                        <div class="text-gray-600 space-y-4 text-justify md:text-left leading-relaxed content-rich-text">
                            {!! $dataTentang['deskripsi'] !!}
                        </div>
                    </div>

                    <div class="bg-[#F4F7FF] p-5 rounded-xl flex flex-col sm:flex-row gap-5 items-center shadow-sm border border-blue-100">
                        <div class="w-full sm:w-[40%]">
                            <img src="{{$dataTentang['foto_visi']}}" alt="tentangkami 1" class="w-auto h-full object-contain bg-blue-800/20">
                        </div>

                        <div class="w-full sm:w-[60%]">
                            <div class="flex items-center gap-3 mb-2">
                                <h4 class="text-2xl font-bold text-[#0B3C5D]">Visi</h4>
                                <div class="flex-grow h-px bg-blue-200"></div>
                            </div>
                            <p class="italic text-gray-700 leading-relaxed text-xs md:text-sm">
                                {{$dataTentang['visi']}}
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
                        @forelse($dataTentang['misi_list'] as $misi) 
                        <div class="flex gap-4 items-start group">
                            <div class="flex-shrink-0 w-10 h-10 bg-[#0B3C5D] text-white rounded-lg flex items-center justify-center shadow-md font-bold text-sm transition-transform group-hover:scale-110">
                                {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                            </div>
                            <p class="text-gray-700 text-sm md:text-base leading-snug pt-2">
                                {{$misi}}
                            </p>
                        </div>
                        @empty
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </section>



{{-- layanan --}}
<section id="layanan" class="py-20 px-4 md:px-12 lg:px-24 bg-gray-50" 
        x-data="{ 
            selectedId: {{ $dataLayanan->first()['id'] ?? 0 }},
            layananData: {{ $dataLayanan->toJson() }}
            }"
        @pilih-layanan.window="const target = layananData.find(item => item.nama.includes($event.detail.nama));
            if (target) {
                selectedId = target.id;
            }
        ">
    <div class="max-w-7xl mx-auto">
        <div class="mb-16">
            <h2 class="text-4xl font-bold text-[#0B3C5D] inline-block border-b-4 border-[#0B3C5D] pb-1">Layanan</h2>
            <p class="mt-4 text-gray-700 text-lg max-w-3xl">Elevate your environment with our curated suite of professional maintenance and aesthetic solutions.</p>
        </div>

        {{-- Grid Card Layanan --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($dataLayanan as $layanan)
            <div @click="selectedId = {{ $layanan['id'] }}" 
                class="cursor-pointer transition-all duration-300 p-10 rounded-3xl border shadow-sm relative overflow-hidden group"
                :class="selectedId === {{ $layanan['id'] }} ? 'bg-[#0B3C5D] border-[#0B3C5D]' : 'bg-white border-transparent hover:shadow-md'">
                
                <span class="absolute -top-4 -right-4 text-8xl font-black opacity-10"
                    :class="selectedId === {{ $layanan['id'] }} ? 'text-white' : 'text-gray-200'">
                    {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                </span>

                <h4 class="text-2xl font-bold mb-2 transition-colors"
                    :class="selectedId === {{ $layanan['id'] }} ? 'text-white' : 'text-gray-700'">
                    {{ $layanan['nama'] }}
                </h4>
                
                <p class="text-xs uppercase font-bold tracking-widest mb-10 transition-colors"
                    :class="selectedId === {{ $layanan['id'] }} ? 'text-blue-200' : 'text-gray-400'">
                    {{ $layanan['desk_singkat'] }}
                </p>

                <div class="flex items-center gap-2 text-sm font-bold transition-colors"
                    :class="selectedId === {{ $layanan['id'] }} ? 'text-white' : 'text-blue-500'">
                    <span>View details</span> 
                    <i class="bi bi-arrow-right transition-transform group-hover:translate-x-1"></i>
                </div>
            </div>
            @endforeach
        </div>

        <template x-for="item in layananData" :key="item.id">
            <div x-show="selectedId === item.id" 
                x-transition:enter="transition ease-out duration-500"
                x-transition:enter-start="opacity-0 translate-y-8"
                x-transition:enter-end="opacity-100 translate-y-0"
                class="mt-24 grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                
                {{-- Bagian Gambar --}}
                <div class="rounded-[2.5rem] overflow-hidden shadow-2xl h-[450px] bg-gray-300">
                    <img :src="item.gambar_url || '/assets/images/placeholder.png'" 
                        :alt="item.nama" 
                        class="w-full h-full object-cover shadow-inner">
                </div>

                {{-- Bagian Deskripsi --}}
                <div class="space-y-8">
                    <span class="text-blue-600 font-bold text-xs uppercase tracking-[0.2em]" x-text="'Spesialisasi ' + item.nama"></span>
                    <h2 class="text-4xl font-bold text-gray-900 leading-tight" x-text="item.nama"></h2>
                    
                    <div class="text-gray-500 leading-relaxed text-lg content-rich-text" x-html="item.desk_panjang"></div>
                
                    <div class="pt-4">
                        <a href="https://wa.me/{{ str_replace([' ', '+', '-'], '', $dataHubungi['no_telepon'][0] ?? '') }}" 
                            class="inline-flex items-center gap-3 bg-[#0B3C5D] text-white px-8 py-4 rounded-full font-bold hover:bg-opacity-90 transition-all shadow-lg">
                            Konsultasi Sekarang <i class="bi bi-whatsapp"></i>
                        </a>
                    </div>
                </div>
            </div>
        </template>
    </div>
</section>



{{-- portofolio --}}
<section id="portofolio" class="py-16 px-4 md:px-8 lg:px-16 overflow-hidden">
    <div class="max-w-7xl mx-auto">
        <div class="mb-12">
            <h1 class="text-4xl font-bold text-[#0B3C5D] inline-block border-b-4 border-[#0B3C5D] pb-1">
                Portofolio
            </h1>
            <p class="mt-4 text-gray-700 text-lg max-w-3xl">
                Dedikasi kami tercermin dalam pengalaman, keahlian, dan hasil yang terbukti bersama klien kami
            </p>
        </div>
        <div class="swiper portofolioSwiper !pb-14">
            <div class="swiper-wrapper">
                @forelse($dataPortofolio as $portofolio)
                <div class="swiper-slide h-auto">
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 h-full hover:shadow-md transition-shadow duration-300">
                        <div class="w-full aspect-[4/3] bg-gray-50 rounded-[2rem] overflow-hidden mb-6 flex items-center justify-center border border-gray-100">
                            <img src="{{ $portofolio['gambar_url'] ?? asset('assets/images/placeholder.png') }}" 
                                alt="{{ $portofolio['klien'] }}" 
                                class="w-full h-full object-contain p-4">
                        </div>
                        <div class="space-y-2">
                            <h3 class="text-xl font-bold text-gray-900">{{ $portofolio['klien'] }}</h3>
                            <p class="text-gray-600 italic text-sm leading-relaxed">
                                {{ $portofolio['desk_singkat'] }}
                            </p>
                        </div>
                    </div>
                </div>
                @empty
                    <p class="text-center text-gray-400">Belum ada data portofolio.</p>
                @endforelse
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>

{{-- Dokumentasi --}}
<section class="py-16 px-4 md:px-8 lg:px-16 bg-white" 
    x-data="{ 
        currentPage: 1,
        pageSize: 12,
        selectedCategory: 'All',
        allData: {{ json_encode($dataDokumentasi) }},
        
        get filteredData() {
            if (this.selectedCategory === 'All') return this.allData;
            return this.allData.filter(item => item.jenisLayanan === this.selectedCategory);
        },

        get paginatedData() {
            let start = (this.currentPage - 1) * this.pageSize;
            let end = start + this.pageSize;
            return this.filteredData.slice(start, end);
        },

        get totalPages() {
            return Math.ceil(this.filteredData.length / this.pageSize);
        }
    }">
    <div class="max-w-7xl mx-auto">
        
        <div class="mb-10">
            <h2 class="text-4xl font-bold text-[#0B3C5D] inline-block border-b-4 border-[#0B3C5D] pb-2">
                Dokumentasi
            </h2>
        </div>

        {{-- Filter Kategori --}}
        <div class="flex flex-wrap justify-center gap-6 mb-12 text-sm md:text-base font-semibold text-gray-600">
            <button 
                @click="selectedCategory = 'All'; currentPage = 1"
                :class="selectedCategory === 'All' ? 'text-[#008080] border-b-2 border-[#008080] pb-1' : 'hover:text-[#008080] transition-colors'">
                All
            </button>
            @foreach($daftarLayananDok as $listLayananDok)
            <button 
                @click="selectedCategory = '{{ $listLayananDok }}'; currentPage = 1"
                :class="selectedCategory === '{{ $listLayananDok }}' ? 'text-[#008080] border-b-2 border-[#008080] pb-1' : 'hover:text-[#008080] transition-colors'">
                {{ $listLayananDok }}
            </button>
            @endforeach
        </div>

        {{-- Grid Dokumentasi --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 min-h-[400px]">
            <template x-for="(doc, index) in paginatedData" :key="index">
                <div class="group cursor-pointer" x-transition:enter="transition ease-out duration-300">
                    <div class="w-full aspect-[4/3] bg-gray-100 rounded-lg overflow-hidden mb-4 border border-gray-200 flex items-center justify-center relative">
                        <img :src="doc.gambar_url" :alt="doc.lokasi" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 bg-black/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    </div>
                    <div class="space-y-1">
                        <h3 class="font-bold text-lg text-gray-900" x-text="doc.tanggal + ' - ' + doc.lokasi"></h3>
                        <p class="text-gray-500 text-sm" x-text="doc.jenisLayanan"></p>
                    </div>
                </div>
            </template>
        </div>

        {{-- Navigasi Paginasi --}}
        <div x-show="totalPages > 1" class="mt-16 flex justify-center items-center space-x-2">
            {{-- Tombol Prev --}}
            <button @click="if(currentPage > 1) currentPage--" 
                class="p-2 rounded-md hover:bg-gray-100 disabled:opacity-30" :disabled="currentPage === 1">
                <i class="bi bi-chevron-left"></i>
            </button>

            {{-- Nomor Halaman --}}
            <template x-for="page in totalPages" :key="page">
                <button @click="currentPage = page" 
                    :class="currentPage === page ? 'bg-[#008080] text-white' : 'text-gray-600 hover:bg-gray-100'"
                    class="w-10 h-10 rounded-full font-bold transition-all"
                    x-text="page">
                </button>
            </template>

            {{-- Tombol Next --}}
            <button @click="if(currentPage < totalPages) currentPage++" 
                class="p-2 rounded-md hover:bg-gray-100 disabled:opacity-30" :disabled="currentPage === totalPages">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>

        {{-- Empty State --}}
        <div x-show="filteredData.length === 0" class="text-center py-20">
            <p class="text-gray-400 italic">Tidak ada dokumentasi untuk kategori ini.</p>
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
                                @forelse ($dataHubungi['alamat'] as $alamat)
                                <p class="text-slate-500">{{$alamat}}</p>
                                @empty
                                <span></span>
                                @endforelse
                        </div>
                    </div>
                    

                    <div class="flex items-center p-5 bg-white rounded-2xl shadow-sm border border-slate-100 transition-hover hover:shadow-md">
                        <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center mr-5 shrink-0">
                            <i class="bi bi-phone w-6 h-6"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900">Telepon</h3>
                                @forelse ($dataHubungi['no_telepon'] as $no_telepon)
                                <a class="text-slate-500 hover:text-indigo-600" href="https://wa.me/{{ str_replace([' ', '+', '-'], '', $no_telepon ?? '') }}" >{{$no_telepon}}</a></br>
                                @empty
                                <span></span>
                                @endforelse
                        </div>
                    </div>

                    <div class="flex items-center p-5 bg-white rounded-2xl shadow-sm border border-slate-100 transition-hover hover:shadow-md">
                        <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center mr-5 shrink-0">
                            <i class="bi bi-envelope w-6 h-6"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900">Email</h3>
                                @forelse ($dataHubungi['email'] as $email)
                                <a class="text-slate-500 hover:text-indigo-600 font-medium" href="mailto:{{ $email ?? '' }}">{{$email}}</a></br>
                                @empty
                                <span></span>
                                @endforelse
                        </div>
                    </div>

                    <div class="flex items-center p-5 bg-white rounded-2xl shadow-sm border border-slate-100 transition-hover hover:shadow-md">
                        <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center mr-5 shrink-0">
                            <i class="bi bi-clock w-6 h-6"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900">Jam Kerja</h3>
                            @if($dataHubungi['senin_jumat'] && $dataHubungi['senin_jumat'] != '00:00-00:00')
                            <p class="text-slate-500">{{'Senin - Jumat : '.$dataHubungi['senin_jumat']}}</p>
                            @endif
                            @if($dataHubungi['sabtu'] && $dataHubungi['sabtu'] != '00:00-00:00')
                            <p class="text-slate-500">Sabtu&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;{{$dataHubungi['sabtu']}}</p>
                            @endif
                            @if($dataHubungi['minggu'] && $dataHubungi['minggu'] != '00:00-00:00')
                            <p class="text-slate-500">Minggu&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;{{$dataHubungi['minggu']}}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </section>

            <section id="kirimPesan" class="bg-white p-10 rounded-3xl shadow-2xl shadow-slate-200 border border-slate-100">
                <h2 class="text-2xl font-bold mb-8 text-slate-900">Kirim Pesan kepada Kami</h2>
            
                <form action="{{route('landingPage.kirimPesan')}}" method="POST" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" placeholder="Contoh: Budi Santoso" required
                                class="w-full p-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Email</label>
                            <input type="email" name="email" placeholder="budi@email.com" required
                                class="w-full p-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Subject</label>
                        <div class="relative">
                            <select name="subject" class="w-full p-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white appearance-none transition-all">
                                <option>Pertanyaan Umum</option>
                                <option>Layanan</option>
                                <option>Kerja Sama</option>
                                <option>Lainnya</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Pesan</label>
                        <textarea name="pesan" rows="5" placeholder="Tuliskan pesan Anda di sini..." required
                            class="w-full p-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all"></textarea>
                    </div>

                    <button type="submit" class="w-full md:w-auto px-10 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition-all shadow-lg shadow-indigo-200 active:scale-95">
                        Kirim Pesan
                    </button>
                    @if(session('success'))
                        <script>
                            alert("{{ session('success') }}");
                        </script>
                    @endif
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
                        <img src="{{ optional($dataHubungi)->logo ? $dataHubungi->logo : asset('assets/images/landing_page/logo.png') }}" alt="Logo Tunas Jaya" class="w-auto h-full object-contain">
                    </div>

                    <p class="text-gray-300 text-sm leading-relaxed max-w-xs">
                        Ada pertanyaan atau ide? <br>
                        Jangan ragu untuk menghubungi kami kami senang mendengar dari Anda!
                    </p>

                    {{-- Social Media Icons --}}
                    <div class="flex gap-3">
                        <a href="{{ $dataHubungi['twitter'] ?? '#' }}" class="w-10 h-10 border border-white/20 rounded-full flex items-center justify-center hover:bg-white/10 transition-all {{ !empty($dataHubungi['twitter']) ? '' : 'pointer-events-none opacity-50' }}">
                            <i class="bi bi-twitter w-5 h-5 text-white"></i>
                        </a>
                        <a href="{{ $dataHubungi['facebook'] ?? '#' }}" class="w-10 h-10 border border-white/20 rounded-full flex items-center justify-center hover:bg-white/10 transition-all {{ !empty($dataHubungi['facebook']) ? '' : 'pointer-events-none opacity-50' }}">
                            <i class="bi bi-facebook w-5 h-5 text-white"></i>
                        </a>
                        <a href="{{ $dataHubungi['ig'] ?? '#' }}" class="w-10 h-10 border border-white/20 rounded-full flex items-center justify-center hover:bg-white/10 transition-all {{ !empty($dataHubungi['ig']) ? '' : 'pointer-events-none opacity-50' }}">
                            <i class="bi bi-instagram w-5 h-5 text-white"></i>
                        </a>
                        <a href="{{ $dataHubungi['linkedIn'] ?? '#' }}" class="w-10 h-10 border border-white/20 rounded-full flex items-center justify-center hover:bg-white/10 transition-all {{ !empty($dataHubungi['linkedIn']) ? '' : 'pointer-events-none opacity-50' }}">
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
                    @forelse($daftarLayananDok as $listLayananDok)
                    <li>
                        <a href="#layanan" 
                        @click="$dispatch('pilih-layanan', { nama: '{{ $listLayananDok }}' })"
                        class="hover:text-white transition-colors cursor-pointer">
                            {{ $listLayananDok }}
                        </a>
                    </li>
                    @empty
                    @endforelse
                </ul>
            </div>

                {{-- Kolom 4: Hubungi Kami --}}
                <div class="space-y-6">
                    <h4 class="text-lg font-bold mb-6">Hubungi Kami</h4>
                    
                    <div class="text-gray-300 text-sm leading-relaxed">
                        @forelse ($dataHubungi['alamat'] as $alamat)
                        <p>{{$alamat}}</p>
                        @empty
                        <span></span>
                        @endforelse
                    </div>

                    <div class="text-sm">
                        <p class="font-bold text-white mb-1">Telepon:</p>
                        @forelse ($dataHubungi['no_telepon'] as $no_telepon)
                        <a class="text-gray-300 hover:text-blue-300" href="https://wa.me/{{ str_replace([' ', '+', '-'], '', $no_telepon ?? '') }}" >| {{$no_telepon}} </a>
                        @empty
                        <span></span>
                        @endforelse
                    </div>

                    <div class="text-sm">
                        <p class="font-bold text-white mb-1">Email:</p>
                        @forelse ($dataHubungi['email'] as $email)
                        <a class="text-gray-300 hover:text-blue-300" href="mailto:{{ $email ?? '' }}">{{$email}} </a>
                        @empty
                        <span></span>
                        @endforelse
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

    
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const swiper = new Swiper('.portofolioSwiper', {
        slidesPerView: 1,
        spaceBetween: 20,
        speed: 800,
        
        autoplay: {
            delay: 4000,
            disableOnInteraction: false,
        },

        breakpoints: {
            768: { slidesPerView: 2, spaceBetween: 30 },
            1024: { slidesPerView: 3, spaceBetween: 30 }
        },

        pagination: {
            el: '.swiper-pagination',
            clickable: true,
            renderBullet: function (index, className) {
                return '<span class="' + className + '"></span>';
            },
        },
    });
});
</script>
</body>
