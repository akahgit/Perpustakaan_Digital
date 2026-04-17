@extends('layouts.kepala')

@section('title', 'Laporan & Arsip — Kepala Perpustakaan')
@section('page-title', 'Laporan & Arsip')
@section('page-subtitle', 'Manajemen pelaporan aktivitas sistem dan arsip data bulanan')

@section('content')
<div class="space-y-6 animate-fade-in-down">

    {{-- ══ KONFIGURASI PERIODE LAPORAN ══ --}}
    <div class="bg-[#1e293b] rounded-3xl border border-white/5 shadow-xl relative overflow-hidden group">
        {{-- Decorative Glow --}}
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-indigo-500/5 rounded-full blur-3xl group-hover:bg-indigo-500/10 transition-all"></div>
        
        <div class="p-8 relative z-10">
            <h3 class="text-sm font-bold text-white uppercase tracking-wider mb-6 flex items-center gap-2">
                <i class="fas fa-calendar-check text-indigo-400"></i> Pilih Periode Laporan
            </h3>
            
            <form action="{{ route('kepala.laporan') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
                <div class="md:col-span-1 space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest ml-1">Bulan Operasional</label>
                    <div class="relative">
                        <select name="bulan" class="w-full bg-slate-900/60 border border-white/8 rounded-2xl px-4 py-3 text-slate-300 text-sm focus:outline-none focus:border-indigo-500/50 appearance-none cursor-pointer">
                            @foreach($listBulan as $key => $nama)
                                <option value="{{ $key }}" {{ $bulan == $key ? 'selected' : '' }}>{{ $nama }}</option>
                            @endforeach
                        </select>
                        <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-600 pointer-events-none text-[10px]"></i>
                    </div>
                </div>

                <div class="md:col-span-1 space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest ml-1">Tahun Anggaran</label>
                    <div class="relative">
                        <select name="tahun" class="w-full bg-slate-900/60 border border-white/8 rounded-2xl px-4 py-3 text-slate-300 text-sm focus:outline-none focus:border-indigo-500/50 appearance-none cursor-pointer">
                            @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                                <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                        <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-600 pointer-events-none text-[10px]"></i>
                    </div>
                </div>

                <div class="md:col-span-2 flex gap-3">
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-3 rounded-2xl transition flex items-center justify-center gap-2 text-sm shadow-xl shadow-indigo-500/20">
                        <i class="fas fa-filter"></i> Perbarui Analisa
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ══ STATISTIK PERIODE TERPILIH ══ --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-[#1e293b] rounded-2xl p-6 border border-white/5 shadow-xl relative group overflow-hidden">
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-blue-500/5 rounded-full blur-2xl"></div>
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Volume Transaksi</p>
            <h3 class="text-3xl font-extrabold text-white">{{ number_format($totalTransaksi) }}</h3>
            <p class="text-[10px] text-slate-600 mt-2">Periode {{ $listBulan[$bulan] }} {{ $tahun }}</p>
        </div>

        <div class="bg-[#1e293b] rounded-2xl p-6 border border-white/5 shadow-xl relative group overflow-hidden">
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-emerald-500/5 rounded-full blur-2xl"></div>
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Total Pendapatan</p>
            <h3 class="text-3xl font-extrabold text-emerald-400">Rp {{ number_format($totalDenda, 0, ',', '.') }}</h3>
            <p class="text-[10px] text-slate-600 mt-2">Penerimaan Denda Telah Lunas</p>
        </div>

        <div class="bg-[#1e293b] rounded-2xl p-6 border border-white/5 shadow-xl relative group overflow-hidden">
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-amber-500/5 rounded-full blur-2xl"></div>
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Koleksi Terpopuler</p>
            @if($topBuku->count() > 0)
                <h3 class="text-base font-extrabold text-white truncate pr-4">{{ $topBuku->first()->buku->judul ?? '-' }}</h3>
                <p class="text-[10px] text-amber-500 font-bold mt-2 uppercase tracking-tighter">{{ $topBuku->first()->total }}x Diminati</p>
            @else
                <hgroup class="flex flex-col">
                    <h3 class="text-base font-extrabold text-slate-600">No Data</h3>
                    <p class="text-[10px] text-slate-600 mt-2 uppercase tracking-tighter">Belum ada peminjaman</p>
                </hgroup>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-[#1e293b] rounded-2xl p-6 border border-white/5 shadow-xl relative group overflow-hidden">
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Kasus Buku Rusak</p>
            <h3 class="text-3xl font-extrabold text-amber-400">{{ number_format($kasusBukuRusak) }}</h3>
            <p class="text-[10px] text-slate-600 mt-2">Denda standar Rp 50.000 per kejadian</p>
        </div>

        <div class="bg-[#1e293b] rounded-2xl p-6 border border-white/5 shadow-xl relative group overflow-hidden">
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Kasus Buku Hilang</p>
            <h3 class="text-3xl font-extrabold text-rose-400">{{ number_format($kasusBukuHilang) }}</h3>
            <p class="text-[10px] text-slate-600 mt-2">Denda mengikuti harga pengganti buku</p>
        </div>

        <div class="bg-[#1e293b] rounded-2xl p-6 border border-white/5 shadow-xl relative group overflow-hidden">
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Kerugian Inventaris</p>
            <h3 class="text-3xl font-extrabold text-white">Rp {{ number_format($kerugianInventaris, 0, ',', '.') }}</h3>
            <p class="text-[10px] text-slate-600 mt-2">Akumulasi denda kerusakan, kehilangan, dan gabungan</p>
        </div>
    </div>

    {{-- ══ ANALISA EKSEKUTIF BULANAN ══ --}}
    @if($totalTransaksi > 0)
    <div class="space-y-6">
        <div class="flex items-center gap-4 mb-2">
            <div class="h-px flex-1 bg-white/5"></div>
            <h2 class="text-[10px] font-black text-indigo-400 uppercase tracking-[0.4em] whitespace-nowrap">Analisis Strategis {{ $namaBulan }}</h2>
            <div class="h-px flex-1 bg-white/5"></div>
        </div>

        {{-- EXECUTIVE INSIGHT CARD --}}
        <div class="bg-gradient-to-r from-indigo-600/20 to-purple-600/20 rounded-[32px] p-8 border border-white/5 backdrop-blur-xl mb-6">
            <div class="flex items-start gap-6">
                <div class="w-12 h-12 rounded-2xl bg-indigo-600 flex items-center justify-center text-white shadow-lg shadow-indigo-600/30">
                    <i class="fas fa-lightbulb text-xl"></i>
                </div>
                <div>
                    <h4 class="text-white font-bold text-lg mb-1">Executive Summary</h4>
                    <p class="text-slate-400 text-sm leading-relaxed">{{ $insight }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Performance & Trend Analysis --}}
            <div class="lg:col-span-1 bg-[#1e293b] rounded-[32px] p-8 border border-white/5 relative overflow-hidden group">
                <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-indigo-500/5 rounded-full blur-3xl"></div>
                
                <h3 class="text-white font-bold text-sm mb-6 flex items-center gap-2">
                    <i class="fas fa-chart-line text-indigo-400"></i> Ringkasan Performa
                </h3>

                <div class="space-y-6 relative z-10">
                    <div>
                        <div class="flex justify-between items-end mb-2">
                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Growth Rate</span>
                            <span class="{{ $statusTren == 'Naik' ? 'text-emerald-400' : 'text-rose-400' }} text-xs font-black uppercase tracking-tighter">
                                {{ $statusTren }} {{ number_format(abs($trenPinjam), 1) }}%
                            </span>
                        </div>
                        <div class="h-2 bg-slate-900 rounded-full overflow-hidden flex">
                            @if($statusTren == 'Naik')
                                <div class="h-full bg-emerald-500 rounded-full" style="width: {{ min(100, abs($trenPinjam)) }}%"></div>
                            @else
                                <div class="h-full bg-rose-500 rounded-full" style="width: {{ min(100, abs($trenPinjam)) }}%"></div>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-white/3 p-4 rounded-2xl border border-white/5">
                            <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mb-1">Bulan Lalu</p>
                            <p class="text-xl font-black text-white">{{ number_format($prevPinjam) }}</p>
                        </div>
                        <div class="bg-indigo-500/10 p-4 rounded-2xl border border-indigo-500/20">
                            <p class="text-[9px] font-bold text-indigo-400 uppercase tracking-widest mb-1">Sekarang</p>
                            <p class="text-xl font-black text-white">{{ number_format($totalTransaksi) }}</p>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-white/5">
                        <div class="flex justify-between items-end mb-2">
                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Kepatuhan Pengembalian</span>
                            <span class="text-indigo-400 text-xs font-black">{{ number_format($persenTepat, 1) }}%</span>
                        </div>
                        <div class="h-2 bg-slate-900 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full" style="width: {{ $persenTepat }}%"></div>
                        </div>
                        <p class="text-[9px] text-slate-600 mt-2 italic font-medium">* {{ $tepat }} buku tepat waktu, {{ $telat }} buku terlambat.</p>
                    </div>
                </div>
            </div>

            {{-- Top Contents & Categories --}}
            <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Top 5 Buku --}}
                <div class="bg-[#1e293b] rounded-[32px] p-8 border border-white/5">
                    <h3 class="text-white font-bold text-sm mb-6 flex items-center justify-between">
                        <span class="flex items-center gap-2"><i class="fas fa-book-open text-amber-400"></i> Buku Terpopuler</span>
                        <span class="text-[9px] text-slate-500 font-black uppercase tracking-widest">Total Sesi</span>
                    </h3>
                    <div class="space-y-3">
                        @foreach($topBuku as $i => $item)
                        <div class="flex items-center gap-3 p-2 hover:bg-white/3 rounded-xl transition group">
                            <div class="w-8 h-8 rounded-lg bg-slate-900 flex items-center justify-center text-[10px] font-black text-slate-500">
                                {{ $i + 1 }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-xs font-bold text-white truncate">{{ $item->buku->judul ?? '-' }}</h4>
                                <p class="text-[9px] text-slate-500 font-medium truncate">{{ $item->buku->kategori->nama_kategori ?? 'Umum' }}</p>
                            </div>
                            <div class="text-xs font-black text-indigo-400">{{ $item->total }}x</div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Categories & Members --}}
                <div class="space-y-6">
                    {{-- Categories --}}
                    <div class="bg-[#1e293b] rounded-[32px] p-6 border border-white/5">
                        <h3 class="text-white font-bold text-[11px] mb-4 uppercase tracking-widest flex items-center gap-2">
                            <i class="fas fa-tags text-purple-400"></i> Distribusi Kategori
                        </h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($kategoriStats->take(6) as $kat)
                            <div class="flex items-center gap-2 px-3 py-1.5 bg-white/3 rounded-full border border-white/5">
                                <span class="text-[9px] font-bold text-slate-400 truncate max-w-[80px]">{{ $kat->nama_kategori }}</span>
                                <span class="text-[9px] font-black text-white">{{ $kat->total }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Members --}}
                    <div class="bg-[#1e293b] rounded-[32px] p-6 border border-white/5">
                        <h3 class="text-white font-bold text-[11px] mb-4 uppercase tracking-widest flex items-center gap-2">
                            <i class="fas fa-id-card text-emerald-400"></i> Anggota Teraktif
                        </h3>
                        <div class="space-y-3">
                            @foreach($topAnggota->take(3) as $agt)
                            <div class="flex items-center gap-3">
                                <div class="w-7 h-7 rounded-full bg-emerald-500/10 flex items-center justify-center text-[10px] font-black text-emerald-400">
                                    {{ substr($agt->anggota->nama ?? '?', 0, 1) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-[10px] font-bold text-white truncate">{{ $agt->anggota->nama ?? '-' }}</h4>
                                </div>
                                <div class="text-[10px] font-black text-slate-500">{{ $agt->total }} Transaksi</div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- ══ ARSIP LAPORAN SISTEM ══ --}}
    <div class="bg-[#1e293b] rounded-3xl border border-white/5 shadow-xl overflow-hidden mt-8">
        <div class="px-8 py-6 border-b border-white/5 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-white uppercase tracking-wider">Arsip Laporan Digital</h3>
                <p class="text-xs text-slate-500 mt-0.5">Daftar laporan validasi sistem yang siap diunduh</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-indigo-500/10 flex items-center justify-center text-indigo-400">
                <i class="fas fa-folder-open"></i>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-white/3 border-b border-white/5">
                        <th class="px-8 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-[0.12em]">Judul Arsip</th>
                        <th class="px-8 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-[0.12em]">Kategori</th>
                        <th class="px-8 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-[0.12em] text-center">Periode</th>
                        <th class="px-8 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-[0.12em] text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/3">
                    @forelse($riwayatLaporan as $laporan)
                    <tr class="table-row-hover transition-colors group">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-slate-900 border border-white/5 flex items-center justify-center text-indigo-400 group-hover:scale-110 transition shadow-lg">
                                    <i class="fas fa-file-signature text-sm"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-slate-200 group-hover:text-white transition">{{ $laporan['judul'] }}</div>
                                    <div class="text-[10px] text-slate-600 font-mono mt-0.5">SIZE: {{ $laporan['file_size'] }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <span class="px-2.5 py-1 rounded-lg bg-white/5 text-slate-400 text-[10px] font-bold uppercase tracking-wider border border-white/5">
                                {{ $laporan['jenis'] }}
                            </span>
                        </td>
                        <td class="px-8 py-5 text-center">
                            <div class="text-xs font-medium text-slate-400 tabular-nums">
                                {{ \Carbon\Carbon::parse($laporan['tanggal'])->format('d M Y') }}
                            </div>
                        </td>
                        <td class="px-8 py-5 text-center">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-500/10 text-emerald-400 text-[10px] font-extrabold rounded-full border border-emerald-500/20">
                                <i class="fas fa-check-circle text-[8px]"></i> GENERATED
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-white/3 rounded-3xl flex items-center justify-center mb-6">
                                    <i class="fas fa-folder-open text-slate-700 text-3xl"></i>
                                </div>
                                <h4 class="text-slate-300 font-bold">Arsip Kosong</h4>
                                <p class="text-xs text-slate-500 mt-1 max-w-[240px] leading-relaxed mx-auto">Tidak ditemukan data laporan untuk periode {{ $listBulan[$bulan] }} {{ $tahun }}.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
