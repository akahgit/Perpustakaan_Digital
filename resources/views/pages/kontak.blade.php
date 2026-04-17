@extends('layouts.app')

@section('title', 'Hubungi Kami — Perpustakaan Digital')

@section('content')
<div class="py-12 px-6">
    <div class="max-w-5xl mx-auto">
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            
            {{-- Text Content --}}
            <div class="space-y-8 animate-fade-in-left">
                <div>
                    <h4 class="text-indigo-500 font-bold uppercase tracking-[0.2em] text-xs mb-3">Contact Support</h4>
                    <h1 class="text-4xl md:text-5xl font-black text-white leading-tight mb-6">
                        Ada Pertanyaan? <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-violet-400">Kami Siap Membantu.</span>
                    </h1>
                    <p class="text-slate-400 leading-relaxed max-w-md italic">
                        "Buku adalah jendela dunia, dan kami adalah penjaganya. Jika Anda mengalami kesulitan dalam peminjaman atau memiliki saran, jangan ragu untuk menghubungi kami."
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-4">
                    <div class="p-6 bg-white/3 border border-white/5 rounded-[32px] hover:border-indigo-500/30 transition-all group">
                        <div class="w-12 h-12 bg-indigo-500/10 rounded-2xl flex items-center justify-center text-indigo-400 mb-4 group-hover:bg-indigo-500 group-hover:text-white transition-all">
                            <i class="fas fa-envelope text-xl"></i>
                        </div>
                        <h4 class="text-white font-bold mb-1">Email Resmi</h4>
                        <p class="text-xs text-slate-500">support@perpusdig.site</p>
                    </div>
                    <div class="p-6 bg-white/3 border border-white/5 rounded-[32px] hover:border-emerald-500/30 transition-all group">
                        <div class="w-12 h-12 bg-emerald-500/10 rounded-2xl flex items-center justify-center text-emerald-400 mb-4 group-hover:bg-emerald-500 group-hover:text-white transition-all">
                            <i class="fas fa-phone text-xl"></i>
                        </div>
                        <h4 class="text-white font-bold mb-1">Layanan Telp</h4>
                        <p class="text-xs text-slate-500">(021) 1234 5678</p>
                    </div>
                </div>
            </div>

            {{-- Contact Form --}}
            <div class="animate-fade-in-right">
                <div class="relative">
                    <div class="absolute -inset-1 bg-gradient-to-r from-indigo-500 to-violet-600 rounded-[42px] blur opacity-20"></div>
                    <div class="relative bg-[#1e293b] border border-white/10 rounded-[40px] p-8 md:p-10 shadow-2xl">
                        
                        <form action="{{ route('kontak.store') }}" method="POST" class="space-y-6">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Nama Lengkap</label>
                                    <input type="text" value="{{ auth()->user()->name }}" disabled
                                           class="w-full bg-black/20 border border-white/5 rounded-2xl px-5 py-4 text-slate-400 text-sm outline-none cursor-not-allowed">
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Email</label>
                                    <input type="email" value="{{ auth()->user()->email }}" disabled
                                           class="w-full bg-black/20 border border-white/5 rounded-2xl px-5 py-4 text-slate-400 text-sm outline-none cursor-not-allowed">
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Subjek Pesan</label>
                                <input type="text" name="subjek" required
                                       class="w-full bg-black/20 border border-white/5 rounded-2xl px-5 py-4 text-white text-sm focus:border-indigo-500 outline-none transition-all"
                                       placeholder="Judul keluhan atau pertanyaan...">
                            </div>

                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Isi Pesan</label>
                                <textarea name="pesan" rows="5" required
                                          class="w-full bg-black/20 border border-white/5 rounded-2xl px-5 py-4 text-white text-sm focus:border-indigo-500 outline-none transition-all resize-none"
                                          placeholder="Ceritakan detail masalah atau feedback Anda..."></textarea>
                            </div>

                            <button type="submit" 
                                    class="w-full py-5 bg-indigo-600 hover:bg-indigo-500 text-white font-black rounded-2xl shadow-xl shadow-indigo-500/25 transition-all hover:-translate-y-1 flex items-center justify-center gap-3">
                                <span>Kirim Pesan Sekarang</span>
                                <i class="fas fa-paper-plane text-xs opacity-50"></i>
                            </button>
                        </form>

                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection
