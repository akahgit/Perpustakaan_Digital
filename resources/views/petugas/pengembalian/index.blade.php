@extends('layouts.petugas')

@section('title', 'Pengembalian Buku')
@section('page-title', 'Proses Pengembalian')

@section('content')
<div class="space-y-6">

    <!-- 1. STATISTIK CEPAT (DIPERBAIKI VARIABELNYA) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total Harus Kembali -->
        <div class="bg-[#1e293b] rounded-2xl p-6 border border-slate-700/50 shadow-xl flex items-center justify-between">
            <div>
                <p class="text-slate-400 text-sm font-medium mb-1">Sedang Beredar</p>
                <!-- PERBAIKAN: Menggunakan $sedangBeredar -->
                <h3 class="text-3xl font-bold text-white">{{ number_format($sedangBeredar ?? 0) }}</h3>
            </div>
            <div class="w-14 h-14 bg-indigo-500/20 rounded-xl flex items-center justify-center text-indigo-400">
                <i class="fas fa-book-reader text-2xl"></i>
            </div>
        </div>

        <!-- Jumlah Terlambat -->
        <div class="bg-[#1e293b] rounded-2xl p-6 border border-red-500/30 shadow-xl flex items-center justify-between relative overflow-hidden">
            <div class="absolute right-0 top-0 w-24 h-24 bg-red-500/10 rounded-full blur-2xl -mr-10 -mt-10"></div>
            <div class="relative z-10">
                <p class="text-red-200 text-sm font-medium mb-1">Terlambat Kembali</p>
                <h3 class="text-3xl font-bold text-red-400">{{ number_format($terlambatKembali ?? 0) }}</h3>
            </div>
            <div class="w-14 h-14 bg-red-500/20 rounded-xl flex items-center justify-center text-red-400 relative z-10">
                <i class="fas fa-exclamation-triangle text-2xl"></i>
            </div>
        </div>

        <!-- Info Denda (DINAMIS) -->
        <div class="bg-[#1e293b] rounded-2xl p-6 border border-emerald-500/30 shadow-xl flex items-center justify-between">
            <div>
                <p class="text-emerald-200 text-sm font-medium mb-1">Estimasi Denda Harian</p>
                <!-- PERBAIKAN: Menggunakan variabel dinamis -->
                <h3 class="text-3xl font-bold text-emerald-400">Rp {{ number_format($estimasiDenda ?? 0, 0, ',', '.') }}</h3>
                <p class="text-xs text-slate-500 mt-1">Total potensi denda saat ini</p>
            </div>
            <div class="w-14 h-14 bg-emerald-500/20 rounded-xl flex items-center justify-center text-emerald-400">
                <i class="fas fa-coins text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- 2. DAFTAR BUKU YANG HARUS DIKEMBALIKAN (DINAMIS) -->
    <div class="bg-[#1e293b] rounded-2xl border border-slate-700/50 shadow-xl overflow-hidden">
        <div class="p-6 border-b border-slate-700/50 flex justify-between items-center">
            <div>
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <i class="fas fa-list-ul text-indigo-400"></i>
                    Daftar Pinjaman Aktif
                </h3>
                <p class="text-sm text-slate-400">Urut berdasarkan jatuh tempo (terlama di atas)</p>
            </div>
            
            <!-- Form Filter Sederhana -->
            <form action="{{ route('petugas.pengembalian.index') }}" method="GET" class="flex gap-2">
                <input type="text" name="search_anggota" value="{{ request('search_anggota') }}" placeholder="Cari Anggota..." 
                       class="bg-slate-800 border border-slate-600 rounded-lg px-4 py-2 text-sm text-white focus:border-indigo-500 outline-none">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2 rounded-lg text-sm">Cari</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-800/50 text-xs uppercase tracking-wider text-slate-400">
                        <th class="px-6 py-4 font-semibold">ID & Anggota</th>
                        <th class="px-6 py-4 font-semibold">Buku</th>
                        <th class="px-6 py-4 font-semibold text-center">Tgl Pinjam</th>
                        <th class="px-6 py-4 font-semibold text-center">Jatuh Tempo</th>
                        <th class="px-6 py-4 font-semibold text-center">Status</th>
                        <th class="px-6 py-4 font-semibold text-center">Keterlambatan</th>
                        <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/50 text-sm">
                    @forelse($peminjamans as $p)
                    @php
                        // Hitung keterlambatan
                        $today = \Carbon\Carbon::today();
                        $jatuhTempo = $p->tanggal_kembali_rencana;
                        $isLate = $jatuhTempo < $today;
                        $telatHari = $isLate ? $jatuhTempo->diffInDays($today, false) : 0;
                        $denda = $telatHari * 1000; // Logika denda sederhana
                    @endphp
                    <tr class="hover:bg-slate-800/30 transition {{ $isLate ? 'bg-red-500/5' : '' }}">
                        
                        <!-- ID & Anggota -->
                        <td class="px-6 py-4">
                            <div class="font-mono text-xs text-indigo-400 font-bold mb-1">#{{ str_pad($p->id_peminjaman, 5, '0', STR_PAD_LEFT) }}</div>
                            @if($p->anggota)
                                <div class="font-bold text-white">{{ $p->anggota->nama }}</div>
                                <div class="text-xs text-slate-500">{{ $p->anggota->kelas }}</div>
                            @else
                                <span class="text-red-400 text-xs">Anggota Dihapus</span>
                            @endif
                        </td>

                        <!-- Buku -->
                        <td class="px-6 py-4">
                            @if($p->buku)
                                <div class="font-medium text-slate-200 line-clamp-1">{{ $p->buku->judul }}</div>
                                <div class="text-xs text-slate-500">{{ $p->buku->pengarang }}</div>
                            @else
                                <span class="text-red-400 text-xs">Buku Dihapus</span>
                            @endif
                        </td>

                        <!-- Tgl Pinjam -->
                        <td class="px-6 py-4 text-center text-slate-400">
                            {{ $p->tanggal_pinjam->format('d M Y') }}
                        </td>

                        <!-- Jatuh Tempo -->
                        <td class="px-6 py-4 text-center font-medium {{ $isLate ? 'text-red-400' : 'text-slate-300' }}">
                            {{ $jatuhTempo->format('d M Y') }}
                        </td>

                        <!-- Status Badge -->
                        <td class="px-6 py-4 text-center">
                            @if($isLate)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-500/10 text-red-400 border border-red-500/20 animate-pulse">
                                    Terlambat
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                    Aktif
                                </span>
                            @endif
                        </td>

                        <!-- Keterlambatan & Denda -->
                        <td class="px-6 py-4 text-center">
                            @if($isLate)
                                <div class="text-red-400 font-bold">{{ $telatHari }} Hari</div>
                                <div class="text-xs text-red-300">Denda: Rp {{ number_format($denda, 0, ',', '.') }}</div>
                            @else
                                <div class="text-emerald-400 font-bold">{{ $jatuhTempo->diffInDays($today, false) }} Hari lagi</div>
                                <div class="text-xs text-slate-500">-</div>
                            @endif
                        </td>

                        <!-- Tombol Aksi -->
                        <td class="px-6 py-4 text-right">
                            <form action="{{ route('petugas.peminjaman.kembali', $p->id_peminjaman) }}" method="POST" onsubmit="return confirm('Proses pengembalian buku ini?\n\nAnggota: {{ $p->anggota->nama ?? '-' }}\nBuku: {{ $p->buku->judul ?? '-' }}\n\nStok buku akan otomatis bertambah.');">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-lg shadow-lg shadow-emerald-500/30 transition flex items-center gap-1 ml-auto">
                                    <i class="fas fa-undo"></i> Konfirmasi Kembalian
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                            <i class="fas fa-check-circle text-4xl mb-3 text-emerald-500/30"></i>
                            <p>Tidak ada buku yang sedang dipinjam saat ini.</p>
                            <p class="text-sm">Semua buku telah dikembalikan!</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="p-6 border-t border-slate-700/50">
            {{ $peminjamans->links() }}
        </div>
    </div>

    <!-- 3. RIWAYAT PENGEMBALIAN HARI INI (DINAMIS) -->
    <div class="bg-[#1e293b] rounded-2xl border border-slate-700/50 shadow-xl overflow-hidden">
        <div class="p-6 border-b border-slate-700/50">
            <h3 class="text-lg font-bold text-white">Riwayat Pengembalian Hari Ini</h3>
            <p class="text-sm text-slate-400">Transaksi yang baru saja diproses kembali</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-800/50 text-xs uppercase tracking-wider text-slate-400">
                        <th class="px-6 py-4 font-semibold">Waktu Kembali</th>
                        <th class="px-6 py-4 font-semibold">Anggota</th>
                        <th class="px-6 py-4 font-semibold">Buku</th>
                        <th class="px-6 py-4 font-semibold text-center">Keterlambatan</th>
                        <th class="px-6 py-4 font-semibold text-right">Denda</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/50 text-sm">
                    @php
                        // Ambil data peminjaman yang dikembalikan HARI INI saja
                        $returnsToday = \App\Models\Peminjaman::with(['anggota', 'buku'])
                            ->where('status_peminjaman', 'dikembalikan')
                            ->whereDate('tanggal_kembali_realisasi', \Carbon\Carbon::today())
                            ->orderBy('tanggal_kembali_realisasi', 'desc')
                            ->limit(5)
                            ->get();
                    @endphp

                    @forelse($returnsToday as $r)
                    @php
                        $jatuhTempo = $r->tanggal_kembali_rencana;
                        $realisasi = $r->tanggal_kembali_realisasi;
                        $isLateReturn = $realisasi > $jatuhTempo;
                        $telatHari = $isLateReturn ? $jatuhTempo->diffInDays($realisasi, false) : 0;
                        $denda = $telatHari * 1000;
                    @endphp
                    <tr class="hover:bg-slate-800/30 transition">
                        <td class="px-6 py-4 text-slate-300">
                            {{ $realisasi->format('H:i') }} <span class="text-xs text-slate-500">{{ $realisasi->format('d M Y') }}</span>
                        </td>
                        <td class="px-6 py-4 font-medium text-white">
                            {{ $r->anggota->nama ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-slate-300">
                            {{ $r->buku->judul ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($isLateReturn)
                                <span class="text-amber-400 font-bold">{{ $telatHari }} Hari</span>
                            @else
                                <span class="text-emerald-400 font-bold">Tepat Waktu</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right font-bold {{ $denda > 0 ? 'text-red-400' : 'text-slate-500' }}">
                            @if($denda > 0)
                                Rp {{ number_format($denda, 0, ',', '.') }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-500 text-sm">
                            Belum ada pengembalian buku hari ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection