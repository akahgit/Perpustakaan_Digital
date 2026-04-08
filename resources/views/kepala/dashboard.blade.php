@extends('layouts.kepala')

@section('title', 'Dashboard Kepala - Perpustakaan Digital')
@section('page-title', 'Dashboard Eksekutif')

@section('content')

<!-- 1. WELCOME HEADER -->
<div class="bg-gradient-to-r from-blue-900 to-indigo-900 rounded-2xl p-8 mb-8 shadow-xl border border-blue-800/50 relative overflow-hidden">
    <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full blur-3xl -mr-16 -mt-16"></div>
    <div class="relative z-10 flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-bold text-white mb-2">Selamat datang, {{ auth()->user()->name }}! 👋</h2>
            <p class="text-blue-200">Berikut ringkasan operasional perpustakaan hari ini.</p>
        </div>
        <div class="hidden md:block text-right">
            <div class="text-sm text-blue-300 mb-1">Status Sistem</div>
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-green-500/20 border border-green-500/30 rounded-full text-green-400 font-semibold text-sm">
                <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span> Online
            </div>
        </div>
    </div>
</div>

<!-- 2. STATISTIK UTAMA (KPI CARDS) -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Koleksi -->
    <div class="bg-[#1e293b] rounded-2xl p-6 border border-slate-700/50 shadow-lg hover:border-blue-500/30 transition group">
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="text-slate-400 text-sm font-medium mb-1">Total Koleksi Buku</p>
                <h3 class="text-3xl font-bold text-white">{{ number_format($totalBuku) }}</h3>
                <p class="text-xs text-slate-500 mt-1">{{ number_format($totalEksemplar) }} Eksemplar Fisik</p>
            </div>
            <div class="w-12 h-12 bg-blue-500/10 rounded-xl flex items-center justify-center text-blue-400 group-hover:scale-110 transition">
                <i class="fas fa-book text-xl"></i>
            </div>
        </div>
        <div class="w-full bg-slate-700 h-1.5 rounded-full overflow-hidden">
            <div class="bg-blue-500 h-full rounded-full" style="width: 75%"></div>
        </div>
    </div>

    <!-- Total Anggota -->
    <div class="bg-[#1e293b] rounded-2xl p-6 border border-slate-700/50 shadow-lg hover:border-purple-500/30 transition group">
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="text-slate-400 text-sm font-medium mb-1">Total Anggota</p>
                <h3 class="text-3xl font-bold text-white">{{ number_format($totalAnggota) }}</h3>
                <p class="text-xs text-emerald-400 mt-1"><i class="fas fa-check-circle"></i> {{ number_format($anggotaAktif) }} Aktif</p>
            </div>
            <div class="w-12 h-12 bg-purple-500/10 rounded-xl flex items-center justify-center text-purple-400 group-hover:scale-110 transition">
                <i class="fas fa-users text-xl"></i>
            </div>
        </div>
        <div class="w-full bg-slate-700 h-1.5 rounded-full overflow-hidden">
            <div class="bg-purple-500 h-full rounded-full" style="width: 85%"></div>
        </div>
    </div>

    <!-- Transaksi Bulan Ini -->
    <div class="bg-[#1e293b] rounded-2xl p-6 border border-slate-700/50 shadow-lg hover:border-amber-500/30 transition group">
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="text-slate-400 text-sm font-medium mb-1">Transaksi Bulan Ini</p>
                <h3 class="text-3xl font-bold text-white">{{ number_format($transaksiBulanIni) }}</h3>
                <p class="text-xs text-slate-500 mt-1">Peminjaman & Pengembalian</p>
            </div>
            <div class="w-12 h-12 bg-amber-500/10 rounded-xl flex items-center justify-center text-amber-400 group-hover:scale-110 transition">
                <i class="fas fa-exchange-alt text-xl"></i>
            </div>
        </div>
        <div class="w-full bg-slate-700 h-1.5 rounded-full overflow-hidden">
            <div class="bg-amber-500 h-full rounded-full" style="width: 60%"></div>
        </div>
    </div>

    <!-- Pendapatan Denda -->
    <div class="bg-[#1e293b] rounded-2xl p-6 border border-slate-700/50 shadow-lg hover:border-emerald-500/30 transition group">
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="text-slate-400 text-sm font-medium mb-1">Pendapatan Denda</p>
                <h3 class="text-3xl font-bold text-emerald-400">Rp {{ number_format($pendapatanDenda, 0, ',', '.') }}</h3>
                <p class="text-xs text-slate-500 mt-1">Estimasi Bulan Ini</p>
            </div>
            <div class="w-12 h-12 bg-emerald-500/10 rounded-xl flex items-center justify-center text-emerald-400 group-hover:scale-110 transition">
                <i class="fas fa-wallet text-xl"></i>
            </div>
        </div>
        <div class="w-full bg-slate-700 h-1.5 rounded-full overflow-hidden">
            <div class="bg-emerald-500 h-full rounded-full" style="width: 45%"></div>
        </div>
    </div>
