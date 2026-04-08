@extends('layouts.petugas')

@section('title', 'Hasil Pencarian')
@section('page-title', 'Pencarian: ' . ($keyword ?? '...'))

@section('content')
<div class="space-y-6">

    <!-- Header & Info -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-white">
                Hasil Pencarian: <span class="text-indigo-400">"{{ $keyword }}"</span>
            </h2>
            <p class="text-slate-400 text-sm mt-1">
                Ditemukan <strong class="text-white">{{ $totalResults }}</strong> data terkait.
            </p>
        </div>
        <a href="{{ url()->previous() }}" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white text-sm font-medium rounded-lg transition flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    @if($totalResults === 0)
        <!-- Empty State -->
        <div class="bg-[#1e293b] rounded-2xl p-12 text-center border border-slate-700/50">
            <div class="w-20 h-20 bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-search text-3xl text-slate-500"></i>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">Tidak ada hasil ditemukan</h3>
            <p class="text-slate-400 mb-6">Coba kata kunci lain atau periksa ejaan Anda.</p>
            <a href="{{ route('petugas.dashboard') }}" class="inline-block px-6 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-lg transition">
                Ke Dashboard
            </a>
        </div>
    @else
        <!-- Section: Buku -->
        @if($bukus->count() > 0)
        <div class="bg-[#1e293b] rounded-2xl border border-slate-700/50 overflow-hidden">
            <div class="p-6 border-b border-slate-700/50 flex items-center justify-between">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <i class="fas fa-book text-indigo-400"></i> Buku ({{ $bukus->count() }})
                </h3>
                <a href="{{ route('petugas.buku.index') }}?search={{ $keyword }}" class="text-xs text-indigo-400 hover:text-indigo-300 font-medium">Lihat Semua Buku &rarr;</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-800/50 text-xs uppercase text-slate-400">
                        <tr>
                            <th class="px-6 py-3 font-semibold">Judul & Pengarang</th>
                            <th class="px-6 py-3 font-semibold text-center">Kategori</th>
                            <th class="px-6 py-3 font-semibold text-center">Stok</th>
                            <th class="px-6 py-3 font-semibold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700/50 text-sm">
                        @foreach($bukus as $buku)
                        <tr class="hover:bg-slate-800/30 transition">
                            <td class="px-6 py-4">
                                <div class="font-medium text-white">{{ $buku->judul }}</div>
                                <div class="text-xs text-slate-500">{{ $buku->pengarang }}</div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-1 bg-slate-800 rounded text-xs text-slate-300">{{ $buku->kategori->nama_kategori ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="{{ $buku->stok_tersedia > 0 ? 'text-emerald-400' : 'text-red-400' }} font-bold">
                                    {{ $buku->stok_tersedia }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('petugas.buku.edit', ['data_buku' => $buku->id_buku]) }}" class="text-indigo-400 hover:text-indigo-300 text-xs font-medium">Edit</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Section: Anggota -->
        @if($anggotas->count() > 0)
        <div class="bg-[#1e293b] rounded-2xl border border-slate-700/50 overflow-hidden">
            <div class="p-6 border-b border-slate-700/50 flex items-center justify-between">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <i class="fas fa-users text-emerald-400"></i> Anggota ({{ $anggotas->count() }})
                </h3>
                <a href="{{ route('petugas.anggota.index') }}?search_anggota={{ $keyword }}" class="text-xs text-emerald-400 hover:text-emerald-300 font-medium">Lihat Semua Anggota &rarr;</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-800/50 text-xs uppercase text-slate-400">
                        <tr>
                            <th class="px-6 py-3 font-semibold">Nama & NIS</th>
                            <th class="px-6 py-3 font-semibold text-center">Kelas</th>
                            <th class="px-6 py-3 font-semibold text-center">Status</th>
                            <th class="px-6 py-3 font-semibold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700/50 text-sm">
                        @foreach($anggotas as $anggota)
                        <tr class="hover:bg-slate-800/30 transition">
                            <td class="px-6 py-4">
                                <div class="font-medium text-white">{{ $anggota->nama }}</div>
                                <div class="text-xs text-slate-500 font-mono">NIS: {{ $anggota->nis_nisn }}</div>
                            </td>
                            <td class="px-6 py-4 text-center text-slate-300">{{ $anggota->kelas }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $anggota->status == 'aktif' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-red-500/10 text-red-400' }}">
                                    {{ ucfirst($anggota->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('petugas.anggota.edit', ['anggota' => $anggota->id]) }}" class="text-emerald-400 hover:text-emerald-300 text-xs font-medium">Detail</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Section: Transaksi -->
        @if($peminjamans->count() > 0)
        <div class="bg-[#1e293b] rounded-2xl border border-slate-700/50 overflow-hidden">
            <div class="p-6 border-b border-slate-700/50 flex items-center justify-between">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <i class="fas fa-receipt text-amber-400"></i> Transaksi ({{ $peminjamans->count() }})
                </h3>
                <a href="{{ route('petugas.peminjaman.index') }}?search_anggota={{ $keyword }}" class="text-xs text-amber-400 hover:text-amber-300 font-medium">Lihat Semua Transaksi &rarr;</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-800/50 text-xs uppercase text-slate-400">
                        <tr>
                            <th class="px-6 py-3 font-semibold">ID & Anggota</th>
                            <th class="px-6 py-3 font-semibold">Buku</th>
                            <th class="px-6 py-3 font-semibold text-center">Status</th>
                            <th class="px-6 py-3 font-semibold text-center">Tgl Kembali</th>
                            <th class="px-6 py-3 font-semibold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700/50 text-sm">
                        @foreach($peminjamans as $p)
                        <tr class="hover:bg-slate-800/30 transition">
                            <td class="px-6 py-4">
                                <div class="font-mono text-xs text-indigo-400 font-bold">#PMJ-{{ str_pad($p->id_peminjaman, 5, '0', STR_PAD_LEFT) }}</div>
                                <div class="font-medium text-white">{{ $p->anggota->nama ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 text-slate-300">{{ $p->buku->judul ?? 'Buku Dihapus' }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold 
                                    {{ $p->status_peminjaman == 'dipinjam' ? 'bg-amber-500/10 text-amber-400' : '' }}
                                    {{ $p->status_peminjaman == 'dikembalikan' ? 'bg-emerald-500/10 text-emerald-400' : '' }}
                                    {{ $p->status_peminjaman == 'menunggu_konfirmasi' ? 'bg-blue-500/10 text-blue-400' : '' }}
                                ">
                                    {{ ucfirst(str_replace('_', ' ', $p->status_peminjaman)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center text-slate-400 text-xs">{{ $p->tanggal_kembali_rencana->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('petugas.peminjaman.index') }}" class="text-amber-400 hover:text-amber-300 text-xs font-medium">Kelola</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    @endif

</div>
@endsection