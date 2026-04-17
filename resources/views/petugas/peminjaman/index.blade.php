@extends('layouts.petugas')

@section('title', 'Transaksi Peminjaman — Perpustakaan Digital')
@section('page-title', 'Peminjaman Buku')
@section('page-subtitle', 'Kelola transaksi peminjaman perpustakaan')

@section('content')
<div class="space-y-5 animate-fade-in-down"
     x-data="peminjamanPage()"
     x-init="init()">

    {{-- ══════════════════════════════════════════════
         STATISTIK TRANSAKSI
         ══════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">

        {{-- Total Transaksi --}}
        <div class="relative bg-[#1e293b] rounded-2xl p-5 border border-white/5 shadow-xl overflow-hidden group hover:border-indigo-500/25 transition-all duration-300">
            <div class="absolute -top-5 -right-5 w-24 h-24 bg-indigo-500/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Total Transaksi</p>
                    <span class="text-3xl font-extrabold text-white tracking-tight">{{ number_format($totalTransaksi ?? 0) }}</span>
                </div>
                <div class="w-11 h-11 bg-indigo-500/15 border border-indigo-500/20 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-receipt text-indigo-400"></i>
                </div>
            </div>
        </div>

        {{-- Sedang Dipinjam --}}
        <div class="relative bg-[#1e293b] rounded-2xl p-5 border border-white/5 shadow-xl overflow-hidden group hover:border-amber-500/25 transition-all duration-300">
            <div class="absolute -top-5 -right-5 w-24 h-24 bg-amber-500/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Sedang Dipinjam</p>
                    <span class="text-3xl font-extrabold text-amber-400 tracking-tight">{{ number_format($sedangDipinjam ?? 0) }}</span>
                </div>
                <div class="w-11 h-11 bg-amber-500/15 border border-amber-500/20 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-book-reader text-amber-400"></i>
                </div>
            </div>
        </div>

        {{-- Terlambat --}}
        <div class="relative bg-[#1e293b] rounded-2xl p-5 border border-white/5 shadow-xl overflow-hidden group hover:border-rose-500/25 transition-all duration-300">
            <div class="absolute -top-5 -right-5 w-24 h-24 bg-rose-500/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Terlambat Kembali</p>
                    <span class="text-3xl font-extrabold {{ ($terlambat ?? 0) > 0 ? 'text-rose-400' : 'text-slate-400' }} tracking-tight">
                        {{ number_format($terlambat ?? 0) }}
                    </span>
                </div>
                <div class="w-11 h-11 {{ ($terlambat ?? 0) > 0 ? 'bg-rose-500/15 border-rose-500/20' : 'bg-white/5 border-white/10' }} border rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-clock {{ ($terlambat ?? 0) > 0 ? 'text-rose-400' : 'text-slate-500' }}"></i>
                </div>
            </div>
        </div>

        {{-- CTA: Menunggu Konfirmasi --}}
        <a href="{{ route('petugas.peminjaman.index', ['status' => 'menunggu_konfirmasi']) }}"
           class="relative bg-gradient-to-br from-emerald-600 to-teal-700 rounded-2xl p-5 shadow-xl overflow-hidden group hover:shadow-emerald-500/30 transition-all duration-300 flex flex-col justify-center items-center text-center cursor-pointer">
            <div class="absolute -top-4 -right-4 w-20 h-20 bg-white/10 rounded-full blur-2xl"></div>
            <div class="relative w-11 h-11 bg-white/20 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                <i class="fas fa-hourglass-half text-white text-lg"></i>
            </div>
            <h3 class="text-sm font-bold text-white">Perlu Konfirmasi</h3>
            <p class="text-xs text-emerald-100 mt-0.5">Setujui pengajuan anggota</p>
        </a>
    </div>

    {{-- ══════════════════════════════════════════════
         TABEL DATA PEMINJAMAN
         ══════════════════════════════════════════════ --}}
    <div class="bg-[#1e293b] rounded-2xl border border-white/5 shadow-xl overflow-hidden">

        {{-- Header + Filter --}}
        <div class="px-5 py-4 border-b border-white/5">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-bold text-white tracking-tight">Daftar Transaksi</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Riwayat peminjaman dan pengembalian buku</p>
                </div>
            </div>

            {{-- Filter Form --}}
            <form action="{{ route('petugas.peminjaman.index') }}" method="GET"
                  class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                <div class="relative">
                    <i class="fas fa-user absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500 text-xs"></i>
                    <input type="text" name="search_anggota" value="{{ request('search_anggota') }}"
                           placeholder="Cari anggota / NIS..."
                           class="w-full pl-9 pr-4 py-2 bg-slate-900/60 border border-white/8 rounded-xl text-sm text-white placeholder-slate-500
                                  focus:outline-none focus:border-indigo-500/60 focus:ring-2 focus:ring-indigo-500/20 transition">
                </div>
                <div class="relative">
                    <i class="fas fa-book absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500 text-xs"></i>
                    <input type="text" name="search_buku" value="{{ request('search_buku') }}"
                           placeholder="Cari judul buku..."
                           class="w-full pl-9 pr-4 py-2 bg-slate-900/60 border border-white/8 rounded-xl text-sm text-white placeholder-slate-500
                                  focus:outline-none focus:border-indigo-500/60 transition">
                </div>
                <select name="status"
                        class="w-full px-3 py-2 bg-slate-900/60 border border-white/8 rounded-xl text-sm text-slate-300
                               focus:outline-none focus:border-indigo-500/60 cursor-pointer transition">
                    <option value="">Semua Status</option>
                    <option value="menunggu_konfirmasi" {{ request('status') == 'menunggu_konfirmasi' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                    <option value="dipinjam"            {{ request('status') == 'dipinjam'            ? 'selected' : '' }}>Sedang Dipinjam</option>
                    <option value="dikembalikan"        {{ request('status') == 'dikembalikan'        ? 'selected' : '' }}>Sudah Dikembalikan</option>
                    <option value="terlambat"           {{ request('status') == 'terlambat'           ? 'selected' : '' }}>Terlambat</option>
                    <option value="hilang"              {{ request('status') == 'hilang'              ? 'selected' : '' }}>Hilang</option>
                </select>
                <div class="flex gap-2">
                    <button type="submit"
                            class="flex-1 px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold rounded-xl shadow-lg shadow-indigo-500/25 transition">
                        <i class="fas fa-filter mr-1.5"></i> Filter
                    </button>
                    <a href="{{ route('petugas.peminjaman.index') }}"
                       class="px-3 py-2 bg-white/5 hover:bg-white/10 border border-white/8 text-slate-400 hover:text-white text-xs font-semibold rounded-xl transition"
                       title="Reset">
                        <i class="fas fa-rotate"></i>
                    </a>
                </div>
            </form>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-white/3 border-b border-white/5">
                        <th class="px-5 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-[0.12em]">ID & Tanggal</th>
                        <th class="px-5 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-[0.12em]">Anggota</th>
                        <th class="px-5 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-[0.12em]">Buku</th>
                        <th class="px-5 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-[0.12em] text-center">Jatuh Tempo</th>
                        <th class="px-5 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-[0.12em] text-center">Status</th>
                        <th class="px-5 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-[0.12em] text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/3">
                    @forelse($peminjamans as $peminjaman)
                    @php
                        $isLate = $peminjaman->status_peminjaman === 'dipinjam'
                               && $peminjaman->tanggal_kembali_rencana < \Carbon\Carbon::today();
                    @endphp
                    <tr class="table-row-hover transition-colors duration-150 group {{ $isLate ? 'bg-rose-500/3' : '' }}">

                        {{-- ID & Tanggal --}}
                        <td class="px-5 py-3.5">
                            <div class="font-mono text-xs font-bold text-indigo-400">
                                #PMJ-{{ str_pad($peminjaman->id_peminjaman, 5, '0', STR_PAD_LEFT) }}
                            </div>
                            <div class="text-[10px] text-slate-600 mt-0.5 tabular-nums">
                                {{ $peminjaman->tanggal_pinjam->format('d M Y') }}
                            </div>
                            @if($peminjaman->petugas)
                                <div class="text-[10px] text-slate-700 mt-0.5">oleh {{ $peminjaman->petugas->name }}</div>
                            @endif
                        </td>

                        {{-- Anggota --}}
                        <td class="px-5 py-3.5">
                            @if($peminjaman->anggota)
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-violet-500 to-indigo-600 flex items-center justify-center text-white font-bold text-[10px] flex-shrink-0 shadow-md">
                                        {{ strtoupper(substr($peminjaman->anggota->nama, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-slate-200 group-hover:text-white transition-colors leading-tight">
                                            {{ $peminjaman->anggota->nama }}
                                        </div>
                                        <div class="text-[10px] text-slate-600 mt-0.5">{{ $peminjaman->anggota->kelas }}</div>
                                    </div>
                                </div>
                            @else
                                <span class="text-xs text-rose-400 italic">Data dihapus</span>
                            @endif
                        </td>

                        {{-- Buku --}}
                        <td class="px-5 py-3.5 max-w-[180px]">
                            @if($peminjaman->buku)
                                <div class="text-sm text-slate-300 truncate font-medium">{{ $peminjaman->buku->judul }}</div>
                                <div class="text-[10px] text-slate-600 truncate mt-0.5">{{ $peminjaman->buku->pengarang }}</div>
                            @else
                                <span class="text-xs text-rose-400 italic">Buku dihapus</span>
                            @endif
                        </td>

                        {{-- Jatuh Tempo --}}
                        <td class="px-5 py-3.5 text-center">
                            <div class="text-xs font-semibold tabular-nums {{ $isLate ? 'text-rose-400' : 'text-slate-400' }}">
                                {{ $peminjaman->tanggal_kembali_rencana->format('d M Y') }}
                            </div>
                            @if($isLate)
                                <span class="inline-block mt-1 px-2 py-0.5 bg-rose-500/15 text-rose-400 text-[9px] font-bold rounded-full border border-rose-500/20 animate-pulse">
                                    TERLAMBAT
                                </span>
                            @elseif($peminjaman->status_peminjaman === 'dipinjam')
                                @php $sisaHari = $peminjaman->tanggal_kembali_rencana->diffInDays(\Carbon\Carbon::today(), false) @endphp
                                <div class="text-[10px] text-slate-600 mt-0.5">
                                    {{ $sisaHari < 0 ? abs($sisaHari).' hari lagi' : 'hari ini' }}
                                </div>
                            @endif
                        </td>

                        {{-- Status Badge --}}
                        <td class="px-5 py-3.5 text-center">
                            @php
                                if ($isLate) {
                                    $bClass = 'badge-error'; $bIcon = 'fa-exclamation-circle'; $bLabel = 'Terlambat';
                                } else {
                                    switch($peminjaman->status_peminjaman) {
                                        case 'dipinjam':            $bClass = 'badge-warning'; $bIcon = 'fa-clock';           $bLabel = 'Dipinjam'; break;
                                        case 'dikembalikan':        $bClass = 'badge-success'; $bIcon = 'fa-circle-check';    $bLabel = 'Kembali';  break;
                                        case 'menunggu_konfirmasi': $bClass = 'badge-info';    $bIcon = 'fa-hourglass-half';  $bLabel = 'Menunggu'; break;
                                        case 'hilang':              $bClass = 'badge-muted';   $bIcon = 'fa-question-circle'; $bLabel = 'Hilang';   break;
                                        case 'ditolak':             $bClass = 'badge-error';   $bIcon = 'fa-ban';             $bLabel = 'Ditolak';  break;
                                        default:                    $bClass = 'badge-muted';   $bIcon = 'fa-circle';          $bLabel = ucfirst($peminjaman->status_peminjaman);
                                    }
                                }
                            @endphp
                            <span class="{{ $bClass }} inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold">
                                <i class="fas {{ $bIcon }} text-[8px]"></i>
                                {{ $bLabel }}
                            </span>
                        </td>

                        {{-- Aksi --}}
                        <td class="px-5 py-3.5 text-center">
                            <div class="flex items-center justify-center gap-1.5">

                                @if($peminjaman->status_peminjaman === 'menunggu_konfirmasi')
                                    {{-- SETUJUI --}}
                                    <button type="button"
                                            @click="window.dispatchEvent(new CustomEvent('confirm', { 
                                                detail: {
                                                    title: 'Setujui Peminjaman?',
                                                    message: 'Anda akan menyetujui peminjaman buku <strong>{{ addslashes($peminjaman->buku->judul ?? 'ini') }}</strong>. Stok buku akan berkurang otomatis.',
                                                    action: '{{ route('petugas.peminjaman.setujui', $peminjaman->id_peminjaman) }}',
                                                    type: 'success',
                                                    confirmText: 'Ya, Setujui',
                                                    usePin: false
                                                }
                                            }))"
                                            class="w-8 h-8 rounded-lg bg-emerald-500/10 hover:bg-emerald-500 border border-emerald-500/20 hover:border-emerald-400 text-emerald-400 hover:text-white flex items-center justify-center transition-all duration-150"
                                            title="Setujui Peminjaman">
                                        <i class="fas fa-check text-xs"></i>
                                    </button>

                                    {{-- TOLAK --}}
                                    <button
                                        @click="window.dispatchEvent(new CustomEvent('confirm', { 
                                            detail: {
                                                title: 'Tolak Pengajuan?',
                                                message: 'Pengajuan dari <strong>{{ addslashes($peminjaman->anggota->nama ?? "anggota") }}</strong> akan ditolak.',
                                                action: '{{ route('petugas.peminjaman.tolak', $peminjaman->id_peminjaman) }}',
                                                method: 'POST',
                                                type: 'danger',
                                                confirmText: 'Ya, Tolak',
                                                usePin: false
                                            }
                                        }))"
                                        class="w-8 h-8 rounded-lg bg-rose-500/10 hover:bg-rose-500 border border-rose-500/20 hover:border-rose-400 text-rose-400 hover:text-white flex items-center justify-center transition-all duration-150"
                                        title="Tolak Pengajuan">
                                        <i class="fas fa-xmark text-xs"></i>
                                    </button>

                                @elseif($peminjaman->status_peminjaman !== 'dikembalikan' && $peminjaman->status_peminjaman !== 'hilang')
                                    {{-- KEMBALIKAN --}}
                                    <button type="button"
                                            @click="window.dispatchEvent(new CustomEvent('confirm', { 
                                                detail: {
                                                    title: 'Konfirmasi Kembali?',
                                                    message: 'Proses pengembalian buku <strong>{{ addslashes($peminjaman->buku->judul ?? 'ini') }}</strong>? Stok akan bertambah kembali.',
                                                    action: '{{ route('petugas.peminjaman.kembali', $peminjaman->id_peminjaman) }}',
                                                    type: 'info',
                                                    confirmText: 'Ya, Kembali',
                                                    usePin: false
                                                }
                                            }))"
                                            class="w-8 h-8 rounded-lg bg-blue-500/10 hover:bg-blue-500 border border-blue-500/20 hover:border-blue-400 text-blue-400 hover:text-white flex items-center justify-center transition-all duration-150"
                                            title="Proses Pengembalian">
                                        <i class="fas fa-rotate-left text-xs"></i>
                                    </button>

                                    @if($peminjaman->status_peminjaman === 'dipinjam')
                                        {{-- BATALKAN --}}
                                        <button
                                            @click="window.dispatchEvent(new CustomEvent('confirm', { 
                                                detail: {
                                                    title: 'Batalkan Pinjaman?',
                                                    message: 'Batalkan peminjaman buku <strong>{{ addslashes(Str::limit($peminjaman->buku->judul ?? 'ini', 30)) }}</strong>? Stok akan kembali ke rak.',
                                                    action: '{{ route('petugas.peminjaman.destroy', $peminjaman->id_peminjaman) }}',
                                                    method: 'DELETE',
                                                    type: 'danger',
                                                    confirmText: 'Ya, Batalkan',
                                                    usePin: false
                                                }
                                            }))"
                                            class="w-8 h-8 rounded-lg bg-rose-500/10 hover:bg-rose-500 border border-rose-500/20 hover:border-rose-400 text-rose-400 hover:text-white flex items-center justify-center transition-all duration-150"
                                            title="Batalkan Peminjaman">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    @endif
                                @endif

                                {{-- DETAIL (selalu tampil) --}}
                                <button class="w-8 h-8 rounded-lg bg-white/5 hover:bg-slate-600 border border-white/8 hover:border-slate-500 text-slate-500 hover:text-white flex items-center justify-center transition-all duration-150"
                                        title="Detail Transaksi"
                                        @click="window.toast.info('Fitur detail sedang dalam pengembangan.')">
                                    <i class="fas fa-eye text-xs"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-14 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-14 h-14 bg-white/3 rounded-2xl flex items-center justify-center mb-4">
                                    <i class="fas fa-box-open text-slate-600 text-2xl"></i>
                                </div>
                                <p class="text-sm font-medium text-slate-400">Belum ada transaksi peminjaman.</p>
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

        {{-- Pagination --}}
        @if($peminjamans->hasPages())
        <div class="px-5 py-4 border-t border-white/5 flex items-center justify-between">
            <span class="text-xs text-slate-500">
                Menampilkan <strong class="text-slate-300">{{ $peminjamans->firstItem() }}</strong>–<strong class="text-slate-300">{{ $peminjamans->lastItem() }}</strong>
                dari <strong class="text-slate-300">{{ $peminjamans->total() }}</strong> transaksi
            </span>
            <div class="flex gap-1.5" x-data="{}">
                @if($peminjamans->onFirstPage())
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/3 text-slate-700 border border-white/5 cursor-not-allowed"><i class="fas fa-chevron-left text-xs"></i></span>
                @else
                    <a href="{{ $peminjamans->previousPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/5 hover:bg-white/10 text-slate-400 hover:text-white border border-white/8 transition"><i class="fas fa-chevron-left text-xs"></i></a>
                @endif

                @if($peminjamans->hasMorePages())
                    <a href="{{ $peminjamans->nextPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/5 hover:bg-white/10 text-slate-400 hover:text-white border border-white/8 transition"><i class="fas fa-chevron-right text-xs"></i></a>
                @else
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/3 text-slate-700 border border-white/5 cursor-not-allowed"><i class="fas fa-chevron-right text-xs"></i></span>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    function peminjamanPage() {
        return {
            init() {}
        };
    }
</script>
@endpush