</div>

<!-- 3. GRAFIK & STATUS BUKU -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Grafik Peminjaman (Lebar 2 Kolom) -->
    <div class="lg:col-span-2 bg-[#1e293b] rounded-2xl border border-slate-700/50 shadow-xl p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-bold text-white">Grafik Peminjaman (6 Bulan Terakhir)</h3>
            <div class="flex gap-2 text-xs">
                <span class="flex items-center gap-1 text-blue-400"><span class="w-3 h-3 bg-blue-500 rounded-sm"></span> Pinjam</span>
                <span class="flex items-center gap-1 text-emerald-400"><span class="w-3 h-3 bg-emerald-500 rounded-sm"></span> Kembali</span>
            </div>
        </div>
        <div class="relative h-72 w-full">
            <canvas id="trafficChart"></canvas>
        </div>
    </div>

    <!-- Status Buku (Lebar 1 Kolom) -->
    <div class="bg-[#1e293b] rounded-2xl border border-slate-700/50 shadow-xl p-6 flex flex-col">
        <h3 class="text-lg font-bold text-white mb-6">Status Ketersediaan Buku</h3>
        <div class="relative h-48 flex-1 flex justify-center">
            <canvas id="statusChart"></canvas>
        </div>
        <div class="mt-6 space-y-3">
            <div class="flex justify-between items-center text-sm">
                <span class="flex items-center gap-2 text-slate-300"><span class="w-3 h-3 rounded-full bg-blue-500"></span> Tersedia</span>
                <span class="font-bold text-white">{{ number_format($bukuTersedia) }}</span>
            </div>
            <div class="flex justify-between items-center text-sm">
                <span class="flex items-center gap-2 text-slate-300"><span class="w-3 h-3 rounded-full bg-amber-500"></span> Dipinjam</span>
                <span class="font-bold text-white">{{ number_format($bukuDipinjam) }}</span>
            </div>
            <div class="flex justify-between items-center text-sm">
                <span class="flex items-center gap-2 text-slate-300"><span class="w-3 h-3 rounded-full bg-red-500"></span> Habis/Rusak</span>
                <span class="font-bold text-white">{{ number_format($bukuHabis) }}</span>
            </div>
        </div>
    </div>
</div>

