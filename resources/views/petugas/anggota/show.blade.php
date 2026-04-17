@extends('layouts.petugas')

@section('title', 'Detail Anggota — ' . $anggota->nama)
@section('page-title', 'Detail Anggota')
@section('page-subtitle', 'Kartu identitas dan riwayat anggota')

@section('content')
@php
    // Riwayat transaksi lengkap anggota
    $riwayats = \App\Models\Peminjaman::with('buku')
        ->where('id_anggota', $anggota->id)
        ->orderBy('created_at', 'desc')
        ->paginate(8);

    $terlambatCount = \App\Models\Peminjaman::where('id_anggota', $anggota->id)
        ->where('status_peminjaman', 'dipinjam')
        ->where('tanggal_kembali_rencana', '<', \Carbon\Carbon::today())
        ->count();
@endphp

<div class="space-y-6 animate-fade-in-down">

    {{-- Breadcrumb back link --}}
    <a href="{{ route('petugas.anggota.index') }}"
       class="inline-flex items-center gap-2 text-xs text-slate-500 hover:text-indigo-400 transition font-medium">
        <i class="fas fa-arrow-left text-[10px]"></i> Kembali ke Daftar Anggota
    </a>

    {{-- ══ KARTU IDENTITAS DIGITAL ══ --}}
    <div class="bg-[#1e293b] rounded-2xl border border-white/5 shadow-xl overflow-hidden">

        {{-- Header gradient --}}
        <div class="relative h-28 bg-gradient-to-br from-indigo-600 via-violet-700 to-slate-900 overflow-hidden">
            <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 30% 50%, #818cf8 0%, transparent 60%), radial-gradient(circle at 80% 20%, #c4b5fd 0%, transparent 50%)"></div>
            {{-- Edit & Back buttons --}}
            <div class="absolute top-4 right-4 flex gap-2">
                <a href="{{ route('petugas.anggota.edit', $anggota->id) }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white/15 hover:bg-white/25 backdrop-blur text-white text-xs font-semibold rounded-lg border border-white/20 transition">
                    <i class="fas fa-pen text-xs"></i> Edit
                </a>
            </div>
        </div>

        <div class="px-6 pb-6">
            {{-- Avatar overlapping header --}}
            <div class="flex flex-col sm:flex-row items-start sm:items-end gap-5 -mt-12 mb-6">
                <div class="w-24 h-24 rounded-2xl border-4 border-[#1e293b] bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center shadow-2xl shadow-indigo-500/30 flex-shrink-0">
                    <span class="text-3xl font-extrabold text-white">{{ strtoupper(substr($anggota->nama, 0, 2)) }}</span>
                </div>
                <div class="flex-1 min-w-0 mb-1">
                    <div class="flex flex-wrap items-center gap-2 mb-1">
                        <h1 class="text-xl font-extrabold text-white tracking-tight">{{ $anggota->nama }}</h1>
                        @if($anggota->status === 'aktif')
                            <span class="badge-success inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold">
                                <i class="fas fa-circle text-[4px]"></i> Aktif
                            </span>
                        @else
                            <span class="badge-error inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold">
                                <i class="fas fa-circle text-[4px]"></i> Non-Aktif
                            </span>
                        @endif
                    </div>
                    <p class="text-sm text-slate-400">{{ $anggota->kelas }} &bull; NIS: <span class="font-mono">{{ $anggota->nis_nisn }}</span></p>
                </div>
            </div>

            {{-- Info Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">

                {{-- Stat 1: Total Pinjam --}}
                <div class="bg-white/3 border border-white/5 rounded-xl p-4 flex items-center gap-4">
                    <div class="w-10 h-10 bg-indigo-500/15 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-book-open text-indigo-400"></i>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-500 uppercase font-bold tracking-wider">Total Pinjam</p>
                        <p class="text-2xl font-extrabold text-white">{{ $totalPinjam }}</p>
                    </div>
                </div>

                {{-- Stat 2: Sedang Dipinjam --}}
                <div class="bg-white/3 border border-white/5 rounded-xl p-4 flex items-center gap-4">
                    <div class="w-10 h-10 bg-amber-500/15 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-hand-holding-book text-amber-400"></i>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-500 uppercase font-bold tracking-wider">Sedang Dipinjam</p>
                        <p class="text-2xl font-extrabold {{ $sedangDipinjam > 0 ? 'text-amber-400' : 'text-white' }}">{{ $sedangDipinjam }}</p>
                    </div>
                </div>

                {{-- Stat 3: Denda Belum Lunas --}}
                <div class="bg-white/3 border border-white/5 rounded-xl p-4 flex items-center gap-4">
                    <div class="w-10 h-10 {{ $totalDenda > 0 ? 'bg-rose-500/15' : 'bg-emerald-500/15' }} rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-coins {{ $totalDenda > 0 ? 'text-rose-400' : 'text-emerald-400' }}"></i>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-500 uppercase font-bold tracking-wider">Denda Belum Lunas</p>
                        <p class="text-xl font-extrabold {{ $totalDenda > 0 ? 'text-rose-400' : 'text-emerald-400' }}">
                            Rp {{ number_format($totalDenda, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ DETAIL KONTAK & INFO ══ --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        {{-- Info Pribadi --}}
        <div class="bg-[#1e293b] rounded-2xl border border-white/5 shadow-xl p-5">
            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-4">Informasi Pribadi</h3>
            <div class="space-y-3">
                @php
                    $infos = [
                        ['icon' => 'fa-id-badge',   'label' => 'NIS / NISN',     'value' => $anggota->nis_nisn],
                        ['icon' => 'fa-school',      'label' => 'Kelas',          'value' => $anggota->kelas],
                        ['icon' => 'fa-venus-mars',  'label' => 'Jenis Kelamin',  'value' => $anggota->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan'],
                        ['icon' => 'fa-calendar',    'label' => 'Bergabung',      'value' => $anggota->tanggal_bergabung->format('d F Y')],
                    ];
                @endphp
                @foreach($infos as $info)
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-white/5 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class="fas {{ $info['icon'] }} text-xs text-slate-500"></i>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-600 uppercase font-bold tracking-wider">{{ $info['label'] }}</p>
                        <p class="text-sm text-slate-300 font-medium">{{ $info['value'] ?? '—' }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Info Kontak --}}
        <div class="bg-[#1e293b] rounded-2xl border border-white/5 shadow-xl p-5">
            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-4">Kontak & Akun</h3>
            <div class="space-y-3">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-white/5 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class="fas fa-envelope text-xs text-slate-500"></i>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-600 uppercase font-bold tracking-wider">Email</p>
                        <p class="text-sm text-slate-300">{{ $anggota->email }}</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-white/5 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class="fas fa-phone text-xs text-slate-500"></i>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-600 uppercase font-bold tracking-wider">No. Telepon</p>
                        <p class="text-sm text-slate-300">{{ $anggota->no_telepon ?? '—' }}</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-white/5 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class="fas fa-map-marker-alt text-xs text-slate-500"></i>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-600 uppercase font-bold tracking-wider">Alamat</p>
                        <p class="text-sm text-slate-300 leading-relaxed">{{ $anggota->alamat ?? '—' }}</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-white/5 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class="fas fa-user-circle text-xs text-slate-500"></i>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-600 uppercase font-bold tracking-wider">Username Login</p>
                        <p class="text-sm font-mono text-indigo-300">{{ $anggota->user->username ?? '—' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ RIWAYAT TRANSAKSI ══ --}}
    <div class="bg-[#1e293b] rounded-2xl border border-white/5 shadow-xl overflow-hidden">
        <div class="px-5 py-4 border-b border-white/5">
            <h3 class="text-sm font-bold text-white">Riwayat Transaksi</h3>
            <p class="text-xs text-slate-500 mt-0.5">Semua aktivitas peminjaman anggota ini</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-white/3 border-b border-white/5">
                        <th class="px-5 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-[0.12em]">Buku</th>
                        <th class="px-5 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-[0.12em] text-center">Tgl Pinjam</th>
                        <th class="px-5 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-[0.12em] text-center">Jatuh Tempo</th>
                        <th class="px-5 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-[0.12em] text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/3">
                    @forelse($riwayats as $r)
                    @php
                        $isLate = $r->status_peminjaman === 'dipinjam' && $r->tanggal_kembali_rencana < \Carbon\Carbon::today();
                    @endphp
                    <tr class="table-row-hover transition-colors">
                        <td class="px-5 py-3.5">
                            <div class="text-sm font-medium text-slate-200 truncate max-w-[200px]">{{ $r->buku->judul ?? '—' }}</div>
                            <div class="text-[10px] text-slate-600">{{ $r->buku->pengarang ?? '' }}</div>
                        </td>
                        <td class="px-5 py-3.5 text-center text-xs text-slate-500 tabular-nums">
                            {{ $r->tanggal_pinjam->format('d M Y') }}
                        </td>
                        <td class="px-5 py-3.5 text-center text-xs {{ $isLate ? 'text-rose-400 font-bold' : 'text-slate-500' }} tabular-nums">
                            {{ $r->tanggal_kembali_rencana->format('d M Y') }}
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            @if($isLate)
                                <span class="badge-error inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold"><i class="fas fa-circle text-[4px]"></i> Terlambat</span>
                            @elseif($r->status_peminjaman === 'dipinjam')
                                <span class="badge-warning inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold"><i class="fas fa-circle text-[4px]"></i> Dipinjam</span>
                            @elseif($r->status_peminjaman === 'dikembalikan')
                                <span class="badge-success inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold"><i class="fas fa-circle text-[4px]"></i> Kembali</span>
                            @elseif($r->status_peminjaman === 'menunggu_konfirmasi')
                                <span class="badge-info inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold"><i class="fas fa-circle text-[4px]"></i> Menunggu</span>
                            @else
                                <span class="badge-muted inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold"><i class="fas fa-circle text-[4px]"></i> {{ ucfirst($r->status_peminjaman) }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-5 py-10 text-center text-slate-500 text-sm">Belum ada riwayat peminjaman.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($riwayats->hasPages())
        <div class="px-5 py-3 border-t border-white/5">
            {{ $riwayats->links() }}
        </div>
        @endif
    </div>

</div>
@endsection