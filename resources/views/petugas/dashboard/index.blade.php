@extends('layouts.petugas')

@section('title', 'Dashboard Petugas')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Card 1: Total Buku -->
        <div class="bg-[#1e293b] rounded-2xl p-6 border border-slate-700/50 shadow-xl relative overflow-hidden group hover:border-indigo-500/30 transition">
            <div class="absolute right-0 top-0 w-24 h-24 bg-indigo-500/10 rounded-full blur-2xl -mr-10 -mt-10"></div>
            <div class="relative z-10">
                <p class="text-slate-400 text-sm font-medium mb-1">Total Koleksi</p>
                <h3 class="text-3xl font-bold text-white">{{ number_format($totalJudul) }} <span class="text-lg text-slate-500 font-normal">Judul</span></h3>
                <p class="text-xs text-slate-500 mt-1">{{ number_format($totalEksemplar) }} Eksemplar • {{ $stokTersedia }} Tersedia</p>
            </div>
            <div class="w-12 h-12 bg-indigo-500/20 rounded-xl flex items-center justify-center text-indigo-400 absolute bottom-4 right-4 group-hover:scale-110 transition">
                <i class="fas fa-book text-xl"></i>
            </div>
        </div>

        <!-- Card 2: Anggota Aktif -->
        <div class="bg-[#1e293b] rounded-2xl p-6 border border-purple-500/20 shadow-xl relative overflow-hidden group hover:border-purple-500/30 transition">
            <div class="absolute right-0 top-0 w-24 h-24 bg-purple-500/10 rounded-full blur-2xl -mr-10 -mt-10"></div>
            <div class="relative z-10">
                <p class="text-slate-400 text-sm font-medium mb-1">Anggota Aktif</p>
                <h3 class="text-3xl font-bold text-purple-400">{{ number_format($anggotaAktif) }}</h3>
                <p class="text-xs text-emerald-400 mt-1"><i class="fas fa-plus-circle mr-1"></i> +{{ $anggotaBaruBulanIni }} bulan ini</p>
            </div>
            <div class="w-12 h-12 bg-purple-500/20 rounded-xl flex items-center justify-center text-purple-400 absolute bottom-4 right-4 group-hover:scale-110 transition">
                <i class="fas fa-users text-xl"></i>
            </div>
        </div>

        <!-- Card 3: Sedang Dipinjam -->
        <div class="bg-[#1e293b] rounded-2xl p-6 border border-amber-500/20 shadow-xl relative overflow-hidden group hover:border-amber-500/30 transition">
            <div class="absolute right-0 top-0 w-24 h-24 bg-amber-500/10 rounded-full blur-2xl -mr-10 -mt-10"></div>
            <div class="relative z-10">
                <p class="text-slate-400 text-sm font-medium mb-1">Sedang Dipinjam</p>
                <h3 class="text-3xl font-bold text-amber-400">{{ number_format($peminjamanAktif) }}</h3>
                <p class="text-xs text-red-400 mt-1"><i class="fas fa-exclamation-triangle mr-1"></i> {{ $terlambat }} Terlambat</p>
            </div>
            <div class="w-12 h-12 bg-amber-500/20 rounded-xl flex items-center justify-center text-amber-400 absolute bottom-4 right-4 group-hover:scale-110 transition">
                <i class="fas fa-hand-holding-book text-xl"></i>
            </div>
        </div>

        <!-- Card 4: Pendapatan Denda -->
        <div class="bg-[#1e293b] rounded-2xl p-6 border border-emerald-500/20 shadow-xl relative overflow-hidden group hover:border-emerald-500/30 transition">
            <div class="absolute right-0 top-0 w-24 h-24 bg-emerald-500/10 rounded-full blur-2xl -mr-10 -mt-10"></div>
            <div class="relative z-10">
                <p class="text-slate-400 text-sm font-medium mb-1">Pendapatan Denda</p>
                <h3 class="text-2xl font-bold text-emerald-400">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</h3>
                <p class="text-xs text-slate-500 mt-1">Piutang: Rp {{ number_format($dendaBelumLunas, 0, ',', '.') }}</p>
            </div>
            <div class="w-12 h-12 bg-emerald-500/20 rounded-xl flex items-center justify-center text-emerald-400 absolute bottom-4 right-4 group-hover:scale-110 transition">
                <i class="fas fa-coins text-xl"></i>
            </div>
        </div>
    </div>

    <!-- 2. GRAFIK & AKSI CEPAT -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Grafik Aktivitas (Lebar 2/3) -->
        <div class="lg:col-span-2 bg-[#1e293b] rounded-2xl border border-slate-700/50 p-6 shadow-xl">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="text-lg font-bold text-white">Aktivitas 7 Hari Terakhir</h3>
                    <p class="text-sm text-slate-400">Peminjaman vs Pengembalian</p>
                </div>
            </div>
            <div class="relative h-64 w-full">
                <canvas id="activityChart"></canvas>
            </div>
        </div>

        <!-- Panel Kanan: Alert & Info -->
        <div class="space-y-6">
            <!-- Alert Jatuh Tempo Hari Ini -->
            @if($jatuhTempoHariIni > 0)
            <div class="bg-amber-500/10 border border-amber-500/20 rounded-2xl p-5 flex items-start gap-4">
                <div class="w-10 h-10 bg-amber-500/20 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-bell text-amber-500 text-lg"></i>
                </div>
                <div class="flex-1">
                    <h4 class="font-bold text-amber-400 mb-1">Jatuh Tempo Hari Ini</h4>
                    <p class="text-sm text-amber-200/70 mb-3">
                        Terdapat <strong class="text-white">{{ $jatuhTempoHariIni }}</strong> buku yang harus dikembalikan hari ini.
                    </p>
                    <a href="{{ route('petugas.pengembalian.index') }}" class="text-xs font-semibold text-amber-400 hover:text-amber-300 underline">
                        Cek Daftar Pengembalian <i class="fas fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>
            @endif

            <!-- Alert Terlambat -->
            @if($terlambat > 0)
            <div class="bg-red-500/10 border border-red-500/20 rounded-2xl p-5 flex items-start gap-4">
                <div class="w-10 h-10 bg-red-500/20 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-500 text-lg"></i>
                </div>
                <div class="flex-1">
                    <h4 class="font-bold text-red-400 mb-1">Keterlambatan</h4>
                    <p class="text-sm text-red-200/70 mb-3">
                        <strong class="text-white">{{ $terlambat }}</strong> anggota belum mengembalikan buku melewati batas waktu.
                    </p>
                    <a href="{{ route('petugas.denda.index') }}" class="text-xs font-semibold text-red-400 hover:text-red-300 underline">
                        Kelola Denda <i class="fas fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>
            @endif

            <!-- Aksi Cepat -->
            <div class="bg-[#1e293b] rounded-2xl border border-slate-700/50 p-6 shadow-xl">
                <h3 class="text-lg font-bold text-white mb-4">Aksi Cepat</h3>
                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('petugas.peminjaman.create') }}" class="p-3 rounded-xl bg-indigo-600/10 border border-indigo-500/20 hover:bg-indigo-600 hover:border-indigo-500 transition text-center group">
                        <i class="fas fa-plus text-indigo-400 group-hover:text-white text-xl mb-2 block"></i>
                        <span class="text-xs font-semibold text-indigo-300 group-hover:text-white">Peminjaman</span>
                    </a>
                    <a href="{{ route('petugas.pengembalian.index') }}" class="p-3 rounded-xl bg-emerald-600/10 border border-emerald-500/20 hover:bg-emerald-600 hover:border-emerald-500 transition text-center group">
                        <i class="fas fa-undo text-emerald-400 group-hover:text-white text-xl mb-2 block"></i>
                        <span class="text-xs font-semibold text-emerald-300 group-hover:text-white">Pengembalian</span>
                    </a>
                    <a href="{{ route('petugas.buku.create') }}" class="p-3 rounded-xl bg-blue-600/10 border border-blue-500/20 hover:bg-blue-600 hover:border-blue-500 transition text-center group">
                        <i class="fas fa-book text-blue-400 group-hover:text-white text-xl mb-2 block"></i>
                        <span class="text-xs font-semibold text-blue-300 group-hover:text-white">Tambah Buku</span>
                    </a>
                    <a href="{{ route('petugas.anggota.create') }}" class="p-3 rounded-xl bg-purple-600/10 border border-purple-500/20 hover:bg-purple-600 hover:border-purple-500 transition text-center group">
                        <i class="fas fa-user-plus text-purple-400 group-hover:text-white text-xl mb-2 block"></i>
                        <span class="text-xs font-semibold text-purple-300 group-hover:text-white">Anggota Baru</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. TABEL TRANSAKSI TERBARU -->
    <div class="bg-[#1e293b] rounded-2xl border border-slate-700/50 shadow-xl overflow-hidden">
        <div class="p-6 border-b border-slate-700/50 flex justify-between items-center">
            <div>
                <h3 class="text-lg font-bold text-white">Transaksi Terbaru</h3>
                <p class="text-sm text-slate-400">5 aktivitas terakhir di perpustakaan</p>
            </div>
            <a href="{{ route('petugas.peminjaman.index') }}" class="text-indigo-400 hover:text-indigo-300 text-sm font-medium">Lihat Semua</a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-800/50 text-xs uppercase tracking-wider text-slate-400">
                        <th class="px-6 py-4 font-semibold">Waktu</th>
                        <th class="px-6 py-4 font-semibold">Anggota</th>
                        <th class="px-6 py-4 font-semibold">Buku</th>
                        <th class="px-6 py-4 font-semibold text-center">Status</th>
                        <th class="px-6 py-4 font-semibold text-right">Tanggal Kembali</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/50 text-sm">
                    @forelse($transaksiTerbaru as $t)
                    <tr class="hover:bg-slate-800/30 transition">
                        <td class="px-6 py-4 text-slate-400 text-xs">
                            {{ $t->created_at->diffForHumans() }}<br>
                            <span class="text-[10px]">{{ $t->created_at->format('d M Y, H:i') }}</span>
                        </td>
                        <td class="px-6 py-4 font-medium text-white">
                            {{ $t->anggota->nama ?? 'N/A' }}
                            <div class="text-xs text-slate-500">{{ $t->anggota->kelas ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 text-slate-300">
                            {{ $t->buku->judul ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($t->status_peminjaman == 'dipinjam')
                                <span class="px-2 py-1 rounded text-xs bg-amber-500/10 text-amber-400 border border-amber-500/20">Dipinjam</span>
                            @elseif($t->status_peminjaman == 'dikembalikan')
                                <span class="px-2 py-1 rounded text-xs bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">Kembali</span>
                            @else
                                <span class="px-2 py-1 rounded text-xs bg-red-500/10 text-red-400 border border-red-500/20">{{ ucfirst($t->status_peminjaman) }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right text-slate-400 text-xs">
                            {{ $t->tanggal_kembali_rencana->format('d M Y') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-500">Belum ada transaksi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Script Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('activityChart').getContext('2d');
    
    // Data dari Controller
    const labels = @json($grafikLabel);
    const dataPinjam = @json($grafikDataPinjam);
    const dataKembali = @json($grafikDataKembali);

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Peminjaman',
                    data: dataPinjam,
                    backgroundColor: '#6366f1', // Indigo
                    borderRadius: 4,
                },
                {
                    label: 'Pengembalian',
                    data: dataKembali,
                    backgroundColor: '#10b981', // Emerald
                    borderRadius: 4,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'top', labels: { color: '#94a3b8' } }
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
</script>
@endsection