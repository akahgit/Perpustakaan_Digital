@extends('layouts.petugas')

@section('title', 'Koleksi Buku — Perpustakaan Digital')
@section('page-title', 'Koleksi Buku')
@section('page-subtitle', 'Manajemen daftar buku, stok, dan kategori')

@section('content')
<div class="space-y-5 animate-fade-in-down">

    {{-- ══ STATISTIK ══ --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="bg-[#1e293b] rounded-2xl p-5 border border-white/5 shadow-xl relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-16 h-16 bg-indigo-500/10 rounded-full blur-2xl group-hover:bg-indigo-500/20 transition-all"></div>
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Total Judul</p>
            <span class="text-3xl font-extrabold text-white">{{ number_format($judulBerbeda ?? 0) }}</span>
            <p class="text-[10px] text-slate-600 mt-1">{{ number_format($totalTercatat ?? 0) }} eksemplar tercatat</p>
        </div>
        <div class="bg-[#1e293b] rounded-2xl p-5 border border-white/5 shadow-xl relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-16 h-16 bg-emerald-500/10 rounded-full blur-2xl group-hover:bg-emerald-500/20 transition-all"></div>
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Tersedia</p>
            <span class="text-3xl font-extrabold text-emerald-400">{{ number_format($tersedia ?? 0) }}</span>
            <p class="text-[10px] text-slate-600 mt-1">Eksemplar di Rak</p>
        </div>
        <div class="bg-[#1e293b] rounded-2xl p-5 border border-white/5 shadow-xl relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-16 h-16 bg-amber-500/10 rounded-full blur-2xl group-hover:bg-amber-500/20 transition-all"></div>
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Dipinjam</p>
            <span class="text-3xl font-extrabold text-amber-400">{{ number_format($sedangDipinjam ?? 0) }}</span>
            <p class="text-[10px] text-slate-600 mt-1">Sedang Beredar</p>
        </div>
        <div class="bg-[#1e293b] rounded-2xl p-5 border border-white/5 shadow-xl relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-16 h-16 bg-rose-500/10 rounded-full blur-2xl group-hover:bg-rose-500/20 transition-all"></div>
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Rusak / Hilang</p>
            <span class="text-3xl font-extrabold text-rose-400">{{ number_format(($stokRusak ?? 0) + ($stokHilang ?? 0)) }}</span>
            <p class="text-[10px] text-slate-600 mt-1">{{ $stokRusak ?? 0 }} rusak • {{ $stokHilang ?? 0 }} hilang</p>
        </div>
        <a href="{{ route('petugas.buku.create') }}"
           class="bg-indigo-600 hover:bg-indigo-500 rounded-2xl p-5 shadow-xl flex flex-col items-center justify-center text-center group transition-all">
            <i class="fas fa-plus text-white text-2xl mb-2 group-hover:scale-110 transition-transform"></i>
            <span class="text-sm font-bold text-white">Tambah Buku</span>
        </a>
    </div>

    {{-- ══ FILTERS & TOOLS ══ --}}
    <div class="bg-[#1e293b] rounded-2xl border border-white/5 shadow-xl p-4">
        <form action="{{ route('petugas.buku.index') }}" method="GET" class="flex flex-wrap items-center gap-4">
            <div class="flex-1 min-w-[240px] relative">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-xs"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari judul, pengarang, atau ISBN..."
                       class="w-full pl-10 pr-4 py-2.5 bg-slate-900/60 border border-white/8 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500/60 transition">
            </div>
            
            <div class="w-full sm:w-auto">
                <select name="genre"
                        onchange="this.form.submit()"
                        class="w-full px-4 py-2.5 bg-slate-900/60 border border-white/8 rounded-xl text-sm text-slate-300 focus:outline-none focus:border-indigo-500/60 transition cursor-pointer">
                    <option value="">Semua Kategori</option>
                    @foreach ($kategoris as $kat)
                        <option value="{{ $kat->slug }}" {{ request('genre') == $kat->slug ? 'selected' : '' }}>
                            {{ $kat->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold rounded-xl transition shadow-lg shadow-indigo-500/20">
                    Filter
                </button>
                <a href="{{ route('petugas.buku.index') }}" class="px-5 py-2.5 bg-white/5 hover:bg-white/10 text-slate-400 hover:text-white text-xs font-bold rounded-xl border border-white/8 transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- ══ TABEL KOLEKSI ══ --}}
    <div class="bg-[#1e293b] rounded-2xl border border-white/5 shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-white/3 border-b border-white/5">
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-[0.12em] w-16">#</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-[0.12em]">Buku & Detail</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-[0.12em]">Pengarang/Penerbit</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-[0.12em] text-right">Harga Ganti</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-[0.12em] text-center">Stok</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-[0.12em] text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/3">
                    @forelse($bukus as $index => $buku)
                    @php
                        $stokTercatat = (int) ($buku->stok ?? 0) + (int) ($buku->stok_hilang ?? 0);
                        $stokSaatIni = (int) ($buku->stok ?? 0);
                        $stokTersedia = (int) ($buku->stok_tersedia ?? 0);
                        $stokDipinjam = max(0, $stokSaatIni - $stokTersedia - (int) ($buku->stok_rusak ?? 0));
                        $tersedia_pct = $stokSaatIni > 0 ? ($stokTersedia / $stokSaatIni) * 100 : 0;
                    @endphp
                    <tr class="table-row-hover transition-colors group">
                        <td class="px-6 py-4 text-xs text-slate-600 tabular-nums">
                            {{ $bukus->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                {{-- Cover --}}
                                <div class="w-12 h-16 rounded-lg bg-slate-900 border border-white/5 shadow-lg overflow-hidden flex-shrink-0 group-hover:scale-105 transition-transform duration-300">
                                    @if($buku->cover_buku)
                                        <img src="{{ Storage::url($buku->cover_buku) }}" alt="Cover" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-slate-800 to-slate-900">
                                            <i class="fas fa-book text-slate-700 text-sm"></i>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-white group-hover:text-indigo-400 transition-colors line-clamp-1">{{ $buku->judul }}</div>
                                    <div class="flex flex-wrap gap-2 mt-1.5 font-medium">
                                        <span class="px-2 py-0.5 rounded-md bg-indigo-500/10 text-indigo-400 text-[9px] uppercase tracking-wider">
                                            {{ $buku->kategori->nama_kategori ?? 'Umum' }}
                                        </span>
                                        <span class="text-[10px] text-slate-600 font-mono">ISBN: {{ $buku->isbn ?? '—' }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-xs text-slate-300 font-medium">{{ $buku->pengarang }}</div>
                            <div class="text-[10px] text-slate-600 mt-1 uppercase tracking-tighter">{{ $buku->penerbit }} &bull; {{ $buku->tahun_terbit }}</div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="text-xs font-bold text-emerald-400">Rp {{ number_format($buku->harga_ganti ?? 0, 0, ',', '.') }}</div>
                            <div class="text-[10px] text-slate-600 mt-1">Rusak: {{ $buku->stok_rusak ?? 0 }} | Hilang: {{ $buku->stok_hilang ?? 0 }}</div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex flex-col items-center gap-1.5">
                                <span class="text-xs font-bold {{ $buku->stok_tersedia > 0 ? 'text-emerald-400' : 'text-rose-500' }}">
                                    {{ $stokTersedia }} <span class="text-[10px] font-normal text-slate-600">tersedia dari {{ $stokSaatIni }}</span>
                                </span>
                                <span class="text-[10px] text-slate-600">
                                    Tercatat: {{ $stokTercatat }} • Dipinjam: {{ $stokDipinjam }}
                                </span>
                                {{-- Progress bar mini --}}
                                <div class="w-16 h-1 bg-white/5 rounded-full overflow-hidden">
                                    <div class="h-full {{ $tersedia_pct > 20 ? 'bg-emerald-500' : 'bg-rose-500' }}" style="width: {{ $tersedia_pct }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('petugas.buku.edit', $buku->id_buku) }}"
                                   class="w-8 h-8 rounded-lg bg-indigo-500/10 hover:bg-indigo-500 border border-indigo-500/20 text-indigo-400 hover:text-white flex items-center justify-center transition-all"
                                   title="Edit">
                                    <i class="fas fa-pen text-xs"></i>
                                </a>
                                 {{-- Gunakan Form Global for consistency --}}
                                 @if($buku->peminjaman_aktif_count > 0)
                                     <button type="button"
                                             @click="window.dispatchEvent(new CustomEvent('confirm', { 
                                                 detail: {
                                                     title: 'Tidak Bisa Dihapus',
                                                     message: 'Buku <strong>{{ addslashes($buku->judul) }}</strong> tidak bisa dihapus karena sedang ada peminjaman aktif.',
                                                     type: 'warning',
                                                     confirmText: 'Oke',
                                                     cancelText: '',
                                                     usePin: false
                                                 }
                                             }))"
                                             class="w-8 h-8 rounded-lg bg-slate-500/10 border border-slate-500/20 text-slate-500 flex items-center justify-center cursor-help transition-all"
                                             title="Buku sedang dipinjam">
                                         <i class="fas fa-trash text-xs opacity-50"></i>
                                     </button>
                                 @else
                                     <button type="button"
                                             @click="window.dispatchEvent(new CustomEvent('confirm', { 
                                                 detail: {
                                                     title: 'Hapus Buku?',
                                                     message: 'Buku <strong>{{ addslashes($buku->judul) }}</strong> akan dihapus permanen dari sistem.',
                                                     action: '{{ route('petugas.buku.destroy', $buku->id_buku) }}',
                                                     method: 'DELETE',
                                                     type: 'danger',
                                                     confirmText: 'Ya, Hapus',
                                                     usePin: false
                                                 }
                                             }))"
                                             class="w-8 h-8 rounded-lg bg-rose-500/10 hover:bg-rose-500 border border-rose-500/20 text-rose-400 hover:text-white flex items-center justify-center transition-all"
                                             title="Hapus">
                                         <i class="fas fa-trash text-xs"></i>
                                     </button>
                                 @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-14 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-14 h-14 bg-white/3 rounded-2xl flex items-center justify-center mb-4">
                                    <i class="fas fa-book-open text-slate-600 text-2xl"></i>
                                </div>
                                <p class="text-sm text-slate-400">Tidak ada buku ditemukan.</p>
                                <a href="{{ route('petugas.buku.create') }}" class="mt-2 text-xs text-indigo-400 hover:text-indigo-300 font-semibold transition">
                                    Tambah buku sekarang →
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($bukus->hasPages())
        <div class="px-6 py-4 border-t border-white/5 flex items-center justify-between">
            <span class="text-xs text-slate-500">
                Menampilkan <strong class="text-slate-300">{{ $bukus->firstItem() }}</strong>-<strong class="text-slate-300">{{ $bukus->lastItem() }}</strong> dari <strong class="text-slate-300">{{ $bukus->total() }}</strong> buku
            </span>
            <div class="flex gap-1.5" x-data="{}">
                @if($bukus->onFirstPage())
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/3 text-slate-700 border border-white/5 cursor-not-allowed"><i class="fas fa-chevron-left text-xs"></i></span>
                @else
                    <a href="{{ $bukus->previousPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/5 hover:bg-white/10 text-slate-400 hover:text-white border border-white/8 transition shadow-lg"><i class="fas fa-chevron-left text-xs"></i></a>
                @endif
                
                @if($bukus->hasMorePages())
                    <a href="{{ $bukus->nextPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/5 hover:bg-white/10 text-slate-400 hover:text-white border border-white/8 transition shadow-lg"><i class="fas fa-chevron-right text-xs"></i></a>
                @else
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/3 text-slate-700 border border-white/5 cursor-not-allowed"><i class="fas fa-chevron-right text-xs"></i></span>
                @endif
            </div>
        </div>
        @endif
    </div>

</div>
@endsection
