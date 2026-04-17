@extends('layouts.petugas')

@section('title', 'Data Anggota — Perpustakaan Digital')
@section('page-title', 'Data Anggota')
@section('page-subtitle', 'Kelola data anggota perpustakaan')

@section('content')
<div class="space-y-5 animate-fade-in-down"
     x-data="anggotaPage()"
     x-init="init()">

    {{-- ══════════════════════════════════════════════
         STATISTIK RINGKAS
         ══════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        {{-- Total --}}
        <div class="relative bg-[#1e293b] rounded-2xl p-5 border border-white/5 shadow-xl overflow-hidden group hover:border-indigo-500/25 transition-all duration-300">
            <div class="absolute -top-5 -right-5 w-24 h-24 bg-indigo-500/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Total Anggota</p>
                    <span class="text-3xl font-extrabold text-white tracking-tight">{{ number_format($totalAnggota ?? 0) }}</span>
                </div>
                <div class="w-11 h-11 bg-indigo-500/15 border border-indigo-500/20 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-users text-indigo-400"></i>
                </div>
            </div>
        </div>

        {{-- Aktif --}}
        <div class="relative bg-[#1e293b] rounded-2xl p-5 border border-white/5 shadow-xl overflow-hidden group hover:border-emerald-500/25 transition-all duration-300">
            <div class="absolute -top-5 -right-5 w-24 h-24 bg-emerald-500/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Anggota Aktif</p>
                    <span class="text-3xl font-extrabold text-emerald-400 tracking-tight">{{ number_format($anggotaAktif ?? 0) }}</span>
                </div>
                <div class="w-11 h-11 bg-emerald-500/15 border border-emerald-500/20 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-user-check text-emerald-400"></i>
                </div>
            </div>
        </div>

        {{-- Non-Aktif --}}
        <div class="relative bg-[#1e293b] rounded-2xl p-5 border border-white/5 shadow-xl overflow-hidden group hover:border-rose-500/25 transition-all duration-300">
            <div class="absolute -top-5 -right-5 w-24 h-24 bg-rose-500/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Non-Aktif</p>
                    <span class="text-3xl font-extrabold text-rose-400 tracking-tight">{{ number_format($anggotaNonAktif ?? 0) }}</span>
                </div>
                <div class="w-11 h-11 bg-rose-500/15 border border-rose-500/20 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-user-slash text-rose-400"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════
         TABEL DATA ANGGOTA
         ══════════════════════════════════════════════ --}}
    <div class="bg-[#1e293b] rounded-2xl border border-white/5 shadow-xl overflow-hidden">

        {{-- Header --}}
        <div class="px-5 py-4 border-b border-white/5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
            <div>
                <h3 class="text-sm font-bold text-white tracking-tight">Daftar Anggota Perpustakaan</h3>
                <p class="text-xs text-slate-500 mt-0.5">Akun anggota dibuat melalui halaman register, petugas cukup mengelola data yang sudah terdaftar</p>
            </div>
            <span class="inline-flex items-center gap-2 px-4 py-2 bg-white/5 border border-white/8 text-slate-400 text-xs font-semibold rounded-xl whitespace-nowrap">
                <i class="fas fa-circle-info text-[11px]"></i>
                Pendaftaran via register
            </span>
        </div>

        {{-- Filter & Search --}}
        <div class="px-5 py-3.5 bg-white/2 border-b border-white/5">
            <form action="{{ route('petugas.anggota.index') }}" method="GET"
                  class="flex flex-col md:flex-row gap-3">
                <div class="relative flex-1">
                    <i class="fas fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500 text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari nama, NIS, atau email..."
                           class="w-full pl-9 pr-4 py-2 bg-slate-900/60 border border-white/8 rounded-xl text-sm text-white placeholder-slate-500
                                  focus:outline-none focus:border-indigo-500/60 focus:ring-2 focus:ring-indigo-500/20 transition">
                </div>
                <select name="status"
                        class="w-full md:w-40 px-3 py-2 bg-slate-900/60 border border-white/8 rounded-xl text-sm text-slate-300
                               focus:outline-none focus:border-indigo-500/60 cursor-pointer transition">
                    <option value="">Semua Status</option>
                    <option value="aktif"     {{ request('status') === 'aktif'     ? 'selected' : '' }}>Aktif</option>
                    <option value="non-aktif" {{ request('status') === 'non-aktif' ? 'selected' : '' }}>Non-Aktif</option>
                </select>
                <input type="text" name="kelas" value="{{ request('kelas') }}"
                       placeholder="Filter kelas..."
                       class="w-full md:w-36 px-3 py-2 bg-slate-900/60 border border-white/8 rounded-xl text-sm text-white placeholder-slate-500
                              focus:outline-none focus:border-indigo-500/60 transition">
                <div class="flex gap-2">
                    <button type="submit"
                            class="flex-1 md:flex-none px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold rounded-xl shadow-lg shadow-indigo-500/25 transition whitespace-nowrap">
                        <i class="fas fa-filter mr-1.5"></i> Filter
                    </button>
                    <a href="{{ route('petugas.anggota.index') }}"
                       class="px-3 py-2 bg-white/5 hover:bg-white/10 border border-white/8 text-slate-400 hover:text-white text-xs font-semibold rounded-xl transition"
                       title="Reset filter">
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
                        <th class="px-5 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-[0.12em] w-12">#</th>
                        <th class="px-5 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-[0.12em]">Anggota</th>
                        <th class="px-5 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-[0.12em]">Kelas</th>
                        <th class="px-5 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-[0.12em]">Email / Username</th>
                        <th class="px-5 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-[0.12em] text-center">Bergabung</th>
                        <th class="px-5 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-[0.12em] text-center">Status</th>
                        <th class="px-5 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-[0.12em] text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/3">
                    @forelse($anggotas as $index => $anggota)
                    <tr class="table-row-hover transition-colors duration-150 group">
                        {{-- No --}}
                        <td class="px-5 py-3.5 text-xs text-slate-600 font-medium tabular-nums">
                            {{ $anggotas->firstItem() + $index }}
                        </td>

                        {{-- Nama & NIS --}}
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center text-white font-bold text-xs shadow-md flex-shrink-0">
                                    {{ strtoupper(substr($anggota->nama, 0, 2)) }}
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-slate-200 group-hover:text-white transition-colors leading-tight">
                                        {{ $anggota->nama }}
                                    </div>
                                    <div class="text-[10px] text-slate-600 font-mono mt-0.5">NIS: {{ $anggota->nis_nisn }}</div>
                                </div>
                            </div>
                        </td>

                        {{-- Kelas --}}
                        <td class="px-5 py-3.5">
                            <span class="px-2 py-1 bg-slate-700/60 border border-white/5 text-xs font-medium text-slate-300 rounded-lg">
                                {{ $anggota->kelas }}
                            </span>
                        </td>

                        {{-- Email --}}
                        <td class="px-5 py-3.5">
                            <div class="text-xs text-slate-400">{{ $anggota->email }}</div>
                        </td>

                        {{-- Bergabung --}}
                        <td class="px-5 py-3.5 text-center">
                            <div class="text-xs text-slate-500 tabular-nums">{{ $anggota->tanggal_bergabung->format('d M Y') }}</div>
                        </td>

                        {{-- Status Badge --}}
                        <td class="px-5 py-3.5 text-center">
                            @if($anggota->status === 'aktif')
                                <span class="badge-success inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold">
                                    <i class="fas fa-circle text-[4px]"></i> Aktif
                                </span>
                            @else
                                <span class="badge-error inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold">
                                    <i class="fas fa-circle text-[4px]"></i> Non-Aktif
                                </span>
                            @endif
                        </td>

                        {{-- Aksi --}}
                        <td class="px-5 py-3.5 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                {{-- Lihat Detail --}}
                                <a href="{{ route('petugas.anggota.show', $anggota->id) }}"
                                   class="group/btn w-8 h-8 rounded-lg bg-white/5 hover:bg-slate-600 border border-white/8 hover:border-slate-500 text-slate-500 hover:text-white flex items-center justify-center transition-all duration-150"
                                   title="Lihat Detail">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>

                                {{-- Edit --}}
                                <a href="{{ route('petugas.anggota.edit', $anggota->id) }}"
                                   class="group/btn w-8 h-8 rounded-lg bg-indigo-500/10 hover:bg-indigo-500 border border-indigo-500/20 hover:border-indigo-400 text-indigo-400 hover:text-white flex items-center justify-center transition-all duration-150"
                                   title="Edit Anggota">
                                    <i class="fas fa-pen text-xs"></i>
                                </a>

                                {{-- Hapus (trigger modal) --}}
                                <button
                                    type="button"
                                    @click="window.dispatchEvent(new CustomEvent('confirm', { 
                                        detail: {
                                            title: 'Hapus Anggota?',
                                            message: 'Anggota <strong>{{ addslashes($anggota->nama) }}</strong> akan dihapus permanen beserta akun loginnya.',
                                            action: '{{ route('petugas.anggota.destroy', $anggota->id) }}',
                                            method: 'DELETE',
                                            type: 'danger',
                                            confirmText: 'Ya, Hapus',
                                            usePin: false
                                        }
                                    }))"
                                    class="group/btn w-8 h-8 rounded-lg bg-rose-500/10 hover:bg-rose-500 border border-rose-500/20 hover:border-rose-400 text-rose-400 hover:text-white flex items-center justify-center transition-all duration-150"
                                    title="Hapus Anggota">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-14 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-14 h-14 bg-white/3 rounded-2xl flex items-center justify-center mb-4">
                                    <i class="fas fa-users-slash text-slate-600 text-2xl"></i>
                                </div>
                                <p class="text-sm font-medium text-slate-400">Tidak ada data anggota.</p>
                                <p class="text-xs text-slate-600 mt-1">Coba ubah kata kunci atau filter.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($anggotas->hasPages())
        <div class="px-5 py-4 border-t border-white/5 flex items-center justify-between">
            <span class="text-xs text-slate-500">
                Menampilkan <strong class="text-slate-300">{{ $anggotas->firstItem() }}</strong>–<strong class="text-slate-300">{{ $anggotas->lastItem() }}</strong>
                dari <strong class="text-slate-300">{{ $anggotas->total() }}</strong> anggota
            </span>
            <div class="flex gap-1.5" x-data="{}">
                @if($anggotas->onFirstPage())
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/3 text-slate-700 border border-white/5 cursor-not-allowed">
                        <i class="fas fa-chevron-left text-xs"></i>
                    </span>
                @else
                    <a href="{{ $anggotas->previousPageUrl() }}"
                       class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/5 hover:bg-white/10 text-slate-400 hover:text-white border border-white/8 transition">
                        <i class="fas fa-chevron-left text-xs"></i>
                    </a>
                @endif

                @if($anggotas->hasMorePages())
                    <a href="{{ $anggotas->nextPageUrl() }}"
                       class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/5 hover:bg-white/10 text-slate-400 hover:text-white border border-white/8 transition">
                        <i class="fas fa-chevron-right text-xs"></i>
                    </a>
                @else
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/3 text-slate-700 border border-white/5 cursor-not-allowed">
                        <i class="fas fa-chevron-right text-xs"></i>
                    </span>
                @endif
            </div>
        </div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script>
    function anggotaPage() {
        return {
            init() {}
        };
    }
</script>
@endpush
