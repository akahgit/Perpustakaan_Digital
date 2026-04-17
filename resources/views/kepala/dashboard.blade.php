@extends('layouts.kepala')

@section('title', 'Dashboard Eksekutif — Kepala Perpustakan Digital')
@section('page-title', 'Dashboard Eksekutif')
@section('page-subtitle', 'Analisis data koleksi dan operasional sistem secara real-time')

@section('content')
<div class="space-y-6 animate-fade-in-down">

    {{-- ══ WELCOME CARD (EXECUTIVE STYLE) ══ --}}
    <div class="relative overflow-hidden bg-[#1e293b] rounded-3xl border border-white/5 shadow-2xl p-8 group">
        {{-- Decorative Glow Orbs --}}
        <div class="absolute -top-24 -right-24 w-80 h-80 bg-blue-600/10 rounded-full blur-3xl group-hover:bg-blue-600/20 transition-all duration-1000"></div>
        <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-indigo-600/5 rounded-full blur-3xl"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-5">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-2xl shadow-xl shadow-blue-500/20">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-extrabold text-white tracking-tight">Selamat datang, {{ explode(' ', auth()->user()->name)[0] }}!</h2>
                    <p class="text-slate-400 text-sm mt-1 leading-relaxed">Sistem dalam kondisi optimal. Terdapat <span class="text-emerald-400 font-bold">{{ $transaksiBulanIni }} transaksi</span> baru bulan ini.</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="text-right hidden sm:block">
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Laporan Terakhir</p>
                    <p class="text-xs text-slate-300 font-medium">{{ now()->format('d F Y, H:i') }}</p>
                </div>
                <a href="{{ route('kepala.laporan') }}" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold rounded-xl shadow-lg shadow-blue-500/25 transition flex items-center gap-2">
                    <i class="fas fa-file-export"></i> Ekspor Data
                </a>
            </div>
        </div>
    </div>

    {{-- ══ KPI GRID ══ --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
        {{-- Card 1: Koleksi --}}
        <div class="bg-[#1e293b] rounded-2xl p-5 border border-white/5 shadow-xl hover:border-blue-500/30 transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-400 group-hover:scale-110 transition">
                    <i class="fas fa-book"></i>
                </div>
                <span class="text-[10px] font-bold text-emerald-400 bg-emerald-500/10 px-2 py-0.5 rounded-full">+12%</span>
            </div>
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Total Koleksi</p>
            <h3 class="text-2xl font-extrabold text-white mt-1">{{ number_format($totalBuku) }}</h3>
            <p class="text-[10px] text-slate-600 mt-1.5">{{ number_format($totalEksemplar) }} Eksemplar Terdaftar</p>
        </div>

        {{-- Card 2: Anggota --}}
        <div class="bg-[#1e293b] rounded-2xl p-5 border border-white/5 shadow-xl hover:border-purple-500/30 transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-xl bg-purple-500/10 flex items-center justify-center text-purple-400 group-hover:scale-110 transition">
                    <i class="fas fa-users"></i>
                </div>
                <span class="text-[10px] font-bold text-emerald-400 bg-emerald-500/10 px-2 py-0.5 rounded-full">{{ round(($anggotaAktif/$totalAnggota)*100) }}% Aktif</span>
            </div>
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Total Anggota</p>
            <h3 class="text-2xl font-extrabold text-white mt-1">{{ number_format($totalAnggota) }}</h3>
            <p class="text-[10px] text-slate-600 mt-1.5">{{ number_format($totalAnggota - $anggotaAktif) }} Menunggu Aktivasi</p>
        </div>

        {{-- Card 3: Transaksi --}}
        <div class="bg-[#1e293b] rounded-2xl p-5 border border-white/5 shadow-xl hover:border-amber-500/30 transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-xl bg-amber-500/10 flex items-center justify-center text-amber-400 group-hover:scale-110 transition">
                    <i class="fas fa-exchange-alt"></i>
                </div>
                <span class="text-[10px] font-bold text-slate-400 bg-white/5 px-2 py-0.5 rounded-full">Bulan Ini</span>
            </div>
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Votalitas Pinjam</p>
            <h3 class="text-2xl font-extrabold text-white mt-1">{{ number_format($transaksiBulanIni) }}</h3>
            <p class="text-[10px] text-slate-600 mt-1.5">Traffic Sirkulasi Buku</p>
        </div>

        {{-- Card 4: Revenue --}}
        <div class="bg-[#1e293b] rounded-2xl p-5 border border-white/5 shadow-xl hover:border-emerald-500/30 transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-400 group-hover:scale-110 transition">
                    <i class="fas fa-wallet"></i>
                </div>
                <span class="text-[10px] font-bold text-emerald-400 bg-emerald-500/10 px-2 py-0.5 rounded-full">Lunas</span>
            </div>
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Pendapatan Denda</p>
            <h3 class="text-xl font-extrabold text-emerald-400 mt-1">Rp {{ number_format($pendapatanDenda, 0, ',', '.') }}</h3>
            <p class="text-[10px] text-slate-600 mt-1.5">Estimasi Pendapatan Realized</p>
        </div>
    </div>

    {{-- ══ CHARTS SECTION ══ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Analytics --}}
        <div class="lg:col-span-2 bg-[#1e293b] rounded-3xl border border-white/5 shadow-xl p-6">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider">Metrik Sirkulasi Buku</h3>
                    <p class="text-[10px] text-slate-500 mt-0.5">Komparasi data peminjaman vs pengembalian</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Pinjam</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Kembali</span>
                    </div>
                </div>
            </div>
            <div class="h-80 w-full relative">
                <canvas id="trafficChart"></canvas>
            </div>
        </div>

        {{-- Distribution Chart --}}
        <div class="bg-[#1e293b] rounded-3xl border border-white/5 shadow-xl p-6 flex flex-col">
            <div class="mb-8">
                <h3 class="text-sm font-bold text-white uppercase tracking-wider">Distribusi Koleksi</h3>
                <p class="text-[10px] text-slate-500 mt-0.5">Eksistensi fisik buku dalam sistem</p>
            </div>
            <div class="h-56 relative flex justify-center">
                <canvas id="statusChart"></canvas>
            </div>
            <div class="mt-8 space-y-3.5">
                @php
                    $stats = [
                        ['label' => 'Tersedia', 'count' => $bukuTersedia, 'color' => 'bg-blue-500'],
                        ['label' => 'Dipinjam',  'count' => $bukuDipinjam,  'color' => 'bg-amber-500'],
                        ['label' => 'Tidak Aktif', 'count' => $bukuHabis,    'color' => 'bg-rose-500'],
                    ];
                @endphp
                @foreach($stats as $s)
                <div class="flex items-center justify-between group">
                    <div class="flex items-center gap-3">
                        <span class="w-2 h-2 rounded-full {{ $s['color'] }}"></span>
                        <span class="text-xs font-medium text-slate-400 group-hover:text-slate-200 transition">{{ $s['label'] }}</span>
                    </div>
                    <span class="text-xs font-bold text-white tabular-nums">{{ number_format($s['count']) }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ══ HIGHLIGHTS SECTION ══ --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Popular Items --}}
        <div class="bg-[#1e293b] rounded-3xl border border-white/5 shadow-xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-sm font-bold text-white flex items-center gap-2">
                    <i class="fas fa-crown text-amber-400"></i> TOP KOLEKSI TERPOPULER
                </h3>
                <a href="{{ route('kepala.statistik') }}" class="text-[10px] font-bold text-blue-400 hover:text-blue-300 uppercase tracking-widest">Detail →</a>
            </div>
            <div class="space-y-4">
                @forelse($topBuku as $index => $item)
                <div class="flex items-center gap-4 p-3 rounded-2xl hover:bg-white/3 transition group">
                    <div class="w-8 h-8 rounded-lg bg-orange-500/10 flex items-center justify-center text-orange-400 text-xs font-extrabold flex-shrink-0">
                        {{ $index + 1 }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-slate-200 truncate group-hover:text-white transition">{{ $item->buku->judul ?? 'Buku Dihapus' }}</p>
                        <p class="text-[10px] text-slate-500 mt-0.5 truncate">{{ $item->buku->pengarang ?? '-' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-extrabold text-white">{{ $item->total }}x</p>
                        <p class="text-[9px] text-slate-600 font-bold uppercase">Pinjam</p>
                    </div>
                </div>
                @empty
                <p class="text-center py-8 text-xs text-slate-500 font-medium">Belum ada aktivitas pinjam terdata.</p>
                @endforelse
            </div>
        </div>

        {{-- Active Members --}}
        <div class="bg-[#1e293b] rounded-3xl border border-white/5 shadow-xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-sm font-bold text-white flex items-center gap-2">
                    <i class="fas fa-medal text-blue-400"></i> ANGGOTA PALING AKTIF
                </h3>
                <a href="{{ route('kepala.statistik') }}" class="text-[10px] font-bold text-blue-400 hover:text-blue-300 uppercase tracking-widest">Detail →</a>
            </div>
            <div class="space-y-4">
                @forelse($topAnggota as $index => $item)
                <div class="flex items-center gap-4 p-3 rounded-2xl hover:bg-white/3 transition group">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-xs font-bold flex-shrink-0 shadow-lg group-hover:scale-105 transition">
                        {{ strtoupper(substr($item->anggota->nama ?? 'A', 0, 2)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-slate-200 truncate group-hover:text-white transition">{{ $item->anggota->nama ?? 'Anggota Dihapus' }}</p>
                        <p class="text-[10px] text-slate-500 mt-0.5 truncate">{{ $item->anggota->nis_nisn ?? '-' }} &bull; {{ $item->anggota->kelas ?? '-' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-extrabold text-blue-400">{{ $item->total }}x</p>
                        <p class="text-[9px] text-slate-600 font-bold uppercase">Aktivitas</p>
                    </div>
                </div>
                @empty
                <p class="text-center py-8 text-xs text-slate-500 font-medium">Data sirkulasi anggota belum tersedia.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const ctxTraffic = document.getElementById('trafficChart').getContext('2d');
    const ctxStatus = document.getElementById('statusChart').getContext('2d');

    // Chart Design System
    const gradientPinjam = ctxTraffic.createLinearGradient(0, 0, 0, 400);
    gradientPinjam.addColorStop(0, '#3b82f633');
    gradientPinjam.addColorStop(1, '#3b82f600');

    new Chart(ctxTraffic, {
        type: 'line',
        data: {
            labels: {!! json_encode($labels) !!},
            datasets: [
                {
                    label: 'Pinjam',
                    data: {!! json_encode($dataPinjam) !!},
                    borderColor: '#3b82f6',
                    borderWidth: 3,
                    backgroundColor: gradientPinjam,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#3b82f6',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                },
                {
                    label: 'Kembali',
                    data: {!! json_encode($dataKembali) !!},
                    borderColor: '#10b981',
                    borderWidth: 3,
                    tension: 0.4,
                    pointBackgroundColor: '#10b981',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { grid: { color: 'rgba(255,255,255,0.03)' }, ticks: { color: '#64748b', font: { size: 10 } } },
                x: { grid: { display: false }, ticks: { color: '#64748b', font: { size: 10 } } }
            }
        }
    });

    new Chart(ctxStatus, {
        type: 'doughnut',
        data: {
            labels: ['Tersedia', 'Dipinjam', 'Tidak Aktif'],
            datasets: [{
                data: [{{ $bukuTersedia }}, {{ $bukuDipinjam }}, {{ $bukuHabis }}],
                backgroundColor: ['#3b82f6', '#f59e0b', '#ef4444'],
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '82%',
            plugins: { legend: { display: false } }
        }
    });
</script>
@endpush
