@extends('layouts.app')

@section('title', 'Profil Saya - Perpustakaan Digital')

@section('content')
<div class="bg-[#050505] min-h-screen pb-20">
    
    <!-- HEADER PROFIL -->
    <section class="pt-12 pb-8">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-r from-purple-900/40 to-indigo-900/40 border border-white/10 rounded-3xl p-8 md:p-10 relative overflow-hidden">
                <!-- Background Decor -->
                <div class="absolute top-0 right-0 w-64 h-64 bg-purple-500/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row items-center md:items-start gap-8">
                    <!-- Avatar Besar -->
                    <div class="relative">
                        <div class="w-32 h-32 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-full flex items-center justify-center text-5xl font-bold text-white shadow-2xl shadow-purple-500/30 ring-4 ring-white/10">
                            {{ substr(auth()->user()->name, 0, 2) }}
                        </div>
                        <div class="absolute bottom-2 right-2 w-6 h-6 bg-emerald-500 border-4 border-[#1a1a1a] rounded-full" title="Aktif"></div>
                    </div>

                    <!-- Info Utama -->
                    <div class="flex-1 text-center md:text-left">
                        <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">{{ auth()->user()->name }}</h1>
                        <p class="text-gray-400 mb-4">Anggota sejak Januari 2024</p>
                        
                        <div class="flex flex-wrap justify-center md:justify-start gap-3 mb-6">
                            <span class="px-4 py-1.5 bg-white/5 border border-white/10 rounded-full text-sm font-medium text-gray-300 flex items-center gap-2">
                                <i class="fas fa-id-card text-purple-400"></i>
                                ID: AG001
                            </span>
                            <span class="px-4 py-1.5 bg-emerald-500/10 border border-emerald-500/20 rounded-full text-sm font-medium text-emerald-400 flex items-center gap-2">
                                <i class="fas fa-check-circle"></i>
                                Aktif
                            </span>
                        </div>

                        <button class="px-6 py-2.5 bg-white/5 hover:bg-white/10 border border-white/10 text-white font-semibold rounded-xl transition flex items-center gap-2 mx-auto md:mx-0">
                            <i class="fas fa-edit"></i>
                            Edit Profil
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- STATS GRID -->
    <section class="py-4">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-[#0a0a0a] border border-white/10 rounded-2xl p-6 text-center hover:border-purple-500/30 transition">
                    <div class="w-12 h-12 bg-purple-500/10 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-book-open text-2xl text-purple-400"></i>
                    </div>
                    <div class="text-3xl font-bold text-white mb-1">27</div>
                    <div class="text-sm text-gray-400">Total Peminjaman</div>
                </div>

                <div class="bg-[#0a0a0a] border border-white/10 rounded-2xl p-6 text-center hover:border-amber-500/30 transition">
                    <div class="w-12 h-12 bg-amber-500/10 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-clock text-2xl text-amber-400"></i>
                    </div>
                    <div class="text-3xl font-bold text-white mb-1">3</div>
                    <div class="text-sm text-gray-400">Sedang Dipinjam</div>
                </div>

                <div class="bg-[#0a0a0a] border border-white/10 rounded-2xl p-6 text-center hover:border-emerald-500/30 transition">
                    <div class="w-12 h-12 bg-emerald-500/10 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-history text-2xl text-emerald-400"></i>
                    </div>
                    <div class="text-3xl font-bold text-white mb-1">24</div>
                    <div class="text-sm text-gray-400">Dikembalikan</div>
                </div>

                <div class="bg-[#0a0a0a] border border-red-500/20 rounded-2xl p-6 text-center hover:border-red-500/40 transition">
                    <div class="w-12 h-12 bg-red-500/10 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-coins text-2xl text-red-400"></i>
                    </div>
                    <div class="text-3xl font-bold text-red-400 mb-1">Rp 8.000</div>
                    <div class="text-sm text-gray-400">Denda Aktif</div>
                </div>
            </div>
        </div>
    </section>

    <!-- INFORMASI PRIBADI & KONTAK -->
    <section class="py-8">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-8">
                
                <!-- Kolom Kiri: Informasi Pribadi -->
                <div class="bg-[#0a0a0a] border border-white/10 rounded-2xl p-8">
                    <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-3">
                        <i class="fas fa-user text-purple-400"></i>
                        Informasi Pribadi
                    </h3>
                    
                    <div class="space-y-5">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Nama Lengkap</label>
                            <input type="text" value="{{ auth()->user()->name }}" readonly 
                                   class="w-full bg-[#050505] border border-white/10 rounded-lg px-4 py-2.5 text-white text-sm focus:outline-none cursor-default">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase mb-1">ID Anggota</label>
                            <input type="text" value="AG001" readonly 
                                   class="w-full bg-[#050505] border border-white/10 rounded-lg px-4 py-2.5 text-white text-sm focus:outline-none cursor-default">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Jenis Kelamin</label>
                            <input type="text" value="Laki-laki" readonly 
                                   class="w-full bg-[#050505] border border-white/10 rounded-lg px-4 py-2.5 text-white text-sm focus:outline-none cursor-default">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Tanggal Daftar</label>
                            <input type="text" value="10 Januari 2024" readonly 
                                   class="w-full bg-[#050505] border border-white/10 rounded-lg px-4 py-2.5 text-white text-sm focus:outline-none cursor-default">
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Informasi Kontak -->
                <div class="bg-[#0a0a0a] border border-white/10 rounded-2xl p-8">
                    <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-3">
                        <i class="fas fa-address-card text-indigo-400"></i>
                        Informasi Kontak
                    </h3>
                    
                    <div class="space-y-5">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Alamat</label>
                            <textarea readonly rows="2" 
                                      class="w-full bg-[#050505] border border-white/10 rounded-lg px-4 py-2.5 text-white text-sm focus:outline-none cursor-default resize-none">Jl. Merdeka No. 45, Jakarta Selatan</textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase mb-1">No. Telepon</label>
                            <input type="text" value="081234567890" readonly 
                                   class="w-full bg-[#050505] border border-white/10 rounded-lg px-4 py-2.5 text-white text-sm focus:outline-none cursor-default">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Email</label>
                            <input type="email" value="{{ auth()->user()->email }}" readonly 
                                   class="w-full bg-[#050505] border border-white/10 rounded-lg px-4 py-2.5 text-white text-sm focus:outline-none cursor-default">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- AKTIVITAS TERBARU -->
    <section class="py-8">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-[#0a0a0a] border border-white/10 rounded-2xl p-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-white flex items-center gap-3">
                        <i class="fas fa-bolt text-amber-400"></i>
                        Aktivitas Terbaru
                    </h3>
                    <a href="{{ route('riwayat') }}" class="text-sm text-purple-400 hover:text-purple-300 font-medium flex items-center gap-1">
                        Lihat Semua Riwayat
                        <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                </div>

                <div class="space-y-4">
                    @php
                        $activities = [
                            ['type' => 'pinjam', 'book' => 'Negeri 5 Menara', 'date' => '19 Februari 2026', 'desc' => 'Batas kembali: 26 Feb 2026', 'color' => 'amber'],
                            ['type' => 'pinjam', 'book' => 'Laskar Pelangi', 'date' => '18 Februari 2026', 'desc' => 'Batas kembali: 25 Feb 2026', 'color' => 'purple'],
                            ['type' => 'kembali', 'book' => 'Bumi Manusia', 'date' => '08 Februari 2026', 'desc' => 'Tepat waktu', 'color' => 'emerald'],
                            ['type' => 'denda', 'book' => 'Sang Pemimpi', 'date' => '24 Januari 2026', 'desc' => 'Denda Rp 4.000 (Lunas)', 'color' => 'red'],
                        ];
                    @endphp

                    @foreach($activities as $act)
                    <div class="flex items-start gap-4 p-4 rounded-xl hover:bg-white/5 transition border border-transparent hover:border-white/5">
                        <!-- Icon -->
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 
                            @if($act['color'] == 'amber') bg-amber-500/10 text-amber-400
                            @elseif($act['color'] == 'purple') bg-purple-500/10 text-purple-400
                            @elseif($act['color'] == 'emerald') bg-emerald-500/10 text-emerald-400
                            @else bg-red-500/10 text-red-400
                            @endif">
                            @if($act['type'] == 'pinjam')
                                <i class="fas fa-hand-holding-heart text-xl"></i>
                            @elseif($act['type'] == 'kembali')
                                <i class="fas fa-check-double text-xl"></i>
                            @else
                                <i class="fas fa-file-invoice-dollar text-xl"></i>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="flex-1 pt-1">
                            <h4 class="text-white font-medium mb-1">
                                @if($act['type'] == 'pinjam')
                                    Meminjam buku "<span class="text-white">{{ $act['book'] }}</span>"
                                @elseif($act['type'] == 'kembali')
                                    Mengembalikan buku "<span class="text-white">{{ $act['book'] }}</span>"
                                @else
                                    Denda karena keterlambatan "<span class="text-white">{{ $act['book'] }}</span>"
                                @endif
                            </h4>
                            <p class="text-sm text-gray-400">{{ $act['date }} • {{ $act['desc'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
</div>
@endsection