<!-- 4. TOP POPULER & ANGGOTA AKTIF -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Top 5 Buku Terpopuler -->
    <div class="bg-[#1e293b] rounded-2xl border border-slate-700/50 shadow-xl p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                <i class="fas fa-crown text-amber-400"></i> Top 5 Buku Terpopuler
            </h3>
            <a href="{{ route('petugas.buku.index') }}" class="text-xs text-blue-400 hover:text-blue-300">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="text-xs text-slate-400 uppercase border-b border-slate-700">
                    <tr>
                        <th class="pb-3 font-semibold">Judul Buku</th>
                        <th class="pb-3 font-semibold text-center">Total Pinjam</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-slate-700/50">
                    @forelse($topBuku as $index => $item)
                    <tr class="group hover:bg-slate-800/50 transition">
                        <td class="py-3 pr-4">
                            <div class="font-medium text-white group-hover:text-blue-400 transition truncate">
                                {{ $index + 1 }}. {{ $item->buku->judul ?? 'Buku Dihapus' }}
                            </div>
                            <div class="text-xs text-slate-500 truncate">{{ $item->buku->pengarang ?? '-' }}</div>
                        </td>
                        <td class="py-3 text-center">
                            <span class="inline-block px-3 py-1 bg-amber-500/10 text-amber-400 rounded-full text-xs font-bold border border-amber-500/20">
                                {{ $item->total }}x
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="2" class="py-4 text-center text-slate-500">Belum ada data peminjaman.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Top 5 Anggota Paling Aktif -->
    <div class="bg-[#1e293b] rounded-2xl border border-slate-700/50 shadow-xl p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                <i class="fas fa-user-graduate text-purple-400"></i> Anggota Paling Aktif
            </h3>
            <a href="{{ route('petugas.anggota.index') }}" class="text-xs text-blue-400 hover:text-blue-300">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="text-xs text-slate-400 uppercase border-b border-slate-700">
                    <tr>
                        <th class="pb-3 font-semibold">Nama Anggota</th>
                        <th class="pb-3 font-semibold text-center">Total Transaksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-slate-700/50">
                    @forelse($topAnggota as $index => $item)
                    <tr class="group hover:bg-slate-800/50 transition">
                        <td class="py-3 pr-4">
                            <div class="font-medium text-white group-hover:text-purple-400 transition truncate">
                                {{ $index + 1 }}. {{ $item->anggota->nama ?? 'Anggota Dihapus' }}
                            </div>
                            <div class="text-xs text-slate-500 truncate">{{ $item->anggota->nis_nisn ?? '-' }}</div>
                        </td>
                        <td class="py-3 text-center">
                            <span class="inline-block px-3 py-1 bg-purple-500/10 text-purple-400 rounded-full text-xs font-bold border border-purple-500/20">
                                {{ $item->total }}x
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="2" class="py-4 text-center text-slate-500">Belum ada data anggota.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Konfigurasi Grafik
    const ctxTraffic = document.getElementById('trafficChart').getContext('2d');
    const ctxStatus = document.getElementById('statusChart').getContext('2d');

    // 1. Grafik Batang (Traffic)
    new Chart(ctxTraffic, {
        type: 'bar',
        data: {
            labels: {!! json_encode($labels) !!},
            datasets: [
                {
                    label: 'Dipinjam',
                    data: {!! json_encode($dataPinjam) !!},
                    backgroundColor: '#3b82f6', // Blue-500
                    borderRadius: 4,
                    barPercentage: 0.6
                },
                {
                    label: 'Dikembalikan',
                    data: {!! json_encode($dataKembali) !!},
                    backgroundColor: '#10b981', // Emerald-500
                    borderRadius: 4,
                    barPercentage: 0.6
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#334155' },
                    ticks: { color: '#94a3b8' }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: '#94a3b8' }
                }
            }
        }
    });

    // 2. Grafik Donat (Status Buku)
    new Chart(ctxStatus, {
        type: 'doughnut',
        data: {
            labels: ['Tersedia', 'Dipinjam', 'Habis/Rusak'],
            datasets: [{
                data: [{{ $bukuTersedia }}, {{ $bukuDipinjam }}, {{ $bukuHabis }}],
                backgroundColor: ['#3b82f6', '#f59e0b', '#ef4444'], // Blue, Amber, Red
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: { display: false }
            }
        }
    });
</script>
@endpush