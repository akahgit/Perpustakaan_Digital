@extends('layouts.app')

@section('title', 'Profil Saya — Perpustakaan Digital')

@section('content')
<div class="max-w-5xl mx-auto animate-fade-in-down" x-data="{ activeTab: 'info' }">
    
    <div class="flex flex-col md:flex-row gap-8 items-start">
        
        {{-- Sidebar: Profile Overview --}}
        <div class="w-full md:w-80 space-y-6">
            <div class="bg-[#1e293b] rounded-3xl border border-white/5 shadow-xl overflow-hidden">
                <div class="h-24 bg-gradient-to-br from-indigo-500 via-violet-600 to-slate-900"></div>
                
                <div class="px-6 pb-6 text-center">
                    <div class="w-24 h-24 rounded-2xl border-4 border-[#1e293b] bg-gradient-to-br from-indigo-500 to-violet-600 mx-auto -mt-12 mb-4 flex items-center justify-center shadow-xl">
                        <span class="text-3xl font-extrabold text-white">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
                    </div>
                    
                    <h2 class="text-xl font-extrabold text-white tracking-tight">{{ auth()->user()->name }}</h2>
                    <p class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest mt-1">{{ auth()->user()->role }}</p>
                    
                    <div class="mt-6 flex flex-col gap-2">
                        <button @click="activeTab = 'info'" 
                                :class="activeTab === 'info' ? 'bg-white/10 text-white' : 'text-slate-400 hover:text-white hover:bg-white/5'"
                                class="w-full px-4 py-2.5 rounded-xl text-sm font-semibold transition flex items-center gap-3">
                            <i class="fas fa-id-card text-xs"></i> Informasi Profil
                        </button>
                        <button @click="activeTab = 'password'"
                                :class="activeTab === 'password' ? 'bg-white/10 text-white' : 'text-slate-400 hover:text-white hover:bg-white/5'"
                                class="w-full px-4 py-2.5 rounded-xl text-sm font-semibold transition flex items-center gap-3">
                            <i class="fas fa-key text-xs"></i> Keamanan Akun
                        </button>
                    </div>
                </div>
            </div>

            @if(auth()->user()->role === 'anggota' && isset($anggota->id))
            <div class="bg-[#1e293b] rounded-2xl border border-white/5 shadow-xl p-5">
                <h3 class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-4">Ringkasan Aktivitas</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-400">Peminjaman Aktif</span>
                        <span class="text-sm font-bold text-white">{{ \App\Models\Peminjaman::where('id_anggota', $anggota->id)->whereIn('status_peminjaman', ['dipinjam', 'terlambat'])->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-400">Total Riwayat</span>
                        <span class="text-sm font-bold text-white">{{ \App\Models\Peminjaman::where('id_anggota', $anggota->id)->count() }}</span>
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{-- Main Content --}}
        <div class="flex-1 w-full space-y-6">
            
            {{-- TAB: Info Profil --}}
            <div x-show="activeTab === 'info'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4">
                <div class="bg-[#1e293b] rounded-3xl border border-white/5 shadow-xl overflow-hidden">
                    <div class="px-8 py-6 border-b border-white/5 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-white">Informasi Profil</h3>
                        <i class="fas fa-user-pen text-slate-700"></i>
                    </div>

                    <form action="{{ route('profil.update') }}" method="POST" class="p-8 space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest ml-1">Nama Lengkap</label>
                                <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required
                                       class="w-full px-4 py-3 bg-white/3 border border-white/5 rounded-2xl text-slate-200 focus:outline-none focus:border-indigo-500/50 transition">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest ml-1">Email</label>
                                <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required
                                       class="w-full px-4 py-3 bg-white/3 border border-white/5 rounded-2xl text-slate-200 focus:outline-none focus:border-indigo-500/50 transition">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest ml-1">No. Telepon</label>
                                <input type="text" name="no_telepon" value="{{ old('no_telepon', $anggota->no_telepon ?? '') }}"
                                       class="w-full px-4 py-3 bg-white/3 border border-white/5 rounded-2xl text-slate-200 focus:outline-none focus:border-indigo-500/50 transition"
                                       placeholder="mis: 08123456789">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest ml-1">NIS / NISN</label>
                                <div class="w-full px-4 py-3 bg-white/5 border border-white/5 rounded-2xl text-slate-500 font-mono text-sm flex items-center gap-2">
                                    <i class="fas fa-lock text-[10px]"></i> {{ $anggota->nis_nisn ?? 'BUKAN ANGGOTA' }}
                                </div>
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest ml-1">Alamat Tinggal</label>
                            <textarea name="alamat" rows="3"
                                      class="w-full px-4 py-3 bg-white/3 border border-white/5 rounded-2xl text-slate-200 focus:outline-none focus:border-indigo-500/50 transition resize-none">{{ old('alamat', $anggota->alamat ?? '') }}</textarea>
                        </div>

                        <div class="flex justify-end border-t border-white/5 pt-6">
                            <button type="submit" class="px-8 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-sm rounded-2xl shadow-xl shadow-indigo-500/25 transition">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- TAB: Password --}}
            <div x-show="activeTab === 'password'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4">
                <div class="bg-[#1e293b] rounded-3xl border border-white/5 shadow-xl overflow-hidden">
                    <div class="px-8 py-6 border-b border-white/5 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-white">Ganti Password</h3>
                        <i class="fas fa-key text-slate-700"></i>
                    </div>

                    <form action="{{ route('profil.password') }}" method="POST" class="p-8 space-y-6">
                        @csrf
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest ml-1">Password Saat Ini</label>
                            <input type="password" name="current_password" required
                                   class="w-full px-4 py-3 bg-white/3 border border-white/5 rounded-2xl text-slate-200 focus:outline-none focus:border-indigo-500/50 transition">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest ml-1">Password Baru</label>
                                <input type="password" name="password" required
                                       class="w-full px-4 py-3 bg-white/3 border border-white/5 rounded-2xl text-slate-200 focus:outline-none focus:border-indigo-500/50 transition">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest ml-1">Konfirmasi Password Baru</label>
                                <input type="password" name="password_confirmation" required
                                       class="w-full px-4 py-3 bg-white/3 border border-white/5 rounded-2xl text-slate-200 focus:outline-none focus:border-indigo-500/50 transition">
                            </div>
                        </div>

                        <div class="flex justify-end border-t border-white/5 pt-6">
                            <button type="submit" class="px-8 py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm rounded-2xl shadow-xl shadow-emerald-500/25 transition">
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection