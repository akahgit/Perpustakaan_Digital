@extends('layouts.app')

@section('title', 'Riwayat Transaksi - Perpustakaan Digital')

@section('content')
<div class="bg-[#050505] min-h-screen pb-20">
    
    <!-- HEADER SECTION -->
    <section class="pt-12 pb-8 border-b border-white/5">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl lg:text-5xl font-bold mb-2">
                Riwayat <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-indigo-400">Transaksi</span>
            </h1>
            <p class="text-gray-400 text-lg">Pantau seluruh riwayat peminjaman dan pengembalian buku Anda.</p>
        </div>
    </section>

    <!-- STATS SUMMARY (DINAMIS) -->
    <section class="py-8">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            
            @php
                // Hitung statistik sederhana dari data riwayat yang ada
                $totalPinjam = $riwayats->total(); 
                $totalDenda = 0;
                $tepatWaktu = 0;
                $terlambat = 0;

                foreach($riwayats as $r) {
                    // Skip jika data buku null (dihapus) agar tidak error saat hitung statistik
                    if (!$r->buku) continue; 

                    if ($r->tanggal_kembali_realisasi && $r->tanggal_kembali_realisasi > $r->tanggal_kembali_rencana) {
                        $terlambat++;
                        $daysLate = $r->tanggal_kembali_rencana->diffInDays($r->tanggal_kembali_realisasi, false);
                        $totalDenda += ($daysLate * 1000); 
                    } else {
                        $tepatWaktu++;
                    }
                }
            @endphp

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-[#0a0a0a] border border-white/10 rounded-2xl p-5">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 bg-purple-500/10 rounded-lg flex items-center justify-center">
                            <i class="fas fa-history text-purple-400 text-sm"></i>
                        </div>
                        <span class="text-xs text-gray-400 uppercase font-bold tracking-wider">Total Riwayat</span>
                    </div>
                    <div class="text-2xl font-bold text-white">{{ $totalPinjam }}</div>
                </div>
                <div class="bg-[#0a0a0a] border border-emerald-500/20 rounded-2xl p-5">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 bg-emerald-500/10 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check-circle text-emerald-400 text-sm"></i>
                        </div>
                        <span class="text-xs text-gray-400 uppercase font-bold tracking-wider">Tepat Waktu</span>
                    </div>
                    <div class="text-2xl font-bold text-emerald-400">{{ $tepatWaktu }}</div>
                </div>
                <div class="bg-[#0a0a0a] border border-red-500/20 rounded-2xl p-5">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 bg-red-500/10 rounded-lg flex items-center justify-center">
                            <i class="fas fa-exclamation-circle text-red-400 text-sm"></i>
                        </div>
                        <span class="text-xs text-gray-400 uppercase font-bold tracking-wider">Terlambat</span>
                    </div>
                    <div class="text-2xl font-bold text-red-400">{{ $terlambat }}</div>
                </div>
                <div class="bg-[#0a0a0a] border border-amber-500/20 rounded-2xl p-5">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 bg-amber-500/10 rounded-lg flex items-center justify-center">
                            <i class="fas fa-coins text-amber-400 text-sm"></i>
                        </div>
                        <span class="text-xs text-gray-400 uppercase font-bold tracking-wider">Est. Denda Historis</span>
                    </div>
                    <div class="text-2xl font-bold text-amber-400">Rp {{ number_format($totalDenda, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </section>

    <!-- TABEL RIWAYAT UTAMA -->
    <section class="py-4">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Search Bar (Visual Only) -->
            <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
                <div class="relative w-full md:w-96">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-500"></i>
                    </div>
                    <input type="text" placeholder="Cari judul buku lama..." disabled
                           class="w-full pl-11 pr-4 py-3 bg-[#0a0a0a]/50 border border-white/5 rounded-xl text-gray-500 text-sm cursor-not-allowed">
                </div>
                <div class="text-sm text-gray-400">
                    Menampilkan data pengembalian terakhir
                </div>
            </div>

            <!-- Table Container -->
            <div class="bg-[#0a0a0a] border border-white/10 rounded-2xl overflow-hidden shadow-xl">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white/5 border-b border-white/10 text-xs uppercase tracking-wider text-gray-400">
                                <th class="px-6 py-4 font-semibold">ID Transaksi</th>
                                <th class="px-6 py-4 font-semibold">Buku</th>
                                <th class="px-6 py-4 font-semibold">Tgl Pinjam</th>
                                <th class="px-6 py-4 font-semibold">Jatuh Tempo</th>
                                <th class="px-6 py-4 font-semibold">Tgl Kembali</th>
                                <th class="px-6 py-4 font-semibold text-center">Status</th>
                                <th class="px-6 py-4 font-semibold text-right">Keterlambatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5 text-sm">
                            @forelse($riwayats as $r)
                                @php
                                    // PERBAIKAN: Cek apakah buku ada. Jika tidak, skip baris ini.
                                    if (!$r->buku) {
                                        continue;
                                    }

                                    $isLateReturn = $r->tanggal_kembali_realisasi && $r->tanggal_kembali_realisasi > $r->tanggal_kembali_rencana;
                                    $daysLate = $isLateReturn ? $r->tanggal_kembali_rencana->diffInDays($r->tanggal_kembali_realisasi, false) : 0;
                                    $fineAmount = $daysLate * 1000; 
                                    
                                    // Gradient Cover Logic
                                    $colors = ['from-purple-500 to-pink-600', 'from-blue-500 to-cyan-600', 'from-indigo-500 to-blue-600', 'from-emerald-500 to-teal-600'];
                                    $colorClass = $colors[$r->buku->id_buku % count($colors)] ?? 'from-gray-500 to-gray-600';
                                @endphp
                                <tr class="hover:bg-white/5 transition group">
                                    <td class="px-6 py-4 font-mono text-purple-400 font-medium">#PMJ-{{ str_pad($r->id_peminjaman, 5, '0', STR_PAD_LEFT) }}</td>
                                    
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-14 bg-gradient-to-br {{ $colorClass }} rounded flex-shrink-0 flex items-center justify-center">
                                                @if($r->buku->cover_buku && file_exists(public_path('storage/' . $r->buku->cover_buku)))
                                                    <img src="{{ asset('storage/' . $r->buku->cover_buku) }}" class="w-full h-full object-cover opacity-80 rounded">
                                                @else
                                                    <i class="fas fa-book text-white/40 text-xs"></i>
                                                @endif
                                            </div>
                                            <div>
                                                <!-- Fallback jika judul null -->
                                                <div class="font-medium text-white group-hover:text-purple-300 transition">{{ $r->buku->judul ?? 'Buku Dihapus' }}</div>
                                                <div class="text-xs text-gray-500">{{ $r->buku->pengarang ?? '-' }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 text-gray-400">{{ $r->tanggal_pinjam->format('d M Y') }}</td>
                                    <td class="px-6 py-4 text-gray-400">{{ $r->tanggal_kembali_rencana->format('d M Y') }}</td>
                                    
                                    <td class="px-6 py-4 font-medium {{ $isLateReturn ? 'text-red-400' : 'text-emerald-400' }}">
                                        {{ $r->tanggal_kembali_realisasi ? $r->tanggal_kembali_realisasi->format('d M Y') : '-' }}
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        @if($isLateReturn)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-500/10 text-red-400 border border-red-500/20">
                                                Terlambat
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                                Tepat Waktu
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-right">
                                        @if($isLateReturn)
                                            <div class="text-red-400 font-bold">{{ $daysLate }} Hari</div>
                                            <div class="text-xs text-gray-500">Denda: Rp {{ number_format($fineAmount, 0, ',', '.') }}</div>
                                        @else
                                            <span class="text-gray-500">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center text-gray-500">
                                            <i class="fas fa-box-open text-4xl mb-3 opacity-30"></i>
                                            <p class="text-lg font-medium">Belum ada riwayat pengembalian.</p>
                                            <p class="text-sm">Buku yang sudah Anda kembalikan akan muncul di sini.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($riwayats->hasPages())
                <div class="flex items-center justify-between px-6 py-4 border-t border-white/10 bg-white/5">
                    <span class="text-sm text-gray-400">
                        Menampilkan <strong class="text-white">{{ $riwayats->firstItem() }}</strong>-<strong class="text-white">{{ $riwayats->lastItem() }}</strong> dari <strong class="text-white">{{ $riwayats->total() }}</strong>
                    </span>
                    <div class="flex gap-2">
                        @if ($riwayats->onFirstPage())
                            <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/5 text-gray-600 cursor-not-allowed"><i class="fas fa-chevron-left text-xs"></i></span>
                        @else
                            <a href="{{ $riwayats->previousPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/5 hover:bg-white/10 text-gray-400 hover:text-white transition"><i class="fas fa-chevron-left text-xs"></i></a>
                        @endif

                        @foreach ($riwayats->links()->elements[0] ?? [] as $page => $url)
                            @if ($page == $riwayats->currentPage())
                                <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-purple-600 text-white font-semibold shadow-lg shadow-purple-500/25">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/5 hover:bg-white/10 text-gray-400 hover:text-white transition">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if ($riwayats->hasMorePages())
                            <a href="{{ $riwayats->nextPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/5 hover:bg-white/10 text-gray-400 hover:text-white transition"><i class="fas fa-chevron-right text-xs"></i></a>
                        @else
                            <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/5 text-gray-600 cursor-not-allowed"><i class="fas fa-chevron-right text-xs"></i></span>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>

    <!-- INFO SECTION -->
    <section class="py-12">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-r from-purple-900/20 to-indigo-900/20 border border-white/10 rounded-3xl p-8 text-center relative overflow-hidden">
                <div class="relative z-10">
                    <h3 class="text-xl font-bold mb-2">Butuh Bantuan?</h3>
                    <p class="text-gray-400 mb-6 max-w-md mx-auto">Jika terdapat kesalahan data pada riwayat pengembalian, silakan hubungi petugas perpustakaan.</p>
                    <a href="{{ route('profil') }}" class="inline-flex items-center gap-2 px-6 py-2 bg-white/10 hover:bg-white/20 text-white font-semibold rounded-full transition">
                        <i class="fas fa-headset"></i> Hubungi Petugas
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection