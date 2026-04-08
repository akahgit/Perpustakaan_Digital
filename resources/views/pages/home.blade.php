@extends('layouts.app')

@section('title', 'Beranda - Perpustakaan Digital')

@section('content')
    <div class="bg-[#050505] min-h-screen">

        <!-- 1. HERO SECTION -->
        <section class="relative pt-20 pb-32 overflow-hidden">
            <!-- Background Glow Effects -->
            <div
                class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[500px] bg-purple-600/20 rounded-full blur-[120px] pointer-events-none">
            </div>
            <div
                class="absolute bottom-0 right-0 w-[600px] h-[600px] bg-indigo-600/10 rounded-full blur-[100px] pointer-events-none">
            </div>

            <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="grid lg:grid-cols-2 gap-16 items-center">

                    <!-- Left Content -->
                    <div class="space-y-8 text-center lg:text-left">
                        <!-- Badge -->
                        <div
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/5 border border-white/10 backdrop-blur-sm">
                            <span class="w-2 h-2 bg-purple-500 rounded-full animate-pulse"></span>
                            <span class="text-sm font-medium text-gray-300">Selamat datang di Perpustakaan Digital</span>
                        </div>

                        <!-- Headline -->
                        <h1 class="text-5xl lg:text-7xl font-extrabold leading-tight tracking-tight">
                            Jelajahi Dunia<br>
                            <span
                                class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 via-pink-400 to-indigo-400">
                                Melalui Buku
                            </span>
                        </h1>

                        <!-- Description -->
                        <p class="text-lg text-gray-400 max-w-xl mx-auto lg:mx-0 leading-relaxed">
                            Pinjam buku kapan saja, di mana saja. Akses ribuan koleksi buku dari berbagai genre dan penulis
                            terbaik.
                        </p>

                        <!-- Buttons -->
                        <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                            <a href="{{ route('katalog') }}"
                                class="group px-8 py-4 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white font-bold rounded-full shadow-xl shadow-purple-500/25 transition transform hover:scale-105 flex items-center justify-center gap-2">
                                <i class="fas fa-compass"></i>
                                Jelajahi Katalog
                                <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                            </a>
                            <a href="#cara-kerja"
                                class="px-8 py-4 bg-white/5 hover:bg-white/10 backdrop-blur-md border border-white/10 text-white font-semibold rounded-full transition flex items-center justify-center gap-2">
                                <i class="fas fa-play-circle text-purple-400"></i>
                                Pelajari Lebih
                            </a>
                        </div>

                        <!-- Stats DINAMIS -->
                        <div class="grid grid-cols-3 gap-8 pt-8 border-t border-white/10 mt-8">
                            <div>
                                <div class="text-3xl lg:text-4xl font-bold text-white mb-1">
                                    {{ number_format($totalEksemplar ?? 0) }}+</div>
                                <div class="text-sm text-gray-500 font-medium">Koleksi Buku</div>
                            </div>
                            <div>
                                <div class="text-3xl lg:text-4xl font-bold text-white mb-1">
                                    {{ number_format($anggotaAktif ?? 0) }}+</div>
                                <div class="text-sm text-gray-500 font-medium">Anggota Aktif</div>
                            </div>
                            <div>
                                <div class="text-3xl lg:text-4xl font-bold text-white mb-1">
                                    {{ number_format($bukuBaru ?? 0) }}+</div>
                                <div class="text-sm text-gray-500 font-medium">Judul Baru</div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Content (Floating Books) -->
                    <div class="relative hidden lg:block h-[600px]">
                        <!-- Central Glow -->
                        <div
                            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[400px] h-[400px] bg-gradient-to-tr from-purple-500/30 via-indigo-500/30 to-pink-500/30 rounded-full blur-3xl">
                        </div>

                        <!-- Book Cards Stack (Visual Only) -->
                        <div class="relative w-full h-full flex items-center justify-center">
                            <!-- Book 1 (Back - Blue) -->
                            <div
                                class="absolute w-64 h-80 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-2xl shadow-2xl transform rotate-[-12deg] translate-x-[-80px] translate-y-[20px] border border-white/10 z-10">
                                <div class="absolute inset-0 flex flex-col items-center justify-center p-6">
                                    <i class="fas fa-book text-6xl text-white/30 mb-4"></i>
                                    <div class="text-white/80 text-center">
                                        <div class="font-bold text-lg">Bumi Manusia</div>
                                        <div class="text-xs opacity-70">Pramoedya A.T.</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Book 2 (Middle - Purple/Pink) -->
                            <div
                                class="absolute w-64 h-80 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl shadow-2xl transform rotate-[-6deg] translate-x-[-40px] border border-white/10 z-20">
                                <div class="absolute inset-0 flex flex-col items-center justify-center p-6">
                                    <i class="fas fa-book text-6xl text-white/30 mb-4"></i>
                                    <div class="text-white text-center">
                                        <div class="font-bold text-lg mb-1">Laskar Pelangi</div>
                                        <div class="text-xs opacity-80">Andrea Hirata</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Book 3 (Front - Indigo) -->
                            <div
                                class="absolute w-64 h-80 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-2xl shadow-2xl transform rotate-[6deg] translate-x-[40px] border border-white/10 z-30">
                                <div class="absolute inset-0 flex flex-col items-center justify-center p-6">
                                    <i class="fas fa-book text-6xl text-white/30 mb-4"></i>
                                    <div class="text-white/80 text-center">
                                        <div class="font-bold text-lg">Filosofi Teras</div>
                                        <div class="text-xs opacity-70">Henry Manampiring</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 2. FITUR UNGGULAN -->
        <section id="fitur" class="py-24 bg-[#050505] relative">
            <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="text-center mb-16">
                    <div class="inline-block px-4 py-1.5 mb-4 rounded-full bg-white/5 border border-white/10">
                        <span class="text-purple-300 text-xs font-bold uppercase tracking-wider">Fitur Unggulan</span>
                    </div>
                    <h2 class="text-4xl lg:text-5xl font-bold mb-4">
                        Kemudahan dalam <span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-400">Genggaman</span>
                    </h2>
                    <p class="text-gray-400 text-lg max-w-2xl mx-auto">
                        Nikmati berbagai fitur yang memudahkan Anda dalam meminjam dan mengelola buku.
                    </p>
                </div>

                <!-- Grid Features -->
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Feature 1: Cari Buku -->
                    <div
                        class="group p-8 bg-white/[0.03] rounded-2xl border border-white/5 hover:border-purple-500/30 hover:bg-white/[0.06] transition duration-300">
                        <div
                            class="w-14 h-14 bg-gradient-to-br from-purple-600 to-purple-700 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition duration-300 shadow-lg shadow-purple-500/20">
                            <i class="fas fa-search text-2xl text-white"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-3 text-white">Cari Buku</h3>
                        <p class="text-gray-400 text-sm leading-relaxed">Temukan buku favoritmu dengan mudah melalui katalog
                            digital kami.</p>
                    </div>

                    <!-- Feature 2: Pinjam Online -->
                    <div
                        class="group p-8 bg-white/[0.03] rounded-2xl border border-white/5 hover:border-indigo-500/30 hover:bg-white/[0.06] transition duration-300">
                        <div
                            class="w-14 h-14 bg-gradient-to-br from-indigo-600 to-indigo-700 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition duration-300 shadow-lg shadow-indigo-500/20">
                            <i class="fas fa-hand-holding-heart text-2xl text-white"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-3 text-white">Pinjam Online</h3>
                        <p class="text-gray-400 text-sm leading-relaxed">Ajukan peminjaman buku secara online tanpa perlu
                            datang langsung.</p>
                    </div>

                    <!-- Feature 3: Kembalikan Buku -->
                    <div
                        class="group p-8 bg-white/[0.03] rounded-2xl border border-white/5 hover:border-emerald-500/30 hover:bg-white/[0.06] transition duration-300">
                        <div
                            class="w-14 h-14 bg-gradient-to-br from-emerald-600 to-emerald-700 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition duration-300 shadow-lg shadow-emerald-500/20">
                            <i class="fas fa-undo text-2xl text-white"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-3 text-white">Kembalikan Buku</h3>
                        <p class="text-gray-400 text-sm leading-relaxed">Proses pengembalian buku yang simpel dan
                            transparan.</p>
                    </div>

                    <!-- Feature 4: Riwayat Lengkap -->
                    <div
                        class="group p-8 bg-white/[0.03] rounded-2xl border border-white/5 hover:border-amber-500/30 hover:bg-white/[0.06] transition duration-300">
                        <div
                            class="w-14 h-14 bg-gradient-to-br from-amber-600 to-amber-700 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition duration-300 shadow-lg shadow-amber-500/20">
                            <i class="fas fa-history text-2xl text-white"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-3 text-white">Riwayat Lengkap</h3>
                        <p class="text-gray-400 text-sm leading-relaxed">Pantau semua riwayat peminjaman dan denda dalam
                            satu tempat.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- 3. BUKU TERPOPULER (DINAMIS) -->
        <section id="katalog" class="py-24 bg-[#050505] relative border-t border-white/5">
            <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4">
                    <div>
                        <div class="inline-block px-3 py-1 mb-3 rounded-full bg-white/5 border border-white/10">
                            <span class="text-purple-300 text-xs font-bold uppercase tracking-wider">Koleksi Populer</span>
                        </div>
                        <h2 class="text-3xl lg:text-4xl font-bold">Buku Terpopuler</h2>
                    </div>
                    <a href="{{ route('katalog') }}"
                        class="text-purple-400 hover:text-purple-300 font-medium flex items-center gap-2 transition group">
                        Lihat Semua
                        <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>

                <!-- Grid Buku -->
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
                    @forelse($bukuPopuler as $item)
                        @php
                            // CEK KEAMANAN: Pastikan relasi buku ada
                            if (!$item->buku) {
                                continue; // Lewati item ini jika bukunya sudah dihapus
                            }

                            $buku = $item->buku; // Ambil objek buku

                            // Warna gradient konsisten berdasarkan ID
                            $colors = [
                                'from-purple-500 to-pink-600',
                                'from-blue-500 to-cyan-600',
                                'from-indigo-500 to-blue-600',
                                'from-emerald-500 to-teal-600',
                                'from-amber-500 to-orange-600',
                            ];
                            $colorClass = $colors[$buku->id_buku % count($colors)];

                            // Cek stok
                            $tersedia = $buku->stok_tersedia > 0;
                        @endphp

                        <div
                            class="group bg-white/[0.03] rounded-2xl border border-white/5 overflow-hidden hover:border-purple-500/30 transition duration-300">
                            <!-- Cover -->
                            <div
                                class="aspect-[2/3] bg-gradient-to-br {{ $colorClass }} flex items-center justify-center relative overflow-hidden">
                                @if ($buku->cover_buku && file_exists(public_path('storage/' . $buku->cover_buku)))
                                    <img src="{{ asset('storage/' . $buku->cover_buku) }}" alt="{{ $buku->judul }}"
                                        class="w-full h-full object-cover opacity-80 group-hover:opacity-60 transition duration-500">
                                @else
                                    <i
                                        class="fas fa-book text-5xl text-white/30 group-hover:scale-110 transition duration-500"></i>
                                @endif

                                <!-- Overlay Button -->
                                <div
                                    class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center gap-3">
                                    <a href="{{ route('katalog') }}"
                                        class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center hover:bg-white/30 transition"
                                        title="Detail">
                                        <i class="fas fa-eye text-white"></i>
                                    </a>
                                </div>

                                <!-- Badge Stok -->
                                <div
                                    class="absolute top-3 right-3 px-2 py-1 bg-black/40 backdrop-blur-md rounded-md text-xs font-medium {{ $tersedia ? 'text-green-400 border border-green-500/20' : 'text-red-400 border border-red-500/20' }} border border-white/10">
                                    {{ $tersedia ? 'Tersedia' : 'Habis' }}
                                </div>
                            </div>

                            <!-- Info -->
                            <div class="p-4">
                                <h4 class="font-bold text-white mb-1 truncate" title="{{ $buku->judul }}">
                                    {{ $buku->judul }}</h4>
                                <p class="text-sm text-gray-400 truncate" title="{{ $buku->pengarang }}">
                                    {{ $buku->pengarang }}</p>
                                <div class="flex items-center justify-between mt-3 pt-3 border-t border-white/5">
                                    <span class="text-xs text-gray-500">{{ $buku->tahun_terbit }}</span>
                                    <a href="{{ route('katalog') }}"
                                        class="text-xs font-semibold text-purple-400 hover:text-purple-300 transition">
                                        + Pinjam
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <!-- Empty State jika belum ada data peminjaman -->
                        <div class="col-span-full text-center py-10">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-white/5 mb-4">
                                <i class="fas fa-chart-bar text-2xl text-gray-500"></i>
                            </div>
                            <p class="text-gray-400 mb-4">Belum ada data peminjaman untuk menampilkan buku populer.</p>
                            <a href="{{ route('katalog') }}"
                                class="inline-block px-6 py-2 bg-purple-600 hover:bg-purple-500 text-white text-sm font-semibold rounded-full transition">
                                Lihat Semua Buku
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        <!-- 4. CARA KERJA -->
        <section id="cara-kerja" class="py-24 bg-[#050505] relative border-t border-white/5">
            <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="text-center mb-16">
                    <div class="inline-block px-3 py-1 mb-3 rounded-full bg-white/5 border border-white/10">
                        <span class="text-purple-300 text-xs font-bold uppercase tracking-wider">Cara Kerja</span>
                    </div>
                    <h2 class="text-4xl lg:text-5xl font-bold mb-4">
                        Mudah & <span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-indigo-400">Cepat</span>
                    </h2>
                    <p class="text-gray-400 text-lg max-w-2xl mx-auto">
                        Hanya butuh 3 langkah sederhana untuk meminjam buku.
                    </p>
                </div>

                <!-- Steps -->
                <div class="grid md:grid-cols-3 gap-8 relative">
                    <!-- Connecting Line (Desktop) -->
                    <div
                        class="hidden md:block absolute top-12 left-[16%] right-[16%] h-0.5 bg-gradient-to-r from-purple-600/20 via-indigo-600/20 to-blue-600/20 z-0">
                    </div>

                    <!-- Step 1 -->
                    <div class="relative z-10 text-center group">
                        <div
                            class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-purple-600 to-pink-600 rounded-2xl flex items-center justify-center shadow-xl shadow-purple-500/20 group-hover:scale-110 transition duration-300">
                            <span class="text-4xl font-bold text-white">1</span>
                        </div>
                        <h3 class="text-xl font-bold mb-3 text-white">Cari Buku</h3>
                        <p class="text-gray-400 text-sm leading-relaxed max-w-xs mx-auto">
                            Jelajahi katalog dan temukan buku yang ingin Anda pinjam.
                        </p>
                    </div>

                    <!-- Step 2 -->
                    <div class="relative z-10 text-center group">
                        <div
                            class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-2xl flex items-center justify-center shadow-xl shadow-indigo-500/20 group-hover:scale-110 transition duration-300">
                            <span class="text-4xl font-bold text-white">2</span>
                        </div>
                        <h3 class="text-xl font-bold mb-3 text-white">Ajukan Peminjaman</h3>
                        <p class="text-gray-400 text-sm leading-relaxed max-w-xs mx-auto">
                            Klik pinjam dan tentukan tanggal pengembalian.
                        </p>
                    </div>

                    <!-- Step 3 -->
                    <div class="relative z-10 text-center group">
                        <div
                            class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-blue-600 to-cyan-600 rounded-2xl flex items-center justify-center shadow-xl shadow-blue-500/20 group-hover:scale-110 transition duration-300">
                            <span class="text-4xl font-bold text-white">3</span>
                        </div>
                        <h3 class="text-xl font-bold mb-3 text-white">Ambil & Baca</h3>
                        <p class="text-gray-400 text-sm leading-relaxed max-w-xs mx-auto">
                            Ambil buku di perpustakaan dan nikmati bacaan Anda.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- 5. CTA SECTION -->
        <section class="py-24 bg-[#050505] relative">
            <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
                <div class="relative rounded-3xl overflow-hidden">
                    <!-- Background with Gradient -->
                    <div class="absolute inset-0 bg-gradient-to-r from-purple-900/40 to-indigo-900/40 backdrop-blur-sm">
                    </div>
                    <div class="absolute inset-0 border border-white/10 rounded-3xl"></div>

                    <div class="relative bg-[#0a0a0a]/80 rounded-3xl p-12 md:p-20 text-center">
                        <h2 class="text-3xl md:text-4xl font-bold mb-4">Siap Mulai Membaca?</h2>
                        <p class="text-gray-400 mb-8 max-w-xl mx-auto text-lg">
                            Jelajahi ribuan koleksi buku dan temukan bacaan favoritmu sekarang.
                        </p>
                        <a href="{{ route('katalog') }}"
                            class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white font-bold rounded-full shadow-xl shadow-purple-500/25 transition transform hover:scale-105">
                            <i class="fas fa-book-open"></i>
                            Jelajahi Katalog Buku
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
