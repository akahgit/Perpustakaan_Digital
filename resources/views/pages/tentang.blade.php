@extends('layouts.app')

@section('title', 'Tentang Kami - Perpustakaan Digital')

@section('content')
<div class="bg-[#050505] min-h-screen pb-20">
    
    <!-- 1. HERO SECTION -->
    <section class="pt-20 pb-16 relative overflow-hidden">
        <!-- Background Glow -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[500px] bg-purple-600/10 rounded-full blur-[120px] pointer-events-none"></div>
        
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <div class="inline-block px-4 py-1.5 mb-6 rounded-full bg-white/5 border border-white/10">
                <span class="text-purple-300 text-xs font-bold uppercase tracking-wider">Tentang Kami</span>
            </div>
            <h1 class="text-4xl lg:text-6xl font-extrabold text-white mb-6 leading-tight">
                Perpustakaan untuk <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-indigo-400">Semua Orang</span>
            </h1>
            <p class="text-lg text-gray-400 max-w-3xl mx-auto leading-relaxed">
                Kami berkomitmen menyediakan akses pendidikan melalui koleksi buku yang lengkap dan sistem digital yang modern.
            </p>
        </div>
    </section>

    <!-- 2. VISI & MISI + STATS -->
    <section class="py-16 border-y border-white/5 bg-white/[0.02]">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                
                <!-- Left: Text -->
                <div>
                    <div class="inline-block px-3 py-1 mb-4 rounded-full bg-purple-500/10 border border-purple-500/20">
                        <span class="text-purple-300 text-xs font-bold uppercase">Visi & Misi</span>
                    </div>
                    <h2 class="text-3xl font-bold text-white mb-6">Mencerdaskan Bangsa melalui Literasi</h2>
                    <p class="text-gray-400 mb-8 leading-relaxed">
                        Perpustakaan Digital adalah sistem perpustakaan modern yang bertujuan memudahkan akses buku dan informasi bagi seluruh anggota. Dengan teknologi digital, kami menghilangkan batasan waktu dan tempat dalam mengakses koleksi perpustakaan.
                    </p>
                    
                    <div class="space-y-6">
                        <div class="flex gap-4">
                            <div class="w-10 h-10 rounded-full bg-purple-500/10 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-eye text-purple-400"></i>
                            </div>
                            <div>
                                <h4 class="text-white font-bold mb-1">Visi</h4>
                                <p class="text-sm text-gray-400">Menjadi perpustakaan digital terdepan yang menginspirasi minat baca dan mendukung pendidikan berkualitas.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="w-10 h-10 rounded-full bg-indigo-500/10 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-bullseye text-indigo-400"></i>
                            </div>
                            <div>
                                <h4 class="text-white font-bold mb-1">Misi</h4>
                                <p class="text-sm text-gray-400">Menyediakan koleksi buku yang beragam, layanan peminjaman yang efisien, dan pengalaman pengguna terbaik melalui teknologi modern.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Stats Grid -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-[#0a0a0a] border border-white/10 rounded-2xl p-6 text-center">
                        <div class="text-4xl font-bold text-white mb-2">2.458</div>
                        <div class="text-sm text-gray-500">Koleksi Buku</div>
                    </div>
                    <div class="bg-[#0a0a0a] border border-white/10 rounded-2xl p-6 text-center">
                        <div class="text-4xl font-bold text-white mb-2">856</div>
                        <div class="text-sm text-gray-500">Anggota Aktif</div>
                    </div>
                    <div class="bg-[#0a0a0a] border border-white/10 rounded-2xl p-6 text-center">
                        <div class="text-4xl font-bold text-white mb-2">156</div>
                        <div class="text-sm text-gray-500">Judul Baru</div>
                    </div>
                    <div class="bg-[#0a0a0a] border border-white/10 rounded-2xl p-6 text-center">
                        <div class="text-4xl font-bold text-white mb-2">12</div>
                        <div class="text-sm text-gray-500">Tahun Beroperasi</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. LAYANAN KAMI -->
    <section class="py-20">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <div class="inline-block px-3 py-1 mb-4 rounded-full bg-white/5 border border-white/10">
                    <span class="text-purple-300 text-xs font-bold uppercase">Layanan</span>
                </div>
                <h2 class="text-3xl lg:text-4xl font-bold text-white mb-4">Layanan <span class="text-purple-400">Kami</span></h2>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Service 1 -->
                <div class="bg-[#0a0a0a] border border-white/10 rounded-2xl p-8 text-center hover:border-purple-500/30 transition group">
                    <div class="w-16 h-16 mx-auto mb-6 bg-gradient-to-br from-purple-600 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg shadow-purple-500/20 group-hover:scale-110 transition">
                        <i class="fas fa-book-reader text-2xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Peminjaman Buku</h3>
                    <p class="text-gray-400 text-sm">Pinjam buku dengan mudah melalui sistem digital. Maksimal 3 buku selama 7 hari.</p>
                </div>

                <!-- Service 2 -->
                <div class="bg-[#0a0a0a] border border-white/10 rounded-2xl p-8 text-center hover:border-blue-500/30 transition group">
                    <div class="w-16 h-16 mx-auto mb-6 bg-gradient-to-br from-blue-600 to-cyan-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-500/20 group-hover:scale-110 transition">
                        <i class="fas fa-search text-2xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Katalog Digital</h3>
                    <p class="text-gray-400 text-sm">Cari buku dari koleksi kami dengan berbagai filter dan kategori.</p>
                </div>

                <!-- Service 3 -->
                <div class="bg-[#0a0a0a] border border-white/10 rounded-2xl p-8 text-center hover:border-emerald-500/30 transition group">
                    <div class="w-16 h-16 mx-auto mb-6 bg-gradient-to-br from-emerald-600 to-teal-600 rounded-2xl flex items-center justify-center shadow-lg shadow-emerald-500/20 group-hover:scale-110 transition">
                        <i class="fas fa-history text-2xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Riwayat Transparan</h3>
                    <p class="text-gray-400 text-sm">Pantau semua riwayat peminjaman, pengembalian, dan denda Anda.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. TIM KAMI -->
    <section class="py-20 bg-white/[0.02] border-y border-white/5">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <div class="inline-block px-3 py-1 mb-4 rounded-full bg-white/5 border border-white/10">
                    <span class="text-purple-300 text-xs font-bold uppercase">Tim Kami</span>
                </div>
                <h2 class="text-3xl lg:text-4xl font-bold text-white mb-4">Dikelola oleh <span class="text-purple-400">Profesional</span></h2>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                @php
                    $team = [
                        ['name' => 'Dr. Kartini Putri', 'role' => 'Kepala Perpustakaan', 'initials' => 'KP', 'color' => 'from-purple-500 to-pink-600'],
                        ['name' => 'Ahmad Suryana', 'role' => 'Petugas Perpustakaan', 'initials' => 'AS', 'color' => 'from-blue-500 to-cyan-600'],
                        ['name' => 'Rina Wulandari', 'role' => 'Petugas Perpustakaan', 'initials' => 'RW', 'color' => 'from-orange-500 to-red-600'],
                    ];
                @endphp

                @foreach($team as $member)
                <div class="bg-[#0a0a0a] border border-white/10 rounded-2xl p-8 text-center hover:border-white/20 transition">
                    <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br {{ $member['color'] }} rounded-full flex items-center justify-center text-3xl font-bold text-white shadow-xl">
                        {{ $member['initials'] }}
                    </div>
                    <h3 class="text-xl font-bold text-white mb-1">{{ $member['name'] }}</h3>
                    <p class="text-purple-400 text-sm font-medium mb-4">{{ $member['role'] }}</p>
                    <p class="text-gray-500 text-sm">Berpengalaman lebih dari 10 tahun di bidang perpustakaan dan manajemen informasi.</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- 5. FAQ (Accordion) -->
    <section class="py-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <div class="inline-block px-3 py-1 mb-4 rounded-full bg-white/5 border border-white/10">
                    <span class="text-purple-300 text-xs font-bold uppercase">FAQ</span>
                </div>
                <h2 class="text-3xl lg:text-4xl font-bold text-white mb-4">Pertanyaan <span class="text-purple-400">Umum</span></h2>
            </div>

            <div class="space-y-4" x-data="{ active: null }">
                @php
                    $faqs = [
                        ['q' => 'Bagaimana cara meminjam buku?', 'a' => 'Kunjungi halaman Katalog Buku, pilih buku yang ingin dipinjam, lalu klik tombol "Pinjam". Tentukan tanggal pengembalian dan konfirmasi peminjaman. Setelah itu, ambil buku fisik di perpustakaan.'],
                        ['q' => 'Berapa lama masa peminjaman?', 'a' => 'Masa peminjaman standar adalah 7 hari. Anda dapat meminjam maksimal 3 buku secara bersamaan.'],
                        ['q' => 'Berapa denda keterlambatan?', 'a' => 'Denda keterlambatan sebesar Rp 2.000 per hari per buku. Denda akan otomatis dihitung saat proses pengembalian.'],
                        ['q' => 'Bagaimana cara mengembalikan buku?', 'a' => 'Kunjungi halaman "Peminjaman Saya", pilih buku yang ingin dikembalikan, lalu klik "Kembalikan Buku". Serahkan buku fisik ke petugas perpustakaan.'],
                        ['q' => 'Bagaimana cara menjadi anggota?', 'a' => 'Hubungi petugas perpustakaan atau datang langsung ke perpustakaan dengan membawa kartu identitas untuk mendaftar sebagai anggota.'],
                    ];
                @endphp

                @foreach($faqs as $index => $faq)
                <div class="bg-[#0a0a0a] border border-white/10 rounded-xl overflow-hidden">
                    <button @click="active === {{ $index }} ? active = null : active = {{ $index }}" 
                            class="w-full flex items-center justify-between p-6 text-left hover:bg-white/5 transition">
                        <span class="font-semibold text-white pr-4">{{ $faq['q'] }}</span>
                        <i class="fas fa-chevron-down text-purple-400 transition-transform duration-300" :class="active === {{ $index }} ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="active === {{ $index }}" 
                         x-collapse 
                         class="px-6 pb-6 text-gray-400 text-sm leading-relaxed border-t border-white/5 pt-4">
                        {{ $faq['a'] }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- 6. HUBUNGI KAMI & FOOTER CONTACT -->
    <section class="py-20 bg-white/[0.02] border-t border-white/5">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12">
                
                <!-- Info Kontak -->
                <div>
                    <div class="inline-block px-3 py-1 mb-4 rounded-full bg-white/5 border border-white/10">
                        <span class="text-purple-300 text-xs font-bold uppercase">Hubungi Kami</span>
                    </div>
                    <h2 class="text-3xl font-bold text-white mb-4">Ada Pertanyaan?</h2>
                    <p class="text-gray-400 mb-8">Hubungi kami melalui salah satu kanal berikut atau kunjungi langsung perpustakaan kami.</p>
                    
                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-purple-500/10 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-map-marker-alt text-purple-400 text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-white font-bold mb-1">Alamat</h4>
                                <p class="text-gray-400 text-sm">Jl. Pendidikan No. 1, Indonesia</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-blue-500/10 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-phone text-blue-400 text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-white font-bold mb-1">Telepon</h4>
                                <p class="text-gray-400 text-sm">(021) 1234-5678</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-emerald-500/10 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-envelope text-emerald-400 text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-white font-bold mb-1">Email</h4>
                                <p class="text-gray-400 text-sm">info@perpusdigital.id</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-amber-500/10 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-clock text-amber-400 text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-white font-bold mb-1">Jam Operasional</h4>
                                <p class="text-gray-400 text-sm">Senin - Jumat: 08.00 - 16.00 WIB</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Kontak -->
                <div class="bg-[#0a0a0a] border border-white/10 rounded-2xl p-8">
                    <h3 class="text-xl font-bold text-white mb-6">Kirim Pesan</h3>
                    <form class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Nama</label>
                            <input type="text" placeholder="Masukkan nama Anda" class="w-full bg-[#050505] border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-600 focus:outline-none focus:border-purple-500 transition">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Email</label>
                            <input type="email" placeholder="Masukkan email Anda" class="w-full bg-[#050505] border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-600 focus:outline-none focus:border-purple-500 transition">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Pesan</label>
                            <textarea rows="4" placeholder="Tulis pesan Anda..." class="w-full bg-[#050505] border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-600 focus:outline-none focus:border-purple-500 transition resize-none"></textarea>
                        </div>
                        <button type="submit" class="w-full py-4 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white font-bold rounded-xl shadow-lg shadow-purple-500/25 transition transform hover:scale-[1.02] flex items-center justify-center gap-2">
                            <i class="fas fa-paper-plane"></i>
                            Kirim Pesan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection