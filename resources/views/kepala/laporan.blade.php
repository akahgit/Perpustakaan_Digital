@extends('layouts.kepala')

@section('title', 'Laporan & Arsip - Kepala Perpustakaan')
@section('page-title', 'Laporan & Arsip')

@section('content')
<div class="space-y-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-white">Kelola dan Unduh Laporan</h2>
            <p class="text-slate-400 text-sm">Pantau kinerja perpustakaan dan unduh laporan bulanan.</p>
        </div>
        @if(session('success'))
            <div class="bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 px-4 py-2 rounded-lg text-sm animate-pulse">
                {{ session('success') }}
            </div>
        @endif
    </div>

    <!-- 1. FILTER BULAN & TAHUN (FORM AKTIF) -->
    <div class="bg-[#1e293b] rounded-2xl border border-slate-700/50 p-6 shadow-xl">
        <form action="{{ route('kepala.laporan') }}" method="GET" class="flex flex-col lg:flex-row gap-4 items-end">
            
            <!-- Pilih Bulan -->
            <div class="w-full lg:w-1/4">
                <label class="block text-xs font-semibold text-slate-400 uppercase mb-2">Bulan</label>
                <select name="bulan" class="w-full bg-slate-800 border border-slate-600 rounded-xl px-4 py-3 text-white focus:border-blue-500 outline-none transition appearance-none">
                    @foreach($listBulan as $key => $nama)
                        <option value="{{ $key }}" {{ $bulan == $key ? 'selected' : '' }}>{{ $nama }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Pilih Tahun -->
            <div class="w-full lg:w-1/4">
                <label class="block text-xs font-semibold text-slate-400 uppercase mb-2">Tahun</label>
                <select name="tahun" class="w-full bg-slate-800 border border-slate-600 rounded-xl px-4 py-3 text-white focus:border-blue-500 outline-none transition">
                    @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>

            <!-- Tombol Filter -->
            <div class="w-full lg:flex-1 flex gap-2">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-blue-500/30 transition flex items-center justify-center gap-2">
                    <i class="fas fa-filter"></i> Tampilkan Data
                </button>
                
                <!-- Tombol Download (Hanya aktif jika ada data) -->
                @if($totalTransaksi > 0)
                    <form action="{{ route('kepala.laporan.download') }}" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="bulan" value="{{ $bulan }}">
                        <input type="hidden" name="tahun" value="{{ $tahun }}">
                        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-emerald-500/30 transition flex items-center justify-center gap-2">
                            <i class="fas fa-file-pdf"></i> Download PDF
                        </button>
                    </form>
                @else
                    <button disabled class="flex-1 bg-slate-700 text-slate-500 font-bold py-3 px-6 rounded-xl cursor-not-allowed flex items-center justify-center gap-2">
                        <i class="fas fa-file-pdf"></i> Tidak Ada Data
                    </button>
                @endif
            </div>
        </form>
    </div>

    <!-- 2. STATISTIK BULAN INI (DINAMIS) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total Transaksi -->
        <div class="bg-[#1e293b] rounded-2xl p-6 border border-slate-700/50 shadow-xl flex items-center justify-between">
            <div>
                <p class="text-slate-400 text-sm font-medium mb-1">Total Transaksi ({{ $listBulan[$bulan] }})</p>
                <h3 class="text-3xl font-bold text-white">{{ number_format($totalTransaksi) }}</h3>
            </div>
            <div class="w-14 h-14 bg-blue-500/20 rounded-xl flex items-center justify-center text-blue-400">
                <i class="fas fa-receipt text-2xl"></i>
            </div>
        </div>

        <!-- Total Denda -->
        <div class="bg-[#1e293b] rounded-2xl p-6 border border-emerald-500/30 shadow-xl flex items-center justify-between">
            <div>
                <p class="text-emerald-200 text-sm font-medium mb-1">Pendapatan Denda</p>
                <h3 class="text-3xl font-bold text-emerald-400">Rp {{ number_format($totalDenda, 0, ',', '.') }}</h3>
            </div>
            <div class="w-14 h-14 bg-emerald-500/20 rounded-xl flex items-center justify-center text-emerald-400">
                <i class="fas fa-coins text-2xl"></i>
            </div>
        </div>

        <!-- Buku Terpopuler -->
        <div class="bg-[#1e293b] rounded-2xl p-6 border border-amber-500/30 shadow-xl flex items-center justify-between">
            <div>
                <p class="text-amber-200 text-sm font-medium mb-1">Buku Terpopuler</p>
                @if($topBuku->count() > 0)
                    <h3 class="text-lg font-bold text-white truncate w-48">{{ $topBuku->first()->buku->judul ?? '-' }}</h3>
                    <p class="text-xs text-slate-500 mt-1">{{ $topBuku->first()->total }}x dipinjam</p>
                @else
                    <h3 class="text-lg font-bold text-slate-500">-</h3>
                @endif
            </div>
            <div class="w-14 h-14 bg-amber-500/20 rounded-xl flex items-center justify-center text-amber-400">
                <i class="fas fa-crown text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- 3. TABEL RIWAYAT LAPORAN -->
    <div class="bg-[#1e293b] rounded-2xl border border-slate-700/50 shadow-xl overflow-hidden">
        <div class="p-6 border-b border-slate-700/50">
            <h3 class="text-lg font-bold text-white">Arsip Laporan Tersedia</h3>
            <p class="text-sm text-slate-400">Daftar laporan yang dapat diunduh untuk periode terpilih.</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-800/50 text-xs uppercase tracking-wider text-slate-400">
                        <th class="px-6 py-4 font-semibold">Judul Laporan</th>
                        <th class="px-6 py-4 font-semibold">Jenis</th>
                        <th class="px-6 py-4 font-semibold">Periode</th>
                        <th class="px-6 py-4 font-semibold text-center">Status</th>
                        <th class="px-6 py-4 font-semibold text-center">Ukuran</th>
                        <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/50 text-sm">
                    @forelse($riwayatLaporan as $laporan)
                    <tr class="hover:bg-slate-800/30 transition group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-blue-500/20 flex items-center justify-center text-blue-400">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <div class="font-bold text-white group-hover:text-blue-300 transition">{{ $laporan['judul'] }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-slate-700 text-slate-300 border border-slate-600">
                                {{ $laporan['jenis'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-400">{{ \Carbon\Carbon::parse($laporan['tanggal'])->format('d F Y') }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                <i class="fas fa-check-circle text-[10px]"></i> Siap Unduh
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center text-slate-400 font-mono">{{ $laporan['file_size'] }}</td>
                        <td class="px-6 py-4 text-center">
                            <form action="{{ route('kepala.laporan.download') }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="bulan" value="{{ $bulan }}">
                                <input type="hidden" name="tahun" value="{{ $tahun }}">
                                <button type="submit" class="w-8 h-8 rounded-lg bg-emerald-500/10 hover:bg-emerald-500 text-emerald-400 hover:text-white flex items-center justify-center transition" title="Download PDF">
                                    <i class="fas fa-download text-xs"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                            <i class="fas fa-inbox text-4xl mb-3 opacity-50"></i>
                            <p>Belum ada laporan yang tersedia untuk periode ini.</p>
                            <p class="text-xs mt-1">Lakukan transaksi terlebih dahulu untuk menghasilkan laporan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection