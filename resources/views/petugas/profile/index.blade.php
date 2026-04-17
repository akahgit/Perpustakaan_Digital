@extends('layouts.' . auth()->user()->role)

@section('title', 'Profil & Keamanan — Perpustakaan Digital')
@section('page-title', 'Profil Saya')
@section('page-subtitle', 'Kelola informasi akun dan PIN keamanan')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- 1. INFO USER (LEFT) --}}
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-[#1e293b] rounded-[32px] p-8 border border-white/5 shadow-xl text-center">
                <div class="relative w-24 h-24 mx-auto mb-6">
                    <div class="w-full h-full bg-gradient-to-br from-indigo-500 to-violet-600 rounded-3xl flex items-center justify-center text-white text-3xl font-black shadow-lg shadow-indigo-500/20">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                    <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-emerald-500 border-4 border-[#1e293b] rounded-full flex items-center justify-center text-white text-[10px]">
                        <i class="fas fa-check"></i>
                    </div>
                </div>
                
                <h3 class="text-xl font-black text-white mb-1">{{ auth()->user()->name }}</h3>
                <p class="text-xs text-indigo-400 font-bold uppercase tracking-widest mb-6">Petugas Perpustakaan</p>
                
                <div class="flex flex-col gap-2">
                    <div class="flex items-center justify-between text-xs p-3 bg-white/3 rounded-xl border border-white/5">
                        <span class="text-slate-500">Username</span>
                        <span class="text-white font-bold">{{ auth()->user()->username }}</span>
                    </div>
                    <div class="flex items-center justify-between text-xs p-3 bg-white/3 rounded-xl border border-white/5">
                        <span class="text-slate-500">ID Petugas</span>
                        <span class="text-white font-mono">#{{ str_pad(auth()->id(), 4, '0', STR_PAD_LEFT) }}</span>
                    </div>
                </div>
            </div>

        {{-- 2. AKUN INFO (RIGHT) --}}
        <div class="lg:col-span-2">
            <div class="bg-[#1e293b] rounded-[40px] border border-white/5 shadow-2xl p-8 text-center py-20">
                <div class="w-16 h-16 bg-emerald-500/10 rounded-2xl flex items-center justify-center text-emerald-400 mx-auto mb-6">
                    <i class="fas fa-check-circle text-2xl"></i>
                </div>
                <h3 class="text-xl font-black text-white mb-2">Akun Terverifikasi</h3>
                <p class="text-slate-500 text-sm">Akun petugas Anda aktif dan memiliki akses penuh ke sistem.</p>
            </div>
        </div>

    </div>

</div>
@endsection
