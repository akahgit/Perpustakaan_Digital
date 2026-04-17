@extends('layouts.petugas')

@section('title', 'Kotak Masuk — Petugas')
@section('page-title', 'Pesan Anggota')
@section('page-subtitle', 'Daftar masukan, pertanyaan, dan keluhan dari anggota')

@section('content')
<div class="space-y-6">

    <div class="bg-[#1e293b] rounded-[32px] border border-white/5 shadow-xl overflow-hidden">
        
        {{-- Header & Stats --}}
        <div class="px-8 py-6 border-b border-white/5 bg-white/2 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-indigo-500/10 rounded-2xl flex items-center justify-center text-indigo-400">
                    <i class="fas fa-inbox text-xl"></i>
                </div>
                <div>
                    <h3 class="text-white font-black">Kotak Masuk</h3>
                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">{{ $contacts->total() }} Total Pesan Terdaftar</p>
                </div>
            </div>
            
            <div class="flex gap-3">
                <span class="px-4 py-2 bg-amber-500/10 border border-amber-500/20 text-amber-400 text-xs font-bold rounded-xl flex items-center gap-2">
                    <i class="fas fa-circle text-[6px]"></i>
                    {{ $contacts->where('status', 'unread')->count() }} Belum Dibaca
                </span>
            </div>
        </div>

        {{-- Messages List --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-black/20 border-b border-white/5">
                        <th class="px-8 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest w-16 text-center">#</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest">Pengirim</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest">Subjek & Pesan</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest text-center">Tgl Kirim</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($contacts as $index => $contact)
                    <tr class="hover:bg-white/2 transition-all group {{ $contact->status === 'unread' ? 'bg-indigo-500/5' : '' }}">
                        <td class="px-8 py-6 text-center text-xs text-slate-600 font-bold">
                            {{ $contacts->firstItem() + $index }}
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-500/20 to-violet-600/20 border border-white/10 flex items-center justify-center text-white text-xs font-bold">
                                    {{ strtoupper(substr($contact->nama, 0, 2)) }}
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-white mb-0.5">{{ $contact->nama }}</div>
                                    <div class="text-[10px] text-indigo-400 font-medium">{{ $contact->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6 max-w-md">
                            <div class="inline-flex items-center gap-2 mb-1">
                                <h4 class="text-sm font-bold text-slate-200">{{ $contact->subjek }}</h4>
                                @if($contact->status === 'unread')
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                @endif
                            </div>
                            <p class="text-xs text-slate-500 leading-relaxed line-clamp-2 italic">"{{ $contact->pesan }}"</p>
                        </td>
                        <td class="px-8 py-6 text-center">
                            <div class="text-[10px] text-slate-400 font-bold mb-1">{{ $contact->created_at->format('d M Y') }}</div>
                            <div class="text-[9px] text-slate-600 tabular-nums uppercase">{{ $contact->created_at->format('H:i') }} WIB</div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center justify-center gap-2 opacity-100 group-hover:opacity-100 transition-opacity">
                                @if($contact->status === 'unread')
                                    {{-- Tandai Selesai/Baca --}}
                                    <form action="{{ route('petugas.kontak.read', $contact->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="w-8 h-8 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 hover:bg-emerald-500 hover:text-white flex items-center justify-center transition-all" title="Tandai Sudah Dibaca">
                                            <i class="fas fa-check text-[10px]"></i>
                                        </button>
                                    </form>
                                @endif

                                {{-- Hapus --}}
                                <button type="button" 
                                        @click="window.dispatchEvent(new CustomEvent('confirm', { 
                                            detail: {
                                                title: 'Hapus Pesan?',
                                                message: 'Pesan dari <strong>{{ addslashes($contact->nama) }}</strong> akan dihapus permanen.',
                                                action: '{{ route('petugas.kontak.destroy', $contact->id) }}',
                                                method: 'DELETE',
                                                type: 'danger',
                                                confirmText: 'Ya, Hapus'
                                            }
                                        }))"
                                        class="w-8 h-8 rounded-lg bg-rose-500/10 border border-rose-500/20 text-rose-400 hover:bg-rose-500 hover:text-white flex items-center justify-center transition-all" title="Hapus">
                                    <i class="fas fa-trash-alt text-[10px]"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-20 h-20 bg-white/3 rounded-[32px] flex items-center justify-center mb-6">
                                    <i class="fas fa-comment-slash text-slate-600 text-3xl"></i>
                                </div>
                                <h4 class="text-white font-bold mb-2">Belum Ada Pesan</h4>
                                <p class="text-xs text-slate-500">Saat anggota mengirim pesan melalui halaman kontak, pesan akan muncul di sini.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($contacts->hasPages())
        <div class="px-8 py-6 border-t border-white/5 bg-white/2">
            {{ $contacts->links() }}
        </div>
        @endif

    </div>

</div>
@endsection
