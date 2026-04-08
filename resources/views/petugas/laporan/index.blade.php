@extends('layouts.petugas')

@section('title', 'Laporan Operasional Perpustakaan')
@section('page-title', 'Laporan Operasional')

@section('content')
    <div class="space-y-6">

        <!-- HEADER & AKSI -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
            <div>
                <h2 class="text-2xl font-bold text-white">Laporan Operasional Perpustakaan</h2>
                <p class="text-slate-400 text-sm">Analisis kinerja periode: <strong
                        class="text-indigo-400">{{ $namaBulan }} {{ $tahun }}</strong></p>
            </div>
            <div class="flex gap-2">
                <!-- Tombol Cetak Laporan (Print Browser) -->
                <button onclick="window.print()"
                    class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white text-sm font-semibold rounded-lg shadow flex items-center gap-2 transition print:hidden">
                    <i class="fas fa-print"></i> Cetak Laporan
                </button>
                <a href="{{ route('petugas.laporan.index') }}"
                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold rounded-lg shadow flex items-center gap-2 transition">
                    <i class="fas fa-sync-alt"></i> Refresh Data
                </a>
            </div>
        </div>

        <!-- FILTER BULAN -->
        <div
            class="bg-[#1e293b] p-4 rounded-xl border border-slate-700/50 shadow-lg flex flex-wrap gap-4 items-end print:hidden">
            <form action="{{ route('petugas.laporan.index') }}" method="GET" class="flex flex-wrap gap-4 w-full">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-xs font-semibold text-slate-400 uppercase mb-1">Bulan</label>
                    <select name="bulan" onchange="this.form.submit()"
                        class="w-full bg-slate-800 border border-slate-600 rounded-lg px-3 py-2 text-white focus:border-indigo-500 outline-none">
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}"
                                {{ $bulan == str_pad($m, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-xs font-semibold text-slate-400 uppercase mb-1">Tahun</label>
                    <select name="tahun" onchange="this.form.submit()"
                        class="w-full bg-slate-800 border border-slate-600 rounded-lg px-3 py-2 text-white focus:border-indigo-500 outline-none">
                        @for ($y = date('Y'); $y >= date('Y') - 5; $y--)
                            <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}
                            </option>
                        @endfor
                    </select>
                </div>
            </form>
        </div>

        <!-- KARTU STATISTIK UTAMA (KPI) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Peminjaman -->
            <div class="bg-[#1e293b] p-6 rounded-2xl border border-slate-700/50 shadow-xl relative overflow-hidden group">
                <div
                    class="absolute right-0 top-0 w-24 h-24 bg-indigo-500/10 rounded-full blur-2xl -mr-10 -mt-10 group-hover:bg-indigo-500/20 transition">
                </div>
                <div class="relative z-10">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Total Peminjaman</p>
                            <h3 class="text-3xl font-bold text-white mt-1">{{ number_format($totalPinjam) }}</h3>
                        </div>
                        <div class="w-10 h-10 bg-indigo-500/20 rounded-lg flex items-center justify-center text-indigo-400">
                            <i class="fas fa-book-reader text-lg"></i>
                        </div>
                    </div>
                    <div class="text-xs mt-2">
                        @if ($trenStatus == 'Naik')
                            <span class="text-emerald-400 font-bold"><i class="fas fa-arrow-up"></i>
                                {{ number_format(abs($trenPersen), 1) }}%</span>
                        @else
                            <span class="text-red-400 font-bold"><i class="fas fa-arrow-down"></i>
                                {{ number_format(abs($trenPersen), 1) }}%</span>
                        @endif
                        <span class="text-slate-500 ml-1">dari bulan lalu</span>
                    </div>
                </div>
            </div>

            <!-- Total Pengembalian -->
            <div class="bg-[#1e293b] p-6 rounded-2xl border border-slate-700/50 shadow-xl relative overflow-hidden group">
                <div
                    class="absolute right-0 top-0 w-24 h-24 bg-emerald-500/10 rounded-full blur-2xl -mr-10 -mt-10 group-hover:bg-emerald-500/20 transition">
                </div>
                <div class="relative z-10">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Pengembalian</p>
                            <h3 class="text-3xl font-bold text-white mt-1">{{ number_format($totalKembali) }}</h3>
                        </div>
                        <div
                            class="w-10 h-10 bg-emerald-500/20 rounded-lg flex items-center justify-center text-emerald-400">
                            <i class="fas fa-undo text-lg"></i>
                        </div>
                    </div>
                    <div class="text-xs mt-2">
                        <span class="text-slate-400">Tingkat Kepatuhan:</span>
                        <span class="text-emerald-400 font-bold">{{ number_format($persenKepatuhan, 1) }}%</span>
                    </div>
                </div>
            </div>

            <!-- Pendapatan Denda -->
            <div class="bg-[#1e293b] p-6 rounded-2xl border border-slate-700/50 shadow-xl relative overflow-hidden group">
                <div
                    class="absolute right-0 top-0 w-24 h-24 bg-amber-500/10 rounded-full blur-2xl -mr-10 -mt-10 group-hover:bg-amber-500/20 transition">
                </div>
                <div class="relative z-10">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Pendapatan Denda</p>
                            <h3 class="text-2xl font-bold text-amber-400 mt-1">Rp
                                {{ number_format($totalDendaDiterima, 0, ',', '.') }}</h3>
                        </div>
                        <div class="w-10 h-10 bg-amber-500/20 rounded-lg flex items-center justify-center text-amber-400">
                            <i class="fas fa-coins text-lg"></i>
                        </div>
                    </div>
                    <div class="text-xs mt-2">
                        <span class="text-red-400 font-bold">Piutang: Rp
                            {{ number_format($totalPiutang, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Anggota Baru -->
            <div class="bg-[#1e293b] p-6 rounded-2xl border border-slate-700/50 shadow-xl relative overflow-hidden group">
                <div
                    class="absolute right-0 top-0 w-24 h-24 bg-purple-500/10 rounded-full blur-2xl -mr-10 -mt-10 group-hover:bg-purple-500/20 transition">
                </div>
                <div class="relative z-10">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Anggota Baru</p>
                            <h3 class="text-3xl font-bold text-white mt-1">{{ number_format($anggotaBaru) }}</h3>
                        </div>
                        <div class="w-10 h-10 bg-purple-500/20 rounded-lg flex items-center justify-center text-purple-400">
                            <i class="fas fa-user-plus text-lg"></i>
                        </div>
                    </div>
                    <div class="text-xs mt-2">
                        <span class="text-slate-400">Total Aktif:</span>
                        <span class="text-white font-bold">{{ number_format($anggotaAktif) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- GRAFIK & TABEL POPULER -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Grafik Harian (2 Kolom) -->
            <div class="lg:col-span-2 bg-[#1e293b] p-6 rounded-2xl border border-slate-700/50 shadow-xl">
                <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                    <i class="fas fa-chart-line text-indigo-400"></i> Grafik Peminjaman Harian
                </h3>
                <div class="relative h-64 w-full">
                    <canvas id="dailyChart"></canvas>
                </div>
            </div>

            <!-- Top Buku (1 Kolom) -->
            <div class="bg-[#1e293b] p-6 rounded-2xl border border-slate-700/50 shadow-xl">
                <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                    <i class="fas fa-crown text-amber-400"></i> 5 Buku Terpopuler
                </h3>
                <div class="space-y-4">
                    @forelse($bukuPopuler as $index => $item)
                        <div class="flex items-center gap-3 pb-3 border-b border-slate-700/50 last:border-0">
                            <div
                                class="w-8 h-8 rounded-full bg-slate-700 flex items-center justify-center text-xs font-bold text-white">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-medium text-white truncate">
                                    {{ $item->buku->judul ?? 'Buku Dihapus' }}</div>
                                <div class="text-xs text-slate-500 truncate">
                                    {{ $item->buku->kategori->nama_kategori ?? '-' }}</div>
                            </div>
                            <div class="text-xs font-bold text-indigo-400 bg-indigo-500/10 px-2 py-1 rounded">
                                {{ $item->total }}x
                            </div>
                        </div>
                    @empty
                        <p class="text-slate-500 text-sm text-center py-4">Belum ada data peminjaman.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- TABEL RINCIAN TRANSAKSI (LAMPIRAN) -->
        <div class="bg-[#1e293b] rounded-2xl border border-slate-700/50 shadow-xl overflow-hidden">
            <div class="p-6 border-b border-slate-700/50">
                <h3 class="text-lg font-bold text-white">Rincian Transaksi Periode Ini</h3>
                <p class="text-xs text-slate-400 mt-1">Daftar seluruh peminjaman yang terjadi antara
                    {{ $startDate->format('d M Y') }} s/d {{ $endDate->format('d M Y') }}</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead class="bg-slate-800/50 text-xs uppercase text-slate-400">
                        <tr>
                            <th class="px-6 py-3 font-semibold">Tanggal</th>
                            <th class="px-6 py-3 font-semibold">Anggota</th>
                            <th class="px-6 py-3 font-semibold">Buku</th>
                            <th class="px-6 py-3 font-semibold text-center">Status</th>
                            <th class="px-6 py-3 font-semibold text-right">Denda</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700/50 text-slate-300">
                        @forelse($transaksiDetail as $t)
                            <tr class="hover:bg-slate-800/30 transition">
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="text-white">{{ $t->tanggal_pinjam->format('d M Y') }}</div>
                                    @if ($t->tanggal_kembali_realisasi)
                                        <div class="text-xs text-slate-500">Kembali:
                                            {{ $t->tanggal_kembali_realisasi->format('d M Y') }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-3">
                                    <div class="font-medium text-white">{{ $t->anggota->nama ?? '-' }}</div>
                                    <div class="text-xs text-slate-500">{{ $t->anggota->nis_nisn ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-3">
                                    <div class="text-white truncate max-w-xs">{{ $t->buku->judul ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-3 text-center">
                                    @if ($t->status_peminjaman == 'dikembalikan')
                                        <span
                                            class="px-2 py-1 rounded text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">Kembali</span>
                                    @elseif($t->status_peminjaman == 'dipinjam')
                                        <span
                                            class="px-2 py-1 rounded text-xs font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20">Dipinjam</span>
                                    @else
                                        <span
                                            class="px-2 py-1 rounded text-xs font-semibold bg-blue-500/10 text-blue-400 border border-blue-500/20">{{ ucfirst($t->status_peminjaman) }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3 text-right font-mono">
                                    @php
                                        $denda = 0;
                                        // Cek apakah relasi denda ada dan merupakan koleksi
                                        if ($t->denda && !$t->denda->isEmpty()) {
                                            // Ambil denda yang sudah ada status pembayarannya (lunas/belum_lunas)
                                            $dendaItem = $t->denda->firstWhere('status_pembayaran', '!=', null);
                                            if ($dendaItem) {
                                                $denda = $dendaItem->jumlah_denda;
                                            }
                                        }
                                    @endphp
                                    @if ($denda > 0)
                                        <div class="text-red-400">Rp {{ number_format($denda, 0, ',', '.') }}</div>
                                    @else
                                        <span class="text-slate-600">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-slate-500">Tidak ada transaksi pada
                                    periode ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Script Chart.js -->
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const ctx = document.getElementById('dailyChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($labelsGrafik) !!},
                    datasets: [{
                        label: 'Jumlah Peminjaman',
                        data: {!! json_encode($grafikData) !!},
                        backgroundColor: '#6366f1',
                        borderRadius: 4,
                        barPercentage: 0.6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#334155'
                            },
                            ticks: {
                                color: '#94a3b8'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#94a3b8'
                            }
                        }
                    }
                }
            });
        </script>
    @endpush

    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            .space-y-6,
            .space-y-6 * {
                visibility: visible;
            }

            .space-y-6 {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }

            button,
            select,
            form,
            .print-hidden {
                display: none !important;
            }

            .bg-\[#1e293b\] {
                background-color: #fff !important;
                color: #000 !important;
                border: 1px solid #ddd !important;
                box-shadow: none !important;
            }

            .text-white,
            h2,
            h3,
            div,
            span,
            p,
            td,
            th {
                color: #000 !important;
            }

            .text-slate-400,
            .text-slate-500,
            .text-slate-300 {
                color: #555 !important;
            }

            .border-slate-700\/50 {
                border-color: #ddd !important;
            }

            /* Force table to look good on print */
            table {
                width: 100%;
                font-size: 10pt;
            }

            th {
                background-color: #eee !important;
                color: #000 !important;
            }
        }
    </style>
@endsection
