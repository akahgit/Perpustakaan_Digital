@extends('layouts.app')

@section('title', 'Peminjaman Saya - Perpustakaan Digital')

@section('content')
<div class="bg-[#050505] min-h-screen pb-20">
    
    <!-- HEADER SECTION -->
    <section class="pt-12 pb-8 border-b border-white/5">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl lg:text-5xl font-bold mb-2">
                Peminjaman <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-indigo-400">Saya</span>
            </h1>
            <p class="text-gray-400 text-lg">Kelola buku yang sedang Anda pinjam dan proses pengembalian.</p>
        </div>
    </section>

    <!-- STATS CARDS (DINAMIS) -->
    <section class="py-8">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <!-- Card 1: Sedang Dipinjam -->
                <div class="bg-[#0a0a0a] border border-white/10 rounded-2xl p-6 flex items-center gap-4 hover:border-purple-500/30 transition group">
                    <div class="w-14 h-14 bg-purple-500/10 rounded-xl flex items-center justify-center group-hover:scale-110 transition">
                        <i class="fas fa-book-open text-2xl text-purple-400"></i>
                    </div>
                    <div>
                        <div class="text-3xl font-bold text-white mb-1">{{ $peminjamans->count() }}</div>
                        <div class="text-sm text-gray-400">Sedang Aktif</div>
                    </div>
                </div>

                <!-- Card 2: Terlambat -->
                @php
                    $jumlahTerlambat = $peminjamans->filter(function($p) {
                        return $p->tanggal_kembali_rencana < \Carbon\Carbon::today() && $p->status_peminjaman == 'dipinjam';
                    })->count();
                @endphp
                <div class="bg-[#0a0a0a] border {{ $jumlahTerlambat > 0 ? 'border-red-500/40' : 'border-white/10' }} rounded-2xl p-6 flex items-center gap-4 hover:border-red-500/40 transition group">
                    <div class="w-14 h-14 {{ $jumlahTerlambat > 0 ? 'bg-red-500/20' : 'bg-red-500/10' }} rounded-xl flex items-center justify-center group-hover:scale-110 transition">
                        <i class="fas fa-exclamation-triangle text-2xl {{ $jumlahTerlambat > 0 ? 'text-red-400' : 'text-red-400/50' }}"></i>
                    </div>
                    <div>
                        <div class="text-3xl font-bold {{ $jumlahTerlambat > 0 ? 'text-red-400' : 'text-white' }} mb-1">{{ $jumlahTerlambat }}</div>
                        <div class="text-sm text-gray-400">Terlambat</div>
                    </div>
                </div>

                <!-- Card 3: Total Denda -->
                <div class="bg-[#0a0a0a] border border-amber-500/20 rounded-2xl p-6 flex items-center gap-4 hover:border-amber-500/40 transition group">
                    <div class="w-14 h-14 bg-amber-500/10 rounded-xl flex items-center justify-center group-hover:scale-110 transition">
                        <i class="fas fa-coins text-2xl text-amber-400"></i>
                    </div>
                    <div>
                        <div class="text-3xl font-bold text-white mb-1">Rp {{ number_format($totalDenda ?? 0, 0, ',', '.') }}</div>
                        <div class="text-sm text-gray-400">Total Denda</div>
                    </div>
                </div>

                <!-- Card 4: Total Dikembalikan -->
                @php
                    $totalKembali = \App\Models\Peminjaman::where('id_anggota', $anggota->id)->where('status_peminjaman', 'dikembalikan')->count();
                @endphp
                <div class="bg-[#0a0a0a] border border-emerald-500/20 rounded-2xl p-6 flex items-center gap-4 hover:border-emerald-500/40 transition group">
                    <div class="w-14 h-14 bg-emerald-500/10 rounded-xl flex items-center justify-center group-hover:scale-110 transition">
                        <i class="fas fa-check-circle text-2xl text-emerald-400"></i>
                    </div>
                    <div>
                        <div class="text-3xl font-bold text-white mb-1">{{ $totalKembali }}</div>
                        <div class="text-sm text-gray-400">Total Dikembalikan</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- WARNING ALERT -->
    @if($jumlahTerlambat > 0)
    <section class="py-4">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-red-500/10 border border-red-500/30 rounded-2xl p-4 flex items-start gap-4">
                <div class="w-10 h-10 bg-red-500/20 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-bell text-red-400"></i>
                </div>
                <div class="flex-1">
                    <h4 class="font-bold text-red-400 mb-1">Peringatan Keterlambatan!</h4>
                    <p class="text-sm text-red-300/80">
                        Anda memiliki <strong class="text-white">{{ $jumlahTerlambat }} buku</strong> yang sudah melewati batas pengembalian. Segera kembalikan untuk menghindari denda yang bertambah.
                    </p>
                </div>
                <a href="{{ route('riwayat') }}" class="text-red-400 hover:text-red-300 text-sm font-medium whitespace-nowrap">
                    Lihat Riwayat
                </a>
            </div>
        </div>
    </section>
    @endif

    <!-- LIST BUKU DIPINJAM -->
    <section class="py-8">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold mb-6 flex items-center gap-3">
                <i class="fas fa-list-ul text-purple-400"></i>
                Buku yang Sedang Dipinjam
            </h2>

            <div class="space-y-4">
                @forelse($peminjamans as $p)
                    @php
                        $isLate = $p->tanggal_kembali_rencana < \Carbon\Carbon::today() && $p->status_peminjaman == 'dipinjam';
                        $daysLate = $isLate ? $p->tanggal_kembali_rencana->diffInDays(\Carbon\Carbon::today(), false) : 0;
                        $dendaSaatIni = $daysLate * 1000; 
                        
                        $borderColor = $isLate ? 'border-red-500/30' : 'border-white/10';
                        $badgeClass = $isLate ? 'bg-red-500/10 text-red-400 border-red-500/20' : 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20';
                        $statusText = $isLate ? 'Terlambat' : ($p->status_peminjaman == 'menunggu_konfirmasi' ? 'Menunggu Konfirmasi' : 'Aktif');
                        
                        $colors = ['from-purple-500 to-pink-600', 'from-blue-500 to-cyan-600', 'from-indigo-500 to-blue-600', 'from-emerald-500 to-teal-600'];
                        $colorClass = $colors[$p->buku->id_buku % count($colors)];
                    @endphp

                    <div class="bg-[#0a0a0a] border {{ $borderColor }} rounded-2xl p-6 flex flex-col md:flex-row items-center gap-6 hover:border-purple-500/30 transition">
                        
                        <!-- Cover Mini -->
                        <div class="w-20 h-28 bg-gradient-to-br {{ $colorClass }} rounded-lg flex items-center justify-center flex-shrink-0 shadow-lg relative overflow-hidden">
                            @if($p->buku->cover_buku && file_exists(public_path('storage/' . $p->buku->cover_buku)))
                                <img src="{{ asset('storage/' . $p->buku->cover_buku) }}" class="w-full h-full object-cover opacity-80">
                            @else
                                <i class="fas fa-book text-3xl text-white/40"></i>
                            @endif
                            
                            @if($p->status_peminjaman == 'menunggu_konfirmasi')
                                <div class="absolute inset-0 bg-black/60 flex items-center justify-center backdrop-blur-[1px]">
                                    <i class="fas fa-clock text-2xl text-amber-400 animate-pulse"></i>
                                </div>
                            @endif
                        </div>

                        <!-- Info Buku -->
                        <div class="flex-1 text-center md:text-left">
                            <h3 class="text-xl font-bold text-white mb-1">{{ $p->buku->judul ?? 'Buku Dihapus' }}</h3>
                            <p class="text-sm text-gray-400 mb-3">{{ $p->buku->pengarang ?? '-' }}</p>
                            
                            <div class="flex flex-wrap justify-center md:justify-start gap-4 text-sm">
                                <div class="flex items-center gap-2 text-gray-300">
                                    <i class="fas fa-calendar-alt text-purple-400"></i>
                                    <span>Dipinjam: <strong class="text-white">{{ $p->tanggal_pinjam->format('d M Y') }}</strong></span>
                                </div>
                                <div class="flex items-center gap-2 {{ $isLate ? 'text-red-400' : 'text-gray-300' }}">
                                    <i class="fas fa-clock {{ $isLate ? 'text-red-400' : 'text-purple-400' }}"></i>
                                    <span>Batas Kembali: <strong class="{{ $isLate ? 'text-red-400' : 'text-white' }}">{{ $p->tanggal_kembali_rencana->format('d M Y') }}</strong></span>
                                </div>
                                
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $badgeClass }}">
                                    {{ $statusText }}
                                </span>

                                @if($isLate)
                                <div class="flex items-center gap-2 text-amber-400 font-semibold">
                                    <i class="fas fa-coins"></i>
                                    <span>Est. Denda: Rp {{ number_format($dendaSaatIni, 0, ',', '.') }}</span>
                                </div>
                                @endif
                            </div>
                            
                            @if($p->status_peminjaman == 'menunggu_konfirmasi')
                                <p class="text-xs text-amber-400 mt-2 bg-amber-500/10 inline-block px-2 py-1 rounded">
                                    <i class="fas fa-info-circle mr-1"></i> Pengajuan Anda sedang diverifikasi petugas.
                                </p>
                            @endif
                        </div>

                        <!-- Action Buttons (PERBAIKAN DI SINI) -->
                        <div class="flex items-center gap-3 w-full md:w-auto">
                            
                            <!-- Tombol Bayar Denda (Jika Terlambat) -->
                            @if($isLate && ($totalDenda > 0 || $dendaSaatIni > 0))
                                <a href="{{ route('profil') }}" class="flex-1 md:flex-none px-6 py-3 bg-amber-600 hover:bg-amber-500 text-white font-semibold rounded-xl transition shadow-lg shadow-amber-500/20 text-center whitespace-nowrap">
                                    <i class="fas fa-wallet mr-2"></i>Bayar Denda
                                </a>
                            @endif

                            <!-- Logika Tombol Utama -->
                            @if($p->status_peminjaman == 'menunggu_konfirmasi')
                                <!-- Jika Menunggu Konfirmasi: Tombol Disabled -->
                                <button disabled class="flex-1 md:flex-none px-6 py-3 bg-slate-800 text-slate-500 font-semibold rounded-xl cursor-not-allowed text-center border border-slate-700 flex items-center justify-center gap-2">
                                    <i class="fas fa-hourglass-half"></i> Menunggu Petugas
                                </button>
                            
                            @elseif($p->status_peminjaman == 'dipinjam' || $isLate)
                                <!-- Jika Sedang Dipinjam: Tombol Instruksi (Bukan Aksi Langsung) -->
                                <button type="button" onclick="showReturnInstruction('{{ $p->buku->judul }}')" 
                                        class="flex-1 md:flex-none px-6 py-3 bg-transparent border border-purple-500/50 text-purple-400 hover:bg-purple-500/10 hover:text-purple-300 font-semibold rounded-xl transition text-center flex items-center justify-center gap-2">
                                    <i class="fas fa-info-circle"></i> Cara Kembalikan
                                </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <!-- Empty State -->
                    <div class="text-center py-16 bg-[#0a0a0a] border border-white/5 rounded-2xl">
                        <div class="w-20 h-20 bg-purple-500/10 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-book-reader text-3xl text-purple-400"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2">Tidak ada peminjaman aktif</h3>
                        <p class="text-gray-400 mb-6">Anda sedang tidak meminjam buku apapun saat ini.</p>
                        <a href="{{ route('katalog') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-purple-600 hover:bg-purple-500 text-white font-semibold rounded-full transition">
                            <i class="fas fa-search"></i> Cari Buku Sekarang
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- CTA SECTION -->
    <section class="py-12">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-r from-purple-900/20 to-indigo-900/20 border border-white/10 rounded-3xl p-12 text-center relative overflow-hidden">
                <div class="relative z-10">
                    <div class="w-16 h-16 mx-auto mb-6 bg-purple-600/20 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-book-open text-3xl text-purple-400"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-2">Ingin pinjam buku lagi?</h3>
                    <p class="text-gray-400 mb-8 max-w-md mx-auto">Jelajahi katalog dan temukan buku bacaan selanjutnya.</p>
                    <a href="{{ route('katalog') }}" class="inline-flex items-center gap-2 px-8 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white font-bold rounded-full shadow-xl shadow-purple-500/25 transition transform hover:scale-105">
                        <i class="fas fa-search"></i>
                        Cari Buku
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Script Instruksi Pengembalian -->
<script>
    function showReturnInstruction(judulBuku) {
        alert("Untuk mengembalikan buku '" + judulBuku + "':\n\n1. Bawa buku fisik ke meja petugas perpustakaan.\n2. Petugas akan memindai buku dan memverifikasi kondisi.\n3. Status peminjaman Anda akan otomatis berubah menjadi 'Dikembalikan'.\n\nTerima kasih!");
    }
</script>
@endsection