@extends('layouts.kepala')

@section('title', 'Manajemen Petugas — Perpustakaan Digital')
@section('page-title', 'Daftar Petugas')
@section('page-subtitle', 'Kelola akun operasional perpustakaan')

@section('content')
<div class="space-y-6" x-data="{ addModal: false }">

    {{-- Flash Messages --}}
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         class="flex items-center gap-3 px-6 py-4 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 rounded-2xl text-sm font-semibold">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         class="flex items-center gap-3 px-6 py-4 bg-rose-500/10 border border-rose-500/30 text-rose-400 rounded-2xl text-sm font-semibold">
        <i class="fas fa-exclamation-circle"></i>
        {{ session('error') }}
    </div>
    @endif

    <!-- Header Action -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-black text-white">Manajemen Akun Petugas</h2>
            <p class="text-slate-500 text-sm">
                Total {{ $petugas->count() }} petugas &mdash;
                <span class="text-emerald-400 font-semibold">{{ $petugas->where('status', 'aktif')->count() }} aktif</span>,
                <span class="text-rose-400 font-semibold">{{ $petugas->where('status', 'non-aktif')->count() }} non-aktif</span>.
            </p>
        </div>
        <button @click="addModal = true"
                class="px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white text-xs font-black uppercase tracking-widest rounded-2xl shadow-xl shadow-blue-500/25 transition-all active:scale-95 flex items-center gap-3">
            <i class="fas fa-plus"></i> Tambah Petugas Baru
        </button>
    </div>

    <!-- Grid Petugas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($petugas as $p)
        @php $isAktif = $p->status !== 'non-aktif'; @endphp
        <div class="bg-[#1e293b] rounded-[32px] border {{ $isAktif ? 'border-white/5 hover:border-blue-500/30' : 'border-rose-500/20 hover:border-rose-500/40' }} p-8 relative overflow-hidden group shadow-xl transition-all duration-300">
            {{-- Decorative blur --}}
            <div class="absolute top-0 right-0 w-32 h-32 {{ $isAktif ? 'bg-blue-500/5 group-hover:bg-blue-500/10' : 'bg-rose-500/5 group-hover:bg-rose-500/10' }} rounded-full blur-3xl -mr-16 -mt-16 transition"></div>

            <div class="relative z-10">
                {{-- Avatar + Info --}}
                <div class="flex items-start justify-between gap-4 mb-6">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 {{ $isAktif ? 'bg-gradient-to-br from-blue-500 to-indigo-600 shadow-blue-500/20' : 'bg-gradient-to-br from-slate-600 to-slate-700 shadow-slate-800/20' }} rounded-2xl flex items-center justify-center text-white text-xl font-black shadow-lg">
                            {{ strtoupper(substr($p->name, 0, 1)) }}
                        </div>
                        <div>
                            <h3 class="text-lg font-bold {{ $isAktif ? 'text-white' : 'text-slate-400' }} leading-tight">{{ $p->name }}</h3>
                            <p class="text-xs {{ $isAktif ? 'text-blue-400' : 'text-slate-600' }} font-bold uppercase tracking-widest">{{ $p->username }}</p>
                        </div>
                    </div>
                    {{-- Status Badge --}}
                    <span class="shrink-0 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $isAktif ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/30' : 'bg-rose-500/15 text-rose-400 border border-rose-500/30' }}">
                        {{ $isAktif ? 'Aktif' : 'Non-Aktif' }}
                    </span>
                </div>

                {{-- Details --}}
                <div class="space-y-3 mb-8">
                    <div class="flex items-center gap-3 text-xs">
                        <i class="fas fa-envelope text-slate-600 w-4"></i>
                        <span class="text-slate-400">{{ $p->email }}</span>
                    </div>
                    <div class="flex items-center gap-3 text-xs">
                        <i class="fas fa-calendar-alt text-slate-600 w-4"></i>
                        <span class="text-slate-500">Terdaftar sejak {{ $p->created_at->format('d M Y') }}</span>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center gap-2 pt-6 border-t border-white/5">

                    {{-- Toggle Status --}}
                    <form action="{{ route('kepala.petugas.toggle-status', $p->id) }}" method="POST" class="flex-shrink-0">
                        @csrf
                        <button type="submit"
                                onclick="return confirm('{{ $isAktif ? 'Nonaktifkan akun petugas ini? Mereka tidak akan bisa login.' : 'Aktifkan kembali akun petugas ini?' }}')"
                                class="w-10 h-10 {{ $isAktif ? 'bg-emerald-500/10 hover:bg-rose-500/20 text-emerald-400 hover:text-rose-400' : 'bg-rose-500/10 hover:bg-emerald-500/20 text-rose-400 hover:text-emerald-400' }} rounded-xl transition-all flex items-center justify-center"
                                title="{{ $isAktif ? 'Nonaktifkan akun' : 'Aktifkan akun' }}">
                            <i class="fas {{ $isAktif ? 'fa-toggle-on' : 'fa-toggle-off' }} text-sm"></i>
                        </button>
                    </form>

                    {{-- Hapus --}}
                    <form action="{{ route('kepala.petugas.destroy', $p->id) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit"
                                onclick="return confirm('Hapus akun petugas ini secara permanen?')"
                                class="w-10 h-10 bg-rose-500/10 hover:bg-rose-500/20 text-rose-500 rounded-xl transition flex items-center justify-center">
                            <i class="fas fa-trash-alt text-xs"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @if($petugas->isEmpty())
    <div class="py-20 text-center bg-[#1e293b] rounded-[48px] border border-dashed border-white/10">
        <div class="w-20 h-20 bg-white/5 rounded-3xl flex items-center justify-center mx-auto mb-6 text-slate-700">
            <i class="fas fa-user-slash text-3xl"></i>
        </div>
        <h3 class="text-xl font-bold text-white mb-2">Belum ada petugas</h3>
        <p class="text-slate-500 text-sm max-w-xs mx-auto">Silakan tambahkan petugas pertama untuk mengelola operasional harian.</p>
    </div>
    @endif

    <!-- MODAL TAMBAH PETUGAS -->
    <template x-if="addModal">
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-6">
            <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" @click="addModal = false"></div>

            <div class="relative bg-[#1e293b] border border-white/10 rounded-[40px] w-full max-w-lg overflow-hidden shadow-2xl">
                <div class="p-8 border-b border-white/5 bg-white/2 flex items-center justify-between">
                    <h3 class="text-xl font-black text-white">Tambah Petugas</h3>
                    <button @click="addModal = false" class="w-10 h-10 rounded-xl bg-white/5 text-slate-400 hover:text-white transition flex items-center justify-center">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form action="{{ route('kepala.petugas.store') }}" method="POST" class="p-10 space-y-6">
                    @csrf

                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Nama Lengkap</label>
                        <input type="text" name="name" required value="{{ old('name') }}"
                               class="w-full bg-black/40 border border-white/10 rounded-2xl px-6 py-4 text-white focus:border-blue-500 outline-none transition">
                        @error('name') <p class="text-rose-400 text-xs mt-1 ml-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Username</label>
                            <input type="text" name="username" required value="{{ old('username') }}"
                                   class="w-full bg-black/40 border border-white/10 rounded-2xl px-6 py-4 text-white focus:border-blue-500 outline-none transition">
                            @error('username') <p class="text-rose-400 text-xs mt-1 ml-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Email</label>
                            <input type="email" name="email" required value="{{ old('email') }}"
                                   class="w-full bg-black/40 border border-white/10 rounded-2xl px-6 py-4 text-white focus:border-blue-500 outline-none transition">
                            @error('email') <p class="text-rose-400 text-xs mt-1 ml-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Password</label>
                            <input type="password" name="password" required
                                   class="w-full bg-black/40 border border-white/10 rounded-2xl px-6 py-4 text-white focus:border-blue-500 outline-none transition">
                            @error('password') <p class="text-rose-400 text-xs mt-1 ml-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" required
                                   class="w-full bg-black/40 border border-white/10 rounded-2xl px-6 py-4 text-white focus:border-blue-500 outline-none transition">
                        </div>
                    </div>

                    <div class="pt-6">
                        <button type="submit" class="w-full py-5 bg-blue-600 hover:bg-blue-500 text-white font-black text-xs uppercase tracking-[0.2em] rounded-2xl shadow-xl shadow-blue-500/25 transition-all">
                            Daftarkan Petugas Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>

</div>
@endsection
