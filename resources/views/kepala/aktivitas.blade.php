@extends('layouts.kepala')

@section('title', 'Audit Aktivitas — Perpustakaan Digital')
@section('page-title', 'Audit Aktivitas')
@section('page-subtitle', 'Log transaksi yang diproses oleh petugas')

@section('content')
<div class="space-y-6">

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-[#1e293b] rounded-[32px] p-6 border border-white/5 shadow-xl">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-500/10 rounded-2xl flex items-center justify-center text-blue-400">
                    <i class="fas fa-microchip"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest leading-none mb-1">Total Audit</p>
                    <p class="text-2xl font-black text-white">{{ $activities->total() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Feed -->
    <div class="bg-[#1e293b] rounded-[48px] border border-white/5 shadow-2xl overflow-hidden">
        <div class="p-8 border-b border-white/5 bg-white/2 flex items-center justify-between">
            <h3 class="text-lg font-black text-white">Timeline Transaksi Terarah</h3>
            <div class="flex items-center gap-2 text-xs text-slate-500">
                <i class="fas fa-circle text-[8px] text-emerald-500 animate-pulse"></i>
                Monitoring Real-time
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-white/[0.02] text-[10px] font-black uppercase tracking-widest text-slate-500">
                        <th class="px-8 py-5">Waktu Eksekusi</th>
                        <th class="px-8 py-5">Petugas (Aktor)</th>
                        <th class="px-8 py-5">Aktivitas & Subjek</th>
                        <th class="px-8 py-5">Status Akhir</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @foreach($activities as $item)
                    <tr class="hover:bg-white/[0.02] transition group">
                        <td class="px-8 py-6">
                            <div class="text-sm font-bold text-white">{{ $item->updated_at->format('H:i:s') }}</div>
                            <div class="text-[10px] text-slate-500 font-medium">{{ $item->updated_at->format('d M Y') }}</div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-blue-500/10 flex items-center justify-center text-[10px] font-black text-blue-400">
                                    {{ strtoupper(substr($item->petugas->name ?? '?', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-white leading-tight">{{ $item->petugas->name ?? 'System' }}</div>
                                    <div class="text-[9px] text-slate-600 font-bold uppercase tracking-tighter">Verified Officer</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="text-sm text-slate-200 font-medium">
                                Memproses <span class="text-white font-bold">{{ $item->status_peminjaman }}</span> buku
                            </div>
                            <div class="text-xs text-slate-500 mt-0.5 truncate max-w-xs">
                                "{{ $item->buku->judul ?? '-' }}" oleh {{ $item->anggota->nama ?? '-' }}
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            @if($item->status_peminjaman == 'dikembalikan')
                                <span class="px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-400 text-[10px] font-black uppercase tracking-widest border border-emerald-500/20">Finalized</span>
                            @else
                                <span class="px-3 py-1 rounded-full bg-blue-500/10 text-blue-400 text-[10px] font-black uppercase tracking-widest border border-blue-500/20">{{ $item->status_peminjaman }}</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-8 border-t border-white/5 bg-white/2">
            {{ $activities->links() }}
        </div>
    </div>

</div>
@endsection
