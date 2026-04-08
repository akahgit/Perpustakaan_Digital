@extends('layouts.kepala')

@section('title', 'Statistik & Analitik')
@section('page-title', 'Statistik & Analitik')

@section('content')
<div class="space-y-6">

    <!-- HEADER & FILTER PERIODE -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-white">Analisis Data Perpustakaan</h2>
            <p class="text-slate-400 text-sm">Tren kinerja dan statistik mendalam.</p>
        </div>
        
        <form action="{{ route('kepala.statistik') }}" method="GET" class="flex items-center gap-2 bg-[#1e293b] p-1.5 rounded-xl border border-slate-700">
            <select name="periode" onchange="this.form.submit()" class="bg-slate-800 text-white text-sm rounded-lg px-4 py-2 outline-none border-none focus:ring-2 focus:ring-blue-500">
                <option value="1month" {{ $periode == '1month' ? 'selected' : '' }}>1 Bulan Terakhir</option>
                <option value="3months" {{ $periode == '3months' ? 'selected' : '' }}>3 Bulan Terakhir</option>
                <option value="6months" {{ $periode == '6months' ? 'selected' : '' }}>6 Bulan Terakhir</option>
                <option value="1year" {{ $periode == '1year' ? 'selected' : '' }}>1 Tahun Terakhir</option>
            </select>
            <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                <i class="fas fa-sync-alt"></i>
            </button>
        </form>
    </div>

    <!-- 1. GRAFIK TREN HARIAN (LINE CHART) -->
    <div class="bg-[#1e293b] rounded-2xl border border-slate-700/50 shadow-xl p-6">
        <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
            <i class="fas fa-chart-line text-blue-400"></i> Tren Transaksi Harian
        </h3>
        <div class="relative h-80 w-full">
            <canvas id="trendChart"></canvas>
        </div>
    </div>

    <!-- 2. GRAFIK KATEGORI & KEPATUHAN (GRID) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Kategori Populer (DOUGHNUT) -->
        <div class="bg-[#1e293b] rounded-2xl border border-slate-700/50 shadow-xl p-6">
            <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                <i class="fas fa-shapes text-purple-400"></i> Kategori Terpopuler
            </h3>
            <div class="relative h-64 flex justify-center">
                <canvas id="categoryChart"></canvas>
            </div>
            <!-- Legend Manual -->
            <div class="mt-4 grid grid-cols-2 gap-2">
                @foreach($kategoriData as $kat)
                <div class="flex items-center justify-between text-xs text-slate-300 bg-slate-800/50 p-2 rounded">
                    <span class="truncate">{{ $kat->nama_kategori }}</span>
                    <span class="font-bold text-white">{{ $kat->total }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Kepatuhan Pengembalian (PIE) -->
        <div class="bg-[#1e293b] rounded-2xl border border-slate-700/50 shadow-xl p-6">
            <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                <i class="fas fa-clock text-emerald-400"></i> Kepatuhan Pengembalian
            </h3>
            <div class="relative h-64 flex justify-center">
                <canvas id="complianceChart"></canvas>
            </div>
            <div class="mt-4 text-center">
                <p class="text-sm text-slate-400">Total Selesai: <strong class="text-white">{{ $totalSelesai }}</strong></p>
                <p class="text-xs text-emerald-400 mt-1">Tepat Waktu: {{ $totalSelesai > 0 ? round(($tepatWaktuCount/$totalSelesai)*100, 1) : 0 }}%</p>
            </div>
        </div>
    </div>

    <!-- 3. TOP ANGGOTA AKTIF (BAR HORIZONTAL) -->
    <div class="bg-[#1e293b] rounded-2xl border border-slate-700/50 shadow-xl p-6">
        <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
            <i class="fas fa-users-crown text-amber-400"></i> 10 Anggota Paling Aktif
        </h3>
        <div class="relative h-80 w-full">
            <canvas id="memberChart"></canvas>
        </div>
    </div>

</div>

@push('scripts')
<script>
    // Konfigurasi Warna
    const colors = {
        blue: '#3b82f6',
        emerald: '#10b981',
        purple: '#a855f7',
        amber: '#f59e0b',
        red: '#ef4444',
        slate: '#64748b'
    };

    // 1. TREND CHART (LINE)
    new Chart(document.getElementById('trendChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($labelsTren) !!},
            datasets: [
                {
                    label: 'Dipinjam',
                    data: {!! json_encode($dataPinjam) !!},
                    borderColor: colors.blue,
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Dikembalikan',
                    data: {!! json_encode($dataKembali) !!},
                    borderColor: colors.emerald,
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'top' } },
            scales: {
                y: { beginAtZero: true, grid: { color: '#334155' }, ticks: { color: '#94a3b8' } },
                x: { grid: { display: false }, ticks: { color: '#94a3b8', maxRotation: 45, minRotation: 45 } }
            }
        }
    });

    // 2. CATEGORY CHART (DOUGHNUT)
    new Chart(document.getElementById('categoryChart'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($kategoriData->pluck('nama_kategori')) !!},
            datasets: [{
                data: {!! json_encode($kategoriData->pluck('total')) !!},
                backgroundColor: [colors.blue, colors.purple, colors.amber, colors.emerald, colors.red],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            cutout: '60%'
        }
    });

    // 3. COMPLIANCE CHART (PIE)
    new Chart(document.getElementById('complianceChart'), {
        type: 'pie',
        data: {
            labels: ['Tepat Waktu', 'Terlambat'],
            datasets: [{
                data: [{{ $tepatWaktuCount }}, {{ $terlambatCount }}],
                backgroundColor: [colors.emerald, colors.red],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' } }
        }
    });

    // 4. MEMBER CHART (BAR HORIZONTAL)
    new Chart(document.getElementById('memberChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($topAnggota->pluck('anggota.nama')) !!},
            datasets: [{
                label: 'Total Pinjaman',
                data: {!! json_encode($topAnggota->pluck('total')) !!},
                backgroundColor: colors.amber,
                borderRadius: 4,
                barPercentage: 0.6
            }]
        },
        options: {
            indexAxis: 'y', // Horizontal
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { beginAtZero: true, grid: { color: '#334155' }, ticks: { color: '#94a3b8' } },
                y: { grid: { display: false }, ticks: { color: '#94a3b8' } }
            }
        }
    });
</script>
@endpush
@endsection