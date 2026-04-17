@extends('layouts.kepala')

@section('title', 'Statistik & Analitik — Perpustakaan Digital')
@section('page-title', 'Statistik & Analitik')
@section('page-subtitle', 'Visualisasi data mendalam untuk pengambilan keputusan strategis')

@section('content')
<div class="space-y-6 animate-fade-in-down">

    {{-- ══ HEADER & FILTER PERIODE ══ --}}
    <div class="bg-[#1e293b] rounded-3xl border border-white/5 shadow-xl p-6 flex flex-col md:flex-row justify-between items-center gap-6">
        <div>
            <h3 class="text-sm font-bold text-white uppercase tracking-wider">Metrik Performa Sistem</h3>
            <p class="text-[10px] text-slate-500 mt-0.5">Menganalisis tren dari <span class="text-indigo-400 font-bold">{{ $periode }}</span> terakhir</p>
        </div>
        
        <form action="{{ route('kepala.statistik') }}" method="GET" class="flex items-center gap-3">
            <div class="relative">
                <i class="fas fa-clock absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-[10px]"></i>
                <select name="periode" onchange="this.form.submit()" class="bg-slate-900/60 border border-white/8 rounded-xl pl-10 pr-10 py-2.5 text-slate-300 text-xs focus:outline-none focus:border-indigo-500/50 appearance-none cursor-pointer">
                    <option value="1month" {{ $periode == '1month' ? 'selected' : '' }}>1 Bulan Terakhir</option>
                    <option value="3months" {{ $periode == '3months' ? 'selected' : '' }}>3 Bulan Terakhir</option>
                    <option value="6months" {{ $periode == '6months' ? 'selected' : '' }}>6 Bulan Terakhir</option>
                    <option value="1year" {{ $periode == '1year' ? 'selected' : '' }}>1 Tahun Terakhir</option>
                </select>
                <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-600 pointer-events-none text-[8px]"></i>
            </div>
            <button type="submit" class="w-10 h-10 flex items-center justify-center bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl shadow-lg shadow-indigo-500/20 transition group">
                <i class="fas fa-sync-alt text-sm group-hover:rotate-180 transition-transform duration-500"></i>
            </button>
        </form>
    </div>

    {{-- ══ LINE CHART: TREN HARIAN ══ --}}
    <div class="bg-[#1e293b] rounded-3xl border border-white/5 shadow-xl p-8 relative overflow-hidden group">
        <div class="absolute -right-20 -top-20 w-80 h-80 bg-blue-500/5 rounded-full blur-3xl group-hover:bg-blue-500/10 transition-all duration-1000"></div>
        
        <div class="relative z-10">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-8 flex items-center gap-2">
                <i class="fas fa-wave-square text-blue-400"></i> Tren Sirkulasi Harian
            </h3>
            <div class="h-80 w-full relative">
                <canvas id="trendChart"></canvas>
            </div>
        </div>
    </div>

    {{-- ══ GRID CHART: KATEGORI & KEPATUHAN ══ --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Doughnut: Kategori --}}
        <div class="bg-[#1e293b] rounded-3xl border border-white/5 shadow-xl p-8">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-8 flex items-center gap-2">
                <i class="fas fa-shapes text-purple-400"></i> Minat Kategori
            </h3>
            <div class="relative h-64 flex justify-center">
                <canvas id="categoryChart"></canvas>
            </div>
            <div class="mt-8 grid grid-cols-2 gap-3">
                @foreach($kategoriData as $kat)
                <div class="flex items-center justify-between bg-white/3 p-2.5 rounded-xl border border-white/5">
                    <span class="text-[10px] font-bold text-slate-400 truncate w-32 uppercase tracking-tighter">{{ $kat->nama_kategori }}</span>
                    <span class="text-xs font-extrabold text-white">{{ $kat->total }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Pie: Kepatuhan --}}
        <div class="bg-[#1e293b] rounded-3xl border border-white/5 shadow-xl p-8 flex flex-col">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-8 flex items-center gap-2">
                <i class="fas fa-clock-rotate-left text-emerald-400"></i> Rasio Kepatuhan Balik
            </h3>
            <div class="relative h-64 flex-1 flex justify-center">
                <canvas id="complianceChart"></canvas>
            </div>
            <div class="mt-8 bg-slate-900/40 p-4 rounded-2xl border border-white/5 flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Akurasi Pengembalian</p>
                    <p class="text-2xl font-extrabold text-emerald-400">{{ $totalSelesai > 0 ? round(($tepatWaktuCount/$totalSelesai)*100, 1) : 0 }}%</p>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Total Selesai</p>
                    <p class="text-xl font-extrabold text-white">{{ number_format($totalSelesai) }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ HORIZONTAL BAR: TOP USERS ══ --}}
    <div class="bg-[#1e293b] rounded-3xl border border-white/5 shadow-xl p-8">
        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-8 flex items-center gap-2">
            <i class="fas fa-trophy text-amber-400"></i> Top 10 Elite Members
        </h3>
        <div class="relative h-96 w-full">
            <canvas id="memberChart"></canvas>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    const colors = {
        blue: '#3b82f6',
        emerald: '#10b981',
        purple: '#a855f7',
        amber: '#f59e0b',
        red: '#ef4444',
        slate: '#64748b'
    };

    const ctxTrend = document.getElementById('trendChart').getContext('2d');
    const grad1 = ctxTrend.createLinearGradient(0, 0, 0, 300); grad1.addColorStop(0, '#3b82f622'); grad1.addColorStop(1, '#3b82f600');
    const grad2 = ctxTrend.createLinearGradient(0, 0, 0, 300); grad2.addColorStop(0, '#10b98122'); grad2.addColorStop(1, '#10b98100');

    new Chart(ctxTrend, {
        type: 'line',
        data: {
            labels: {!! json_encode($labelsTren) !!},
            datasets: [
                {
                    label: 'Dipinjam',
                    data: {!! json_encode($dataPinjam) !!},
                    borderColor: colors.blue,
                    borderWidth: 2,
                    backgroundColor: grad1,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 0,
                    pointHoverRadius: 5
                },
                {
                    label: 'Dikembalikan',
                    data: {!! json_encode($dataKembali) !!},
                    borderColor: colors.emerald,
                    borderWidth: 2,
                    backgroundColor: grad2,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 0,
                    pointHoverRadius: 5
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(255,255,255,0.03)' }, ticks: { color: '#475569', font: { size: 10 } } },
                x: { grid: { display: false }, ticks: { color: '#475569', font: { size: 10 }, maxRotation: 0 } }
            }
        }
    });

    new Chart(document.getElementById('categoryChart'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($kategoriData->pluck('nama_kategori')) !!},
            datasets: [{
                data: {!! json_encode($kategoriData->pluck('total')) !!},
                backgroundColor: [colors.blue, colors.purple, colors.amber, colors.emerald, colors.red],
                borderWidth: 0,
                hoverOffset: 12
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '80%',
            plugins: { legend: { display: false } }
        }
    });

    new Chart(document.getElementById('complianceChart'), {
        type: 'pie',
        data: {
            labels: ['Tepat Waktu', 'Terlambat'],
            datasets: [{
                data: [{{ $tepatWaktuCount }}, {{ $terlambatCount }}],
                backgroundColor: [colors.emerald, colors.red],
                borderWidth: 0,
                hoverOffset: 15
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } }
        }
    });

    new Chart(document.getElementById('memberChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($topAnggota->pluck('anggota.nama')) !!},
            datasets: [{
                label: 'Total Pinjaman',
                data: {!! json_encode($topAnggota->pluck('total')) !!},
                backgroundColor: colors.amber,
                borderRadius: 8,
                barPercentage: 0.5
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { color: 'rgba(255,255,255,0.03)' }, ticks: { color: '#475569', font: { size: 10 } } },
                y: { grid: { display: false }, ticks: { color: '#94a3b8', font: { size: 11, weight: 'bold' } } }
            }
        }
    });
</script>
@endpush