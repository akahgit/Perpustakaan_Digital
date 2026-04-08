@extends('layouts.petugas')

@section('title', 'Edit Anggota')
@section('page-title', 'Edit Data Anggota')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-xl font-bold text-white">Edit Profil Anggota</h2>
            <p class="text-sm text-slate-400">Mengedit data: <span class="text-indigo-400 font-semibold">{{ $anggota->nama }}</span> ({{ $anggota->nis_nisn }})</p>
        </div>
        <a href="{{ route('petugas.anggota.index') }}" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white text-sm font-medium rounded-xl transition flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <form action="{{ route('petugas.anggota.update', $anggota->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- BAGIAN 1: DATA PRIBADI ANGGOTA -->
        <div class="bg-[#1e293b] rounded-2xl border border-slate-700/50 p-8 shadow-xl">
            <div class="flex items-center gap-3 mb-6 border-b border-slate-700 pb-4">
                <div class="w-10 h-10 rounded-lg bg-indigo-500/20 flex items-center justify-center text-indigo-400">
                    <i class="fas fa-user text-lg"></i>
                </div>
                <h3 class="text-lg font-bold text-white">Data Pribadi</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Lengkap -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-300 mb-2">Nama Lengkap <span class="text-red-400">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama', $anggota->nama) }}" required 
                           class="w-full bg-slate-800/50 border border-slate-600 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-indigo-500 @error('nama') border-red-500 @enderror">
                    @error('nama') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>

                <!-- NIS/NISN -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">NIS / NISN <span class="text-red-400">*</span></label>
                    <input type="text" name="nis_nisn" value="{{ old('nis_nisn', $anggota->nis_nisn) }}" required 
                           class="w-full bg-slate-800/50 border border-slate-600 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-indigo-500 @error('nis_nisn') border-red-500 @enderror">
                    @error('nis_nisn') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>

                <!-- Kelas -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Kelas / Jurusan <span class="text-red-400">*</span></label>
                    <input type="text" name="kelas" value="{{ old('kelas', $anggota->kelas) }}" required 
                           class="w-full bg-slate-800/50 border border-slate-600 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-indigo-500 @error('kelas') border-red-500 @enderror">
                    @error('kelas') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>

                <!-- Jenis Kelamin -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Jenis Kelamin <span class="text-red-400">*</span></label>
                    <select name="jenis_kelamin" required class="w-full bg-slate-800/50 border border-slate-600 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-indigo-500 @error('jenis_kelamin') border-red-500 @enderror">
                        <option value="">-- Pilih --</option>
                        <option value="L" {{ old('jenis_kelamin', $anggota->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin', $anggota->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>

                <!-- Tanggal Bergabung -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Tanggal Bergabung <span class="text-red-400">*</span></label>
                    <input type="date" name="tanggal_bergabung" value="{{ old('tanggal_bergabung', $anggota->tanggal_bergabung->format('Y-m-d')) }}" required 
                           class="w-full bg-slate-800/50 border border-slate-600 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-indigo-500 @error('tanggal_bergabung') border-red-500 @enderror">
                    @error('tanggal_bergabung') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>

                <!-- No Telepon -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-300 mb-2">No. Telepon / WhatsApp (Opsional)</label>
                    <input type="text" name="no_telepon" value="{{ old('no_telepon', $anggota->no_telepon) }}" 
                           class="w-full bg-slate-800/50 border border-slate-600 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-indigo-500 @error('no_telepon') border-red-500 @enderror">
                    @error('no_telepon') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>

                <!-- Alamat -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-300 mb-2">Alamat Lengkap (Opsional)</label>
                    <textarea name="alamat" rows="3" 
                              class="w-full bg-slate-800/50 border border-slate-600 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-indigo-500 @error('alamat') border-red-500 @enderror">{{ old('alamat', $anggota->alamat) }}</textarea>
                    @error('alamat') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <!-- BAGIAN 2: DATA AKUN LOGIN -->
        <div class="bg-[#1e293b] rounded-2xl border border-slate-700/50 p-8 shadow-xl">
            <div class="flex items-center gap-3 mb-6 border-b border-slate-700 pb-4">
                <div class="w-10 h-10 rounded-lg bg-emerald-500/20 flex items-center justify-center text-emerald-400">
                    <i class="fas fa-lock text-lg"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-white">Akun Login Anggota</h3>
                    <p class="text-xs text-slate-400">Ubah username/email atau ganti password jika diperlukan</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Email -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-300 mb-2">Email Aktif <span class="text-red-400">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $anggota->email) }}" required 
                           class="w-full bg-slate-800/50 border border-slate-600 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-emerald-500 @error('email') border-red-500 @enderror">
                    @error('email') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>

                <!-- Username -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Username Login <span class="text-red-400">*</span></label>
                    <input type="text" name="username" value="{{ old('username', $anggota->user->username ?? '') }}" required 
                           class="w-full bg-slate-800/50 border border-slate-600 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-emerald-500 @error('username') border-red-500 @enderror">
                    @error('username') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>

                <!-- Ganti Password (Opsional) -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Ganti Password (Opsional)</label>
                    <input type="password" name="password" 
                           class="w-full bg-slate-800/50 border border-slate-600 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-emerald-500 @error('password') border-red-500 @enderror"
                           placeholder="Kosongkan jika tidak ingin mengubah">
                    <p class="text-xs text-amber-400 mt-1"><i class="fas fa-info-circle"></i> Isi hanya jika ingin mereset password anggota.</p>
                    @error('password') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>

                <!-- Konfirmasi Password Baru -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-300 mb-2">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" 
                           class="w-full bg-slate-800/50 border border-slate-600 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-emerald-500">
                    <p class="text-xs text-slate-500 mt-1">Ketik ulang password baru yang sama.</p>
                </div>
            </div>
        </div>

        <!-- BAGIAN 3: STATUS & SUBMIT -->
        <div class="bg-[#1e293b] rounded-2xl border border-slate-700/50 p-6 shadow-xl flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="w-full md:w-auto">
                <label class="block text-sm font-medium text-slate-300 mb-2">Status Keanggotaan</label>
                <select name="status" required class="w-full md:w-48 bg-slate-800/50 border border-slate-600 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-indigo-500">
                    <option value="aktif" {{ old('status', $anggota->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="non-aktif" {{ old('status', $anggota->status) == 'non-aktif' ? 'selected' : '' }}>Non-Aktif</option>
                </select>
            </div>

            <div class="flex gap-4 w-full md:w-auto justify-end">
                <a href="{{ route('petugas.anggota.index') }}" class="px-6 py-3 rounded-xl border border-slate-600 text-slate-300 hover:bg-slate-800 transition font-medium">
                    Batal
                </a>
                <button type="submit" class="px-8 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/30 transition flex items-center gap-2">
                    <i class="fas fa-save"></i> Update Data
                </button>
            </div>
        </div>
    </form>
</div>
@endsection