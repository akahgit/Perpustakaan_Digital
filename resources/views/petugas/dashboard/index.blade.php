@extends('layouts.petugas')

@section('title', 'Dashboard Petugas — Perpustakan Digital')
@section('page-title', 'Dashboard')
@section('page-subtitle')
    Selamat datang kembali, {{ auth()->user()->name }}
@endsection

@section('content')
<div class="space-y-6 animate-fade-in-down">

    {{-- ══════════════════════════════════════════════
         ROW 1 — STAT CARDS
         ══════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 lg:gap-5">

        {{-- Card: Total Koleksi --}}
        <div class="relative bg-[#1e293b] rounded-2xl p-5 border border-white/5 shadow-xl overflow-hidden group hover:border-indigo-500/30 hover:shadow-indigo-500/10 transition-all duration-300 cursor-default">
            {{-- Glow orb --}}
            <div class="absolute -top-6 -right-6 w-28 h-28 bg-indigo-500/10 rounded-full blur-2xl pointer-events-none group-hover:bg-indigo-500/20 transition-colors"></div>

            <div class="relative z-10 flex items-start justify-between">
                <div class="flex-1">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-3">Total Koleksi</p>
                    <div class="flex items-end gap-2 mb-1.5">
                        <span class="text-3xl font-extrabold text-white tracking-tight leading-none">{{ number_format($totalJudul) }}</span>
                        <span class="text-sm text-slate-500 font-medium mb-0.5">Judul</span>
                    </div>
                    <div class="flex items-center gap-1.5 text-xs">
                        <span class="text-slate-500">{{ number_format($totalEksemplar) }} eks •</span>
                        <span class="text-emerald-400 font-semibold">{{ $stokTersedia }} tersedia</span>
                    </div>
                </div>
                {{-- Icon --}}
                <div class="w-11 h-11 bg-indigo-500/15 border border-indigo-500/20 rounded-xl flex items-center justify-center shadow-lg flex-shrink-0">
                    <i class="fas fa-book text-indigo-400 text-base"></i>
                </div>
            </div>

            {{-- Trend indicator --}}
            <div class="relative z-10 mt-4 pt-3 border-t border-white/5 flex items-center gap-1.5">
                <i class="fas fa-arrow-trend-up text-emerald-400 text-xs"></i>
                <span class="text-xs text-slate-500">Koleksi terus bertambah</span>
            </div>
        </div>

        {{-- Card: Anggota Aktif --}}
        <div class="relative bg-[#1e293b] rounded-2xl p-5 border border-white/5 shadow-xl overflow-hidden group hover:border-violet-500/30 hover:shadow-violet-500/10 transition-all duration-300 cursor-default">
            <div class="absolute -top-6 -right-6 w-28 h-28 bg-violet-500/10 rounded-full blur-2xl pointer-events-none group-hover:bg-violet-500/20 transition-colors"></div>

            <div class="relative z-10 flex items-start justify-between">
                <div class="flex-1">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-3">Anggota Aktif</p>
                    <div class="flex items-end gap-2 mb-1.5">
                        <span class="text-3xl font-extrabold text-white tracking-tight leading-none">{{ number_format($anggotaAktif) }}</span>
                        <span class="text-sm text-slate-500 font-medium mb-0.5">Orang</span>
                    </div>
                    <div class="flex items-center gap-1 text-xs">
                        @if($anggotaBaruBulanIni > 0)
                            <i class="fas fa-circle-plus text-emerald-400 text-[10px]"></i>
                            <span class="text-emerald-400 font-semibold">+{{ $anggotaBaruBulanIni }}</span>
                            <span class="text-slate-500">bulan ini</span>
                        @else
                            <span class="text-slate-500">Tidak ada tambahan bulan ini</span>
                        @endif
                    </div>
                </div>
                <div class="w-11 h-11 bg-violet-500/15 border border-violet-500/20 rounded-xl flex items-center justify-center shadow-lg flex-shrink-0">
                    <i class="fas fa-users text-violet-400 text-base"></i>
                </div>
            </div>

            <div class="relative z-10 mt-4 pt-3 border-t border-white/5 flex items-center gap-1.5">
                @if($anggotaBaruBulanIni > 0)
                    <i class="fas fa-arrow-trend-up text-emerald-400 text-xs"></i>
                    <span class="text-xs text-emerald-400 font-medium">+{{ $anggotaBaruBulanIni }} anggota baru</span>
                @else
                    <i class="fas fa-minus text-slate-600 text-xs"></i>
                    <span class="text-xs text-slate-500">Stabil bulan ini</span>
                @endif
            </div>
        </div>

        {{-- Card: Sedang Dipinjam --}}
        <div class="relative bg-[#1e293b] rounded-2xl p-5 border border-white/5 shadow-xl overflow-hidden group hover:border-amber-500/30 hover:shadow-amber-500/10 transition-all duration-300 cursor-default">
            <div class="absolute -top-6 -right-6 w-28 h-28 bg-amber-500/10 rounded-full blur-2xl pointer-events-none group-hover:bg-amber-500/20 transition-colors"></div>

            <div class="relative z-10 flex items-start justify-between">
                <div class="flex-1">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-3">Sedang Dipinjam</p>
                    <div class="flex items-end gap-2 mb-1.5">
                        <span class="text-3xl font-extrabold text-white tracking-tight leading-none">{{ number_format($peminjamanAktif) }}</span>
                        <span class="text-sm text-slate-500 font-medium mb-0.5">Buku</span>
                    </div>
                    <div class="flex items-center gap-1 text-xs">
                        @if($terlambat > 0)
                            <i class="fas fa-triangle-exclamation text-rose-400 text-[10px]"></i>
                            <span class="text-rose-400 font-semibold">{{ $terlambat }}</span>
                            <span class="text-slate-500">terlambat</span>
                        @else
                            <i class="fas fa-check text-emerald-400 text-[10px]"></i>
                            <span class="text-emerald-400 font-semibold">Semua tepat waktu</span>
                        @endif
                    </div>
                </div>
                <div class="w-11 h-11 bg-amber-500/15 border border-amber-500/20 rounded-xl flex items-center justify-center shadow-lg flex-shrink-0">
                    <i class="fas fa-hand-holding-book text-amber-400 text-base"></i>
                </div>
            </div>

            <div class="relative z-10 mt-4 pt-3 border-t border-white/5 flex items-center gap-1.5">
                @if($terlambat > 0)
                    <i class="fas fa-arrow-trend-down text-rose-400 text-xs"></i>
                    <span class="text-xs text-rose-400 font-medium">{{ $terlambat }} terlambat kembali</span>
                @else
                    <i class="fas fa-circle-check text-emerald-400 text-xs"></i>
                    <span class="text-xs text-slate-500">Tidak ada keterlambatan</span>
                @endif
            </div>
        </div>

        {{-- Card: Pendapatan Denda --}}
        <div class="relative bg-[#1e293b] rounded-2xl p-5 border border-white/5 shadow-xl overflow-hidden group hover:border-emerald-500/30 hover:shadow-emerald-500/10 transition-all duration-300 cursor-default">
            <div class="absolute -top-6 -right-6 w-28 h-28 bg-emerald-500/10 rounded-full blur-2xl pointer-events-none group-hover:bg-emerald-500/20 transition-colors"></div>

            <div class="relative z-10 flex items-start justify-between">
                <div class="flex-1">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-3">Pendapatan Denda</p>
                    <div class="flex items-end gap-1 mb-1.5">
                        <span class="text-lg font-extrabold text-white tracking-tight leading-none">Rp</span>
                        <span class="text-2xl font-extrabold text-white tracking-tight leading-none">{{ number_format($pendapatanBulanIni, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex items-center gap-1 text-xs">
                        <span class="text-slate-500">Piutang:</span>
                        <span class="text-amber-400 font-semibold">Rp {{ number_format($dendaBelumLunas, 0, ',', '.') }}</span>
                    </div>
                </div>
                <div class="w-11 h-11 bg-emerald-500/15 border border-emerald-500/20 rounded-xl flex items-center justify-center shadow-lg flex-shrink-0">
                    <i class="fas fa-coins text-emerald-400 text-base"></i>
                </div>
            </div>

            <div class="relative z-10 mt-4 pt-3 border-t border-white/5 flex items-center gap-1.5">
                @if($pendapatanBulanIni > 0)
                    <i class="fas fa-arrow-trend-up text-emerald-400 text-xs"></i>
                    <span class="text-xs text-emerald-400 font-medium">Ada pemasukan bulan ini</span>
                @else
                    <i class="fas fa-minus text-slate-600 text-xs"></i>
                    <span class="text-xs text-slate-500">Belum ada denda bulan ini</span>
                @endif
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════
         ROW 2 — CHART + ALERTS + QUICK ACTIONS
         ══════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Chart: Aktivitas 7 Hari Terakhir (2/3 lebar) --}}
        <div class="lg:col-span-2 bg-[#1e293b] rounded-2xl border border-white/5 shadow-xl overflow-hidden">
            <div class="px-5 py-4 border-b border-white/5 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-white tracking-tight">Aktivitas 7 Hari Terakhir</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Peminjaman vs Pengembalian</p>
                </div>
                <div class="flex items-center gap-4 text-xs text-slate-500">
                    <span class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-sm bg-indigo-500 flex-shrink-0"></span>Pinjam
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-sm bg-emerald-500 flex-shrink-0"></span>Kembali
                    </span>
                </div>
            </div>
            <div class="p-5">
                <div class="relative h-56 sm:h-64">
                    <canvas id="activityChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Right Panel: Alerts + Quick Actions --}}
        <div class="flex flex-col gap-4">

            {{-- Alert: Jatuh Tempo Hari Ini --}}
            @if($jatuhTempoHariIni > 0)
            <div class="bg-amber-500/8 border border-amber-500/20 rounded-2xl p-4 flex items-start gap-3.5">
                <div class="w-9 h-9 bg-amber-500/20 rounded-xl flex items-center justify-center flex-shrink-0 mt-0.5">
                    <i class="fas fa-bell text-amber-400 text-sm"></i>
                </div>
                <div class="flex-1">
                    <h4 class="text-sm font-bold text-amber-300 mb-1">Jatuh Tempo Hari Ini</h4>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        <strong class="text-white">{{ $jatuhTempoHariIni }}</strong> buku wajib dikembalikan hari ini.
                    </p>
                    <a href="{{ route('petugas.pengembalian.index') }}"
                       class="inline-flex items-center gap-1 mt-2 text-xs font-semibold text-amber-400 hover:text-amber-300 transition">
                        Cek Pengembalian <i class="fas fa-arrow-right text-[9px]"></i>
                    </a>
                </div>
            </div>
            @endif

            {{-- Alert: Terlambat --}}
            @if($terlambat > 0)
            <div class="bg-rose-500/8 border border-rose-500/20 rounded-2xl p-4 flex items-start gap-3.5">
                <div class="w-9 h-9 bg-rose-500/20 rounded-xl flex items-center justify-center flex-shrink-0 mt-0.5">
                    <i class="fas fa-circle-exclamation text-rose-400 text-sm"></i>
                </div>
                <div class="flex-1">
                    <h4 class="text-sm font-bold text-rose-300 mb-1">Keterlambatan</h4>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        <strong class="text-white">{{ $terlambat }}</strong> anggota melewati batas waktu pengembalian.
                    </p>
                    <a href="{{ route('petugas.denda.index') }}"
                       class="inline-flex items-center gap-1 mt-2 text-xs font-semibold text-rose-400 hover:text-rose-300 transition">
                        Kelola Denda <i class="fas fa-arrow-right text-[9px]"></i>
                    </a>
                </div>
            </div>
            @endif

            {{-- No alerts --}}
            @if($jatuhTempoHariIni === 0 && $terlambat === 0)
            <div class="bg-emerald-500/8 border border-emerald-500/20 rounded-2xl p-4 flex items-center gap-3.5">
                <div class="w-9 h-9 bg-emerald-500/20 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-circle-check text-emerald-400 text-sm"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-emerald-300">Semuanya Lancar!</h4>
                    <p class="text-xs text-slate-500">Tidak ada keterlambatan hari ini.</p>
                </div>
            </div>
            @endif

            {{-- Aksi Cepat --}}
            <div class="bg-[#1e293b] rounded-2xl border border-white/5 shadow-xl p-4 flex-1">
                <h3 class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-3">Aksi Cepat</h3>
                <div class="grid grid-cols-2 gap-2.5">
                    <a href="{{ route('petugas.peminjaman.index', ['status' => 'menunggu_konfirmasi']) }}"
                       class="group p-3.5 rounded-xl bg-indigo-600/10 border border-indigo-500/15 hover:bg-indigo-600 hover:border-indigo-500 transition-all duration-200 text-center">
                        <div class="w-8 h-8 bg-indigo-500/20 group-hover:bg-white/20 rounded-lg flex items-center justify-center mx-auto mb-2 transition-colors">
                            <i class="fas fa-hourglass-half text-indigo-400 group-hover:text-white text-sm transition-colors"></i>
                        </div>
                        <span class="text-xs font-semibold text-indigo-300 group-hover:text-white transition-colors">Konfirmasi Pinjam</span>
                    </a>

                    <a href="{{ route('petugas.pengembalian.index') }}"
                       class="group p-3.5 rounded-xl bg-emerald-600/10 border border-emerald-500/15 hover:bg-emerald-600 hover:border-emerald-500 transition-all duration-200 text-center">
                        <div class="w-8 h-8 bg-emerald-500/20 group-hover:bg-white/20 rounded-lg flex items-center justify-center mx-auto mb-2 transition-colors">
                            <i class="fas fa-rotate-left text-emerald-400 group-hover:text-white text-sm transition-colors"></i>
                        </div>
                        <span class="text-xs font-semibold text-emerald-300 group-hover:text-white transition-colors">Pengembalian</span>
                    </a>

                    <a href="{{ route('petugas.buku.create') }}"
                       class="group p-3.5 rounded-xl bg-blue-600/10 border border-blue-500/15 hover:bg-blue-600 hover:border-blue-500 transition-all duration-200 text-center">
                        <div class="w-8 h-8 bg-blue-500/20 group-hover:bg-white/20 rounded-lg flex items-center justify-center mx-auto mb-2 transition-colors">
                            <i class="fas fa-book text-blue-400 group-hover:text-white text-sm transition-colors"></i>
                        </div>
                        <span class="text-xs font-semibold text-blue-300 group-hover:text-white transition-colors">Tambah Buku</span>
                    </a>

                    <a href="{{ route('petugas.anggota.index') }}"
                       class="group p-3.5 rounded-xl bg-violet-600/10 border border-violet-500/15 hover:bg-violet-600 hover:border-violet-500 transition-all duration-200 text-center">
                        <div class="w-8 h-8 bg-violet-500/20 group-hover:bg-white/20 rounded-lg flex items-center justify-center mx-auto mb-2 transition-colors">
                            <i class="fas fa-users text-violet-400 group-hover:text-white text-sm transition-colors"></i>
                        </div>
                        <span class="text-xs font-semibold text-violet-300 group-hover:text-white transition-colors">Data Anggota</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════
         ROW 3 — TABEL TRANSAKSI TERBARU
         ══════════════════════════════════════════════ --}}
    <div class="bg-[#1e293b] rounded-2xl border border-white/5 shadow-xl overflow-hidden">

        {{-- Header tabel --}}
        <div class="px-5 py-4 border-b border-white/5 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-white tracking-tight">Transaksi Terbaru</h3>
                <p class="text-xs text-slate-500 mt-0.5">5 aktivitas peminjaman terakhir</p>
            </div>
            <a href="{{ route('petugas.peminjaman.index') }}"
               class="inline-flex items-center gap-1.5 text-xs font-semibold text-indigo-400 hover:text-indigo-300 transition">
                Lihat Semua <i class="fas fa-arrow-right text-[9px]"></i>
            </a>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-white/3 border-b border-white/5">
                        <th class="px-5 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-[0.12em]">Waktu</th>
                        <th class="px-5 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-[0.12em]">Anggota</th>
                        <th class="px-5 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-[0.12em]">Buku</th>
                        <th class="px-5 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-[0.12em] text-center">Status</th>
                        <th class="px-5 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-[0.12em] text-right">Kembali</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/3">
                    @forelse($transaksiTerbaru as $t)
                    <tr class="table-row-hover transition-colors duration-150 group">
                        {{-- Waktu --}}
                        <td class="px-5 py-3.5">
                            <div class="text-xs text-slate-400 font-medium">{{ $t->created_at->diffForHumans() }}</div>
                            <div class="text-[10px] text-slate-600 mt-0.5 tabular-nums">{{ $t->created_at->format('d M Y') }}</div>
                        </td>

                        {{-- Anggota --}}
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-2.5">
                                {{-- Avatar --}}
                                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center text-white font-bold text-xs flex-shrink-0 shadow-md">
                                    {{ strtoupper(substr($t->anggota->nama ?? 'NA', 0, 2)) }}
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-slate-200 group-hover:text-white transition-colors leading-tight">
                                        {{ $t->anggota->nama ?? 'N/A' }}
                                    </div>
                                    <div class="text-[10px] text-slate-600 mt-0.5">{{ $t->anggota->kelas ?? '—' }}</div>
                                </div>
                            </div>
                        </td>

                        {{-- Buku --}}
                        <td class="px-5 py-3.5 max-w-[200px]">
                            <div class="text-sm text-slate-300 truncate">{{ $t->buku->judul ?? 'N/A' }}</div>
                        </td>

                        {{-- Status Badge --}}
                        <td class="px-5 py-3.5 text-center">
                            @if($t->status_peminjaman === 'dipinjam')
                                <span class="badge-warning inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold">
                                    <i class="fas fa-circle text-[5px]"></i> Dipinjam
                                </span>
                            @elseif($t->status_peminjaman === 'dikembalikan')
                                <span class="badge-success inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold">
                                    <i class="fas fa-circle text-[5px]"></i> Kembali
                                </span>
                            @elseif($t->status_peminjaman === 'menunggu_konfirmasi')
                                <span class="badge-info inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold">
                                    <i class="fas fa-circle text-[5px]"></i> Menunggu
                                </span>
                            @else
                                <span class="badge-error inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold">
                                    <i class="fas fa-circle text-[5px]"></i> {{ ucfirst($t->status_peminjaman) }}
                                </span>
                            @endif
                        </td>

                        {{-- Tanggal Kembali --}}
                        <td class="px-5 py-3.5 text-right">
                            <div class="text-xs text-slate-400 tabular-nums">
                                {{ $t->tanggal_kembali_rencana->format('d M Y') }}
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-14 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-14 h-14 bg-white/3 rounded-2xl flex items-center justify-center mb-4">
                                    <i class="fas fa-inbox text-slate-600 text-2xl"></i>
                                </div>
                                <p class="text-sm font-medium text-slate-500">Belum ada transaksi.</p>
                                <a href="{{ route('petugas.peminjaman.index', ['status' => 'menunggu_konfirmasi']) }}"
                                   class="mt-2 text-xs text-indigo-400 hover:text-indigo-300 font-semibold transition">
                                    Cek pengajuan anggota →
                                </a>
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // ── Chart.js: Activity Chart ──
    (function() {
        const ctx = document.getElementById('activityChart');
        if (!ctx) return;

        const labels    = @json($grafikLabel);
        const pinjamData  = @json($grafikDataPinjam);
        const kembaliData = @json($grafikDataKembali);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels,
                datasets: [
                    {
                        label: 'Peminjaman',
                        data: pinjamData,
                        backgroundColor: 'rgba(99,102,241,0.8)',
                        hoverBackgroundColor: 'rgba(99,102,241,1)',
                        borderRadius: 6,
                        borderSkipped: false,
                        barPercentage: 0.6,
                        categoryPercentage: 0.7,
                    },
                    {
                        label: 'Pengembalian',
                        data: kembaliData,
                        backgroundColor: 'rgba(16,185,129,0.8)',
                        hoverBackgroundColor: 'rgba(16,185,129,1)',
                        borderRadius: 6,
                        borderSkipped: false,
                        barPercentage: 0.6,
                        categoryPercentage: 0.7,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        borderColor: 'rgba(255,255,255,0.08)',
                        borderWidth: 1,
                        titleColor: '#f1f5f9',
                        bodyColor:  '#94a3b8',
                        padding: 12,
                        titleFont: { size: 12, weight: 'bold' },
                        bodyFont:  { size: 12 },
                        callbacks: {
                            label: (ctx) => ` ${ctx.dataset.label}: ${ctx.parsed.y} transaksi`,
                        },
                        cornerRadius: 10,
                        caretSize: 5,
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        border: { display: false },
                        ticks: { color: '#64748b', font: { size: 11 } },
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(255,255,255,0.04)',
                            drawBorder: false,
                        },
                        border: { display: false, dash: [4, 4] },
                        ticks: {
                            color: '#64748b',
                            font: { size: 11 },
                            stepSize: 1,
                            precision: 0
                        },
                    }
                }
            }
        });
    })();
</script>
@endpush
