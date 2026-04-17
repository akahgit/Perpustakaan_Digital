@extends('layouts.app')

@section('title', 'Beranda - Perpustakaan Digital')

@section('content')
<div class="bg-[#020617] min-h-screen text-slate-200 overflow-x-hidden">

    <!-- ══ 1. HERO SECTION (ULTRA MODERN) ══ -->
    <section class="relative min-h-[90vh] flex items-center pt-10 pb-20 overflow-hidden">
        {{-- Floating Orbs Decoration --}}
        <div class="absolute top-[10%] left-[5%] w-[400px] h-[400px] bg-indigo-600/20 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute bottom-[10%] right-[5%] w-[500px] h-[500px] bg-purple-600/10 rounded-full blur-[150px] animate-bounce-slow"></div>

        <div class="max-w-[1600px] mx-auto px-6 lg:px-12 relative z-10 w-full">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                
                {{-- Left Side: Text & CTA --}}
                <div class="space-y-10 text-center lg:text-left">
                    <div class="inline-flex items-center gap-3 px-5 py-2 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-xl animate-fade-in-down">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                        </span>
                        <span class="text-[11px] font-black uppercase tracking-[0.2em] text-indigo-300">Era Baru Literasi Digital</span>
                    </div>

                    <h1 class="text-6xl lg:text-8xl font-black leading-[0.95] tracking-tight animate-fade-in-up">
                        Buka <span class="text-transparent bg-clip-text bg-gradient-to-br from-indigo-400 via-purple-400 to-pink-400">Dimensi</span> <br>
                        Baru Pengetahuan.
                    </h1>

                    <p class="text-lg text-slate-400 max-w-xl mx-auto lg:mx-0 leading-relaxed opacity-80 animate-fade-in-up" style="animation-delay: 0.2s">
                        Sistem perpustakaan tercanggih yang menghadirkan koleksi buku premium dalam genggaman Anda. Pinjam, baca, dan jelajahi ribuan judul dengan satu sentuhan.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-5 justify-center lg:justify-start animate-fade-in-up" style="animation-delay: 0.4s">
                        <a href="{{ route('katalog') }}" 
                           class="group px-10 py-5 bg-indigo-600 hover:bg-indigo-500 text-white font-black rounded-3xl shadow-2xl shadow-indigo-600/20 transition-all flex items-center justify-center gap-4 hover:-translate-y-1">
                            <i class="fas fa-rocket"></i>
                            Mulai Menjelajah
                            <i class="fas fa-chevron-right text-xs group-hover:translate-x-2 transition-transform"></i>
                        </a>
                        <a href="#fitur" 
                           class="px-10 py-5 bg-white/5 hover:bg-white/10 border border-white/10 text-white font-bold rounded-3xl backdrop-blur-xl transition-all flex items-center justify-center gap-3">
                            <i class="fas fa-play text-[10px] text-indigo-400"></i>
                            Lihat Cara Kerja
                        </a>
                    </div>

                    {{-- Stats with Glass Effect --}}
                    <div class="grid grid-cols-3 gap-6 pt-12 animate-fade-in-up" style="animation-delay: 0.6s">
                        <div class="p-6 rounded-[32px] bg-white/3 border border-white/5 backdrop-blur-md">
                            <div class="text-3xl font-black text-white mb-1">{{ number_format($totalEksemplar ?? 0) }}+</div>
                            <div class="text-[9px] font-black uppercase tracking-widest text-slate-500">Total Koleksi</div>
                        </div>
                        <div class="p-6 rounded-[32px] bg-white/3 border border-white/5 backdrop-blur-md">
                            <div class="text-3xl font-black text-white mb-1">{{ number_format($anggotaAktif ?? 0) }}+</div>
                            <div class="text-[9px] font-black uppercase tracking-widest text-slate-500">Anggota Aktif</div>
                        </div>
                        <div class="p-6 rounded-[32px] bg-white/3 border border-white/5 backdrop-blur-md">
                            <div class="text-3xl font-black text-white mb-1">{{ number_format($bukuBaru ?? 0) }}+</div>
                            <div class="text-[9px] font-black uppercase tracking-widest text-slate-500">Judul Baru</div>
                        </div>
                    </div>
                </div>

                {{-- Right Side: Modern Staggered Philosophy Books --}}
                <div class="relative hidden lg:block animate-fade-in-right">
                    {{-- Label section --}}
                    <div class="flex items-center justify-between mb-6">
                        <span class="text-[9px] font-black uppercase tracking-[0.25em] text-indigo-500/70">Featured Collection</span>
                        <div class="h-px flex-1 mx-4 bg-gradient-to-r from-indigo-500/20 to-transparent"></div>
                        <span class="text-[9px] font-black uppercase tracking-widest text-slate-600">4 Filsafat Pilihan</span>
                    </div>

                    {{-- Staggered Books Container --}}
                    <div class="relative flex justify-center items-center min-h-[550px]">
                        
                        {{-- Book 1: Kant - Bottom Left (Paling belakang) --}}
                        <div class="absolute bottom-0 left-0 w-[180px] md:w-[200px] group cursor-pointer transition-all duration-500 hover:z-30 hover:-translate-y-4 z-10 rotate-[-8deg] hover:rotate-0"
                             style="filter: drop-shadow(0 20px 15px rgba(0,0,0,0.5));">
                            <div class="relative rounded-[20px] overflow-hidden aspect-[3/4]">
                                <img src="https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=400&h=533&fit=crop" 
                                     alt="Critique of Pure Reason"
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-70 group-hover:opacity-90 transition-opacity"></div>
                                <div class="absolute bottom-0 left-0 right-0 p-4">
                                    <span class="inline-block text-[7px] font-black uppercase tracking-[0.15em] text-indigo-300 bg-indigo-500/30 px-2 py-0.5 rounded-full mb-1 backdrop-blur-sm">Filsafat</span>
                                    <h4 class="text-white font-black text-[11px] leading-tight">Critique of Pure Reason</h4>
                                    <p class="text-indigo-300/70 text-[8px] font-semibold">Immanuel Kant</p>
                                </div>
                            </div>
                            <div class="absolute -top-3 -right-3 bg-indigo-600/90 backdrop-blur-sm text-white text-[7px] font-black px-2 py-0.5 rounded-full border border-white/20 shadow-lg">
                                Best Seller
                            </div>
                        </div>

                        {{-- Book 2: Heidegger - Bottom Right (Sedikit lebih depan) --}}
                        <div class="absolute bottom-0 right-0 w-[180px] md:w-[200px] group cursor-pointer transition-all duration-500 hover:z-30 hover:-translate-y-4 z-20 rotate-[8deg] hover:rotate-0"
                             style="filter: drop-shadow(0 20px 15px rgba(0,0,0,0.5));">
                            <div class="relative rounded-[20px] overflow-hidden aspect-[3/4]">
                                <img src="https://images.unsplash.com/photo-1532012197267-da84d127e765?w=400&h=533&fit=crop" 
                                     alt="Being and Time"
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-70 group-hover:opacity-90 transition-opacity"></div>
                                <div class="absolute bottom-0 left-0 right-0 p-4">
                                    <span class="inline-block text-[7px] font-black uppercase tracking-[0.15em] text-amber-300 bg-amber-500/30 px-2 py-0.5 rounded-full mb-1 backdrop-blur-sm">Eksistensial</span>
                                    <h4 class="text-white font-black text-[11px] leading-tight">Being and Time</h4>
                                    <p class="text-amber-300/70 text-[8px] font-semibold">M. Heidegger</p>
                                </div>
                            </div>
                        </div>

                        {{-- Book 3: Aristotle - Center (Paling depan - Hero position) --}}
                        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[200px] md:w-[240px] group cursor-pointer transition-all duration-500 hover:z-30 hover:-translate-y-6 z-30"
                             style="filter: drop-shadow(0 25px 20px rgba(0,0,0,0.6));">
                            <div class="relative rounded-[24px] overflow-hidden aspect-[3/4] border-2 border-white/20">
                                <img src="https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?w=400&h=533&fit=crop" 
                                     alt="Nicomachean Ethics"
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/30 to-transparent opacity-75 group-hover:opacity-95 transition-opacity"></div>
                                <div class="absolute bottom-0 left-0 right-0 p-5">
                                    <span class="inline-block text-[8px] font-black uppercase tracking-[0.15em] text-emerald-300 bg-emerald-500/30 px-2 py-0.5 rounded-full mb-2 backdrop-blur-sm">Etika</span>
                                    <h4 class="text-white font-black text-sm leading-tight">Nicomachean Ethics</h4>
                                    <p class="text-emerald-300/70 text-[9px] font-semibold">Aristotle</p>
                                    <div class="flex items-center gap-1 mt-2 text-amber-400 text-[9px] font-black">
                                        <i class="fas fa-star text-[8px]"></i> 4.7
                                    </div>
                                </div>
                            </div>
                            <div class="absolute -top-4 -right-4 bg-emerald-600/90 backdrop-blur-sm text-white text-[8px] font-black px-2.5 py-1 rounded-full border border-white/20 shadow-lg">
                                ⭐ Klasik
                            </div>
                        </div>

                        {{-- Book 4: Plato - Top Center (Melayang di atas) --}}
                        <div class="absolute top-0 left-1/2 transform -translate-x-1/2 w-[160px] md:w-[180px] group cursor-pointer transition-all duration-500 hover:z-30 hover:-translate-y-6 z-15 rotate-[12deg] hover:rotate-0"
                             style="filter: drop-shadow(0 15px 12px rgba(0,0,0,0.4));">
                            <div class="relative rounded-[20px] overflow-hidden aspect-[3/4]">
                                <img src="https://images.unsplash.com/photo-1544716278-e513176f20b5?w=400&h=533&fit=crop" 
                                     alt="The Republic"
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-70 group-hover:opacity-90 transition-opacity"></div>
                                <div class="absolute bottom-0 left-0 right-0 p-3">
                                    <span class="inline-block text-[7px] font-black uppercase tracking-[0.15em] text-purple-300 bg-purple-500/30 px-2 py-0.5 rounded-full mb-1 backdrop-blur-sm">Metafisika</span>
                                    <h4 class="text-white font-black text-[10px] leading-tight">The Republic</h4>
                                    <p class="text-purple-300/70 text-[7px] font-semibold">Plato</p>
                                </div>
                            </div>
                            <div class="absolute -top-2 -right-2 bg-purple-600/90 backdrop-blur-sm text-white text-[7px] font-black px-2 py-0.5 rounded-full border border-white/20 shadow-lg">
                                Top Rated
                            </div>
                        </div>

                        {{-- Decorative floating elements --}}
                        <div class="absolute -z-10 w-full h-full">
                            <div class="absolute top-1/4 left-1/4 w-32 h-32 bg-indigo-500/10 rounded-full blur-3xl animate-pulse"></div>
                            <div class="absolute bottom-1/4 right-1/4 w-40 h-40 bg-purple-500/10 rounded-full blur-3xl animate-pulse delay-1000"></div>
                        </div>
                    </div>

                    {{-- Decorative text behind books --}}
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none -z-5">
                        <div class="text-center opacity-5">
                            <i class="fas fa-book-open text-9xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ══ 2. BENTO FEATURES SECTION ══ -->
    <section id="fitur" class="py-32 relative">
        <div class="max-w-[1600px] mx-auto px-6 lg:px-12">
            <div class="text-center mb-20">
                <h4 class="text-indigo-500 font-black uppercase tracking-[0.3em] text-[10px] mb-4">Core Capabilities</h4>
                <h2 class="text-5xl lg:text-6xl font-black text-white">Teknologi <span class="italic text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-purple-400">Pintar</span>.</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 auto-rows-[300px]">
                {{-- Bento 1: Large --}}
                <div class="md:col-span-2 md:row-span-2 relative group rounded-[48px] overflow-hidden bg-white/3 border border-white/5 p-12 flex flex-col justify-end">
                    <div class="absolute top-12 left-12 w-24 h-24 bg-indigo-600 rounded-[32px] flex items-center justify-center text-4xl shadow-2xl shadow-indigo-600/30 group-hover:scale-110 transition-transform">
                        <i class="fas fa-search-plus text-white"></i>
                    </div>
                    <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-indigo-500/10 rounded-full blur-[100px] group-hover:bg-indigo-500/20 transition-all"></div>
                    <div class="relative z-10">
                        <h3 class="text-3xl font-black text-white mb-4">Smart Catalog Searching</h3>
                        <p class="text-slate-400 max-w-md leading-relaxed">Cari jutaan judul dengan filter kategori yang intuitif. Temukan buku impian Anda dalam hitungan detik dengan alur pencarian yang dioptimalkan.</p>
                    </div>
                </div>

                {{-- Bento 2: Square --}}
                <div class="relative group rounded-[48px] overflow-hidden bg-gradient-to-br from-purple-600 to-indigo-700 p-10 flex flex-col justify-between">
                    <div class="relative z-10">
                        <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center text-xl text-white mb-6">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <h3 class="text-2xl font-black text-white mb-3">Quick Borrow</h3>
                        <p class="text-white/70 text-sm leading-relaxed">Ajukan peminjaman secara instan tanpa birokrasi yang rumit.</p>
                    </div>
                    <i class="fas fa-hand-holding-heart absolute -bottom-4 -right-4 text-white/5 text-9xl"></i>
                </div>

                {{-- Bento 3: Square --}}
                <div class="relative group rounded-[48px] overflow-hidden bg-white/3 border border-white/5 p-10 flex flex-col justify-between">
                    <div class="relative z-10 text-center flex flex-col items-center">
                        <div class="w-20 h-20 bg-emerald-500/10 border border-emerald-500/20 rounded-full flex items-center justify-center text-3xl text-emerald-400 mb-6 group-hover:rotate-12 transition-transform">
                            <i class="fas fa-qrcode"></i>
                        </div>
                        <h3 class="text-xl font-black text-white mb-2 text-center">QRIS Ready</h3>
                        <p class="text-slate-500 text-xs text-center">Bayar denda dengan aman melalui sistem QRIS terintegrasi.</p>
                    </div>
                </div>

                {{-- Bento 4: Wide --}}
                <div class="md:col-span-3 rounded-[40px] bg-white/3 border border-white/5 p-8 flex items-center justify-between group overflow-hidden relative">
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-indigo-500/5 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
                    <div class="flex items-center gap-8">
                        <div class="p-6 bg-white/5 rounded-3xl text-3xl text-indigo-400">
                            <i class="fas fa-history"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-black text-white mb-1">Full Transaction Transparency</h3>
                            <p class="text-slate-500 text-sm italic">Pantau seluruh riwayat peminjaman, pengembalian, dan status denda secara real-time.</p>
                        </div>
                    </div>
                    <a href="{{ route('riwayat') }}" class="hidden sm:flex px-8 py-4 bg-white/10 hover:bg-white/20 rounded-2xl text-xs font-black uppercase tracking-widest transition">Lihat Riwayat Sekarang</a>
                </div>
            </div>
        </div>
    </section>

    <!-- ══ 3. SWIPER NEW ARRIVALS (GLASSMOPHISM) ══ -->
    <section class="py-24 relative overflow-hidden">
        <div class="max-w-[1600px] mx-auto px-6 lg:px-12 relative z-10">
            <div class="flex flex-col md:flex-row items-end justify-between mb-16 gap-4">
                <div>
                    <h4 class="text-indigo-500 font-black uppercase tracking-[0.3em] text-[10px] mb-4">Latest Additions</h4>
                    <h2 class="text-5xl font-black text-white">Koleksi <span class="text-indigo-500">Terbaru</span>.</h2>
                </div>
                <div class="flex gap-3">
                    <button class="swiper-prev-new w-14 h-14 bg-white/5 border border-white/10 rounded-2xl flex items-center justify-center text-slate-400 hover:text-white hover:bg-white/10 transition shadow-2xl">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="swiper-next-new w-14 h-14 bg-white/5 border border-white/10 rounded-2xl flex items-center justify-center text-slate-400 hover:text-white hover:bg-white/10 transition shadow-2xl">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>

            {{-- Swiper Container --}}
            <div class="swiper new-arrivals-slider-premium !overflow-visible">
                <div class="swiper-wrapper">
                    @foreach($bukuTerbaru as $buku)
                    <div class="swiper-slide !w-[320px] group">
                        <div class="relative bg-white/5 border border-white/10 rounded-[48px] p-4 transition-all duration-500 group-hover:-translate-y-4 group-hover:border-indigo-500/30 group-hover:bg-white/10">
                            <div class="aspect-[3/4] relative rounded-[36px] overflow-hidden mb-6 shadow-2xl">
                                @if($buku->cover_buku)
                                    <img src="{{ Storage::url($buku->cover_buku) }}" alt="{{ $buku->judul }}" 
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-slate-800 to-slate-900 flex flex-col items-center justify-center text-slate-700">
                                        <i class="fas fa-book-open text-6xl"></i>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex flex-col justify-end p-8">
                                    <a href="{{ route('katalog.show', $buku->id_buku) }}" 
                                       class="w-full py-4 bg-white text-black font-black text-xs rounded-2xl hover:bg-indigo-600 hover:text-white transition">Lihat Detail</a>
                                </div>
                            </div>
                            <div class="px-4 pb-4">
                                <div class="flex items-center justify-between mb-2">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-indigo-400">{{ $buku->kategori->nama_kategori ?? 'Umum' }}</p>
                                    @if($buku->averageRating > 0)
                                    <div class="flex items-center gap-1 text-amber-400 text-[10px] font-black">
                                        <i class="fas fa-star"></i>
                                        <span>{{ number_format($buku->averageRating, 1) }}</span>
                                    </div>
                                    @endif
                                </div>
                                <h4 class="text-white font-black text-lg truncate mb-1">{{ $buku->judul }}</h4>
                                <p class="text-slate-500 text-xs font-bold">{{ $buku->pengarang }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- ══ 4. MODERN STEPS SECTION ══ -->
    <section class="py-32 border-t border-white/5 relative">
        <div class="max-w-[1600px] mx-auto px-6 lg:px-12">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div class="space-y-12">
                    <div>
                        <h4 class="text-indigo-500 font-black uppercase tracking-[0.3em] text-[10px] mb-4">Onboarding</h4>
                        <h2 class="text-5xl font-black text-white">Segalanya Kini <br> Jadi Lebih <span class="text-indigo-500 italic">Simpel</span>.</h2>
                    </div>

                    <div class="space-y-8">
                        @php
                            $steps = [
                                ['icon' => 'fas fa-search', 'title' => 'Temukan Buku', 'desc' => 'Gunakan fitur pencarian pintar untuk menemukan buku berdasarkan genre atau penulis.'],
                                ['icon' => 'fas fa-paper-plane', 'title' => 'Ajukan Pinjam', 'desc' => 'Satu klik untuk mengajukan peminjaman. Tunggu persetujuan petugas dalam hitungan menit.'],
                                ['icon' => 'fas fa-book-reader', 'title' => 'Nikmati Bacaan', 'desc' => 'Buku siap dipinjam! Nikmati konten berkualitas di mana saja.'],
                            ];
                        @endphp
                        @foreach($steps as $i => $step)
                        <div class="flex gap-6 group">
                            <div class="w-16 h-16 bg-white/5 border border-white/10 rounded-3xl flex items-center justify-center text-2xl text-indigo-400 group-hover:bg-indigo-600 group-hover:text-white transition-all shrink-0">
                                <i class="{{ $step['icon'] }}"></i>
                            </div>
                            <div>
                                <h4 class="text-white font-black text-lg mb-2">{{ $step['title'] }}</h4>
                                <p class="text-slate-500 text-sm leading-relaxed max-w-sm">{{ $step['desc'] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Visualization --}}
                <div class="relative bg-white/3 border border-white/5 rounded-[60px] p-12 overflow-hidden aspect-video flex items-center justify-center">
                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/10 via-transparent to-purple-500/10"></div>
                    <div class="relative z-10 text-center">
                        <div class="w-32 h-32 bg-indigo-600 rounded-[40px] shadow-2xl shadow-indigo-600/40 flex items-center justify-center text-5xl text-white mb-8 mx-auto animate-bounce-slow">
                            <i class="fas fa-check-double"></i>
                        </div>
                        <h3 class="text-2xl font-black text-white">Ready for Reading?</h3>
                        <p class="text-slate-500 text-sm mt-4 italic">Alur proses yang telah kami optimasi 10x lebih cepat.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ══ 5. FINAL CTA ══ -->
    <section class="py-32 relative">
        <div class="max-w-[1600px] mx-auto px-6 lg:px-12">
            <div class="relative px-8 py-24 rounded-[64px] bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-800 overflow-hidden text-center">
                <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-white/5 rounded-full blur-[100px] -mr-[300px] -mt-[300px]"></div>
                <div class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-black/20 rounded-full blur-[100px] -ml-[300px] -mb-[300px]"></div>
                
                <h2 class="text-5xl lg:text-7xl font-black text-white mb-8 relative z-10 leading-tight">Mulai Perjalanan <br> Literasi Anda.</h2>
                <div class="flex justify-center gap-6 relative z-10">
                    <a href="{{ route('katalog') }}" 
                       class="px-12 py-6 bg-white text-black font-black rounded-3xl shadow-2xl hover:bg-slate-100 transition transform hover:-translate-y-1">
                        Akses Katalog Sekarang
                    </a>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const swiper = new Swiper('.new-arrivals-slider-premium', {
            slidesPerView: 'auto',
            spaceBetween: 40,
            freeMode: true,
            grabCursor: true,
            navigation: {
                nextEl: '.swiper-next-new',
                prevEl: '.swiper-prev-new',
            },
            autoplay: {
                delay: 3500,
                disableOnInteraction: false,
            },
        });
    });
</script>
@endpush