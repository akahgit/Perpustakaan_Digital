@extends('layouts.guest')

@section('title', 'Daftar - Perpustakaan Digital')

@section('content')
    <div class="text-center mb-6">
        <!-- Logo Icon -->
        <div
            class="inline-flex items-center justify-center w-16 h-16 bg-blue-600/10 rounded-2xl mb-4 border border-blue-500/20 shadow-lg shadow-blue-500/10">
            <i class="fas fa-book-open text-3xl text-blue-500"></i>
        </div>
        <h2 class="text-2xl font-bold text-white mb-1">Perpustakaan Digital</h2>
        <p class="text-gray-400 text-sm">Buat akun baru untuk mengakses perpustakaan</p>
    </div>

    <!-- Register Card -->
    <div class="bg-[#0f172a]/80 backdrop-blur-xl border border-white/10 rounded-2xl p-8 shadow-2xl">
        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <!-- Nama Lengkap -->
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5 ml-1">Nama Lengkap</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fas fa-user text-gray-500"></i>
                    </div>
                    <input type="text" name="nama" value="{{ old('nama') }}" required
                        class="w-full pl-11 pr-4 py-3 bg-[#1e293b]/50 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition"
                        placeholder="Masukkan nama lengkap">
                </div>
                @error('nama')
                    <p class="mt-1 text-xs text-red-400 ml-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- NIS -->
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5 ml-1">NIS (Nomor Induk Siswa)</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fas fa-id-card text-gray-500"></i>
                    </div>
                    <input type="text" name="nis_nisn" value="{{ old('nis_nisn') }}" required
                        class="w-full pl-11 pr-4 py-3 bg-[#1e293b]/50 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition"
                        placeholder="Masukkan NIS">
                </div>
                @error('nis_nisn')
                    <p class="mt-1 text-xs text-red-400 ml-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Kelas (Dropdown) -->
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5 ml-1">Kelas</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fas fa-graduation-cap text-gray-500"></i>
                    </div>
                    <select name="kelas" required
                        class="w-full pl-11 pr-4 py-3 bg-[#1e293b]/50 border border-gray-700 rounded-xl text-white focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition appearance-none cursor-pointer">
                        <option value="" selected disabled>Pilih jurusan & kelas</option>

                        <!-- KELAS X (FASE E) -->
                        <optgroup label="Kelas X">
                            <option value="X TKJ 1" {{ old('kelas') == 'X TKJ 1' ? 'selected' : '' }}>X TKJ 1 (Teknik
                                Komputer Jaringan)</option>
                            <option value="X TKJ 2" {{ old('kelas') == 'X TKJ 2' ? 'selected' : '' }}>X TKJ 2 (Teknik
                                Komputer Jaringan)</option>
                            <option value="X RPL 1" {{ old('kelas') == 'X RPL 1' ? 'selected' : '' }}>X RPL 1 (Rekayasa
                                Perangkat Lunak)</option>
                            <option value="X RPL 2" {{ old('kelas') == 'X RPL 2' ? 'selected' : '' }}>X RPL 2 (Rekayasa
                                Perangkat Lunak)</option>
                            <option value="X AKL 1" {{ old('kelas') == 'X AKL 1' ? 'selected' : '' }}>X AKL 1 (Akuntansi)
                            </option>
                            <option value="X OTKP 1" {{ old('kelas') == 'X OTKP 1' ? 'selected' : '' }}>X OTKP 1
                                (Perkantoran)</option>
                            <option value="X TKR 1" {{ old('kelas') == 'X TKR 1' ? 'selected' : '' }}>X TKR 1 (Teknik
                                Kendaraan Ringan)</option>
                            <option value="X TBSM 1" {{ old('kelas') == 'X TBSM 1' ? 'selected' : '' }}>X TBSM 1 (Sepeda
                                Motor)</option>
                        </optgroup>

                        <!-- KELAS XI (FASE F) -->
                        <optgroup label="Kelas XI">
                            <option value="XI TKJ 1" {{ old('kelas') == 'XI TKJ 1' ? 'selected' : '' }}>XI TKJ 1</option>
                            <option value="XI TKJ 2" {{ old('kelas') == 'XI TKJ 2' ? 'selected' : '' }}>XI TKJ 2</option>
                            <option value="XI RPL 1" {{ old('kelas') == 'XI RPL 1' ? 'selected' : '' }}>XI RPL 1</option>
                            <option value="XI RPL 2" {{ old('kelas') == 'XI RPL 2' ? 'selected' : '' }}>XI RPL 2</option>
                            <option value="XI AKL 1" {{ old('kelas') == 'XI AKL 1' ? 'selected' : '' }}>XI AKL 1</option>
                            <option value="XI OTKP 1" {{ old('kelas') == 'XI OTKP 1' ? 'selected' : '' }}>XI OTKP 1
                            </option>
                            <option value="XI TKR 1" {{ old('kelas') == 'XI TKR 1' ? 'selected' : '' }}>XI TKR 1</option>
                            <option value="XI TBSM 1" {{ old('kelas') == 'XI TBSM 1' ? 'selected' : '' }}>XI TBSM 1
                            </option>
                        </optgroup>

                        <!-- KELAS XII (FASE F) -->
                        <optgroup label="Kelas XII">
                            <option value="XII TKJ 1" {{ old('kelas') == 'XII TKJ 1' ? 'selected' : '' }}>XII TKJ 1
                            </option>
                            <option value="XII TKJ 2" {{ old('kelas') == 'XII TKJ 2' ? 'selected' : '' }}>XII TKJ 2
                            </option>
                            <option value="XII RPL 1" {{ old('kelas') == 'XII RPL 1' ? 'selected' : '' }}>XII RPL 1
                            </option>
                            <option value="XII RPL 2" {{ old('kelas') == 'XII RPL 2' ? 'selected' : '' }}>XII RPL 2
                            </option>
                            <option value="XII AKL 1" {{ old('kelas') == 'XII AKL 1' ? 'selected' : '' }}>XII AKL 1
                            </option>
                            <option value="XII OTKP 1" {{ old('kelas') == 'XII OTKP 1' ? 'selected' : '' }}>XII OTKP 1
                            </option>
                            <option value="XII TKR 1" {{ old('kelas') == 'XII TKR 1' ? 'selected' : '' }}>XII TKR 1
                            </option>
                            <option value="XII TBSM 1" {{ old('kelas') == 'XII TBSM 1' ? 'selected' : '' }}>XII TBSM 1
                            </option>
                        </optgroup>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                        <i class="fas fa-chevron-down text-xs text-gray-500"></i>
                    </div>
                </div>
                @error('kelas')
                    <p class="mt-1 text-xs text-red-400 ml-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5 ml-1">Email</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fas fa-envelope text-gray-500"></i>
                    </div>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full pl-11 pr-4 py-3 bg-[#1e293b]/50 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition"
                        placeholder="contoh@email.com">
                </div>
                @error('email')
                    <p class="mt-1 text-xs text-red-400 ml-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5 ml-1">Kata Sandi</label>
                <div class="relative" x-data="{ show: false }">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-gray-500"></i>
                    </div>
                    <input :type="show ? 'text' : 'password'" name="password" required minlength="8"
                        class="w-full pl-11 pr-12 py-3 bg-[#1e293b]/50 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition"
                        placeholder="Minimal 8 karakter">
                    <button type="button" @click="show = !show"
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-500 hover:text-gray-300">
                        <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                    </button>
                </div>
                <p class="mt-1 text-xs text-gray-500 ml-1">Harus mengandung huruf besar dan angka</p>
                @error('password')
                    <p class="mt-1 text-xs text-red-400 ml-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5 ml-1">Konfirmasi Kata Sandi</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-gray-500"></i>
                    </div>
                    <input type="password" name="password_confirmation" required
                        class="w-full pl-11 pr-4 py-3 bg-[#1e293b]/50 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition"
                        placeholder="Ulangi kata sandi">
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit"
                class="w-full py-3.5 px-4 bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-500 hover:to-cyan-400 text-white font-bold rounded-xl shadow-lg shadow-blue-500/25 transition transform hover:scale-[1.02] active:scale-[0.98] mt-2">
                Daftar Sekarang
            </button>
        </form>

        <!-- Login Link -->
        <p class="mt-6 text-center text-sm text-gray-400">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="font-bold text-blue-400 hover:text-blue-300 transition">Masuk di
                sini</a>
        </p>

        <div class="mt-4 text-center">
            <p class="text-xs text-gray-600">
                Dengan mendaftar, Anda menyetujui <a href="#" class="underline hover:text-gray-500">Syarat &
                    Ketentuan</a> dan <a href="#" class="underline hover:text-gray-500">Kebijakan Privasi</a>.
            </p>
        </div>
    </div>

    <!-- Footer Copyright -->
    <p class="mt-8 text-center text-xs text-gray-600">
        Perpustakaan Digital &copy; {{ date('Y') }}
    </p>
@endsection
