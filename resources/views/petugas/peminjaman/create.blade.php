@extends('layouts.petugas')

@section('title', 'Peminjaman Baru')
@section('page-title', 'Transaksi Peminjaman')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-xl font-bold text-white">Form Peminjaman Buku</h2>
            <p class="text-sm text-slate-400">Proses peminjaman cepat untuk anggota</p>
        </div>
        <a href="{{ route('petugas.peminjaman.index') }}" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white text-sm font-medium rounded-xl transition flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Batal
        </a>
    </div>
    <!-- Tom Select CSS for Select Search -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    <style>
        /* Modern Dark Theme for Tom Select matching the app's aesthetic */
        .ts-wrapper {
            width: 100%;
        }
        .ts-control {
            background-color: #1e293b !important; /* slate-800 */
            border: 1px solid #475569 !important; /* slate-600 */
            color: #f1f5f9 !important;
            border-radius: 0.75rem !important; /* rounded-xl */
            padding: 0.75rem 1rem !important; /* py-3 px-4 */
            font-size: 0.875rem !important;
            line-height: normal !important;
            min-height: 46px; /* manual height to match standard inputs */
            box-shadow: none !important;
            transition: all 0.2s ease-in-out;
            display: flex;
            align-items: center;
        }
        .ts-control.focus {
            border-color: #6366f1 !important; /* indigo-500 */
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2) !important;
        }
        .ts-control > input {
            color: #f1f5f9 !important;
            font-size: 0.875rem !important;
            line-height: 1.25rem !important;
        }
        .ts-control > input::placeholder {
            color: #94a3b8 !important;
        }
        .ts-wrapper.single .ts-control {
            display: flex;
        }
        .ts-wrapper.single .ts-control::after {
            border-color: #94a3b8 transparent transparent transparent !important;
            right: 1.25rem !important;
            margin-top: 0 !important;
            transform: translateY(-50%);
            top: 50%;
        }
        .ts-wrapper.single.focus .ts-control::after {
            border-color: transparent transparent #6366f1 transparent !important;
        }
        .ts-dropdown {
            background-color: #1e293b !important;
            border: 1px solid #475569 !important;
            border-radius: 0.75rem !important;
            margin-top: 0.5rem;
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.2), 0 4px 6px -4px rgb(0 0 0 / 0.1) !important;
            overflow: hidden;
            z-index: 50;
        }
        .ts-dropdown .ts-dropdown-content {
            scrollbar-width: thin;
            scrollbar-color: #475569 transparent;
        }
        .ts-dropdown .ts-dropdown-content::-webkit-scrollbar {
            width: 6px;
        }
        .ts-dropdown .ts-dropdown-content::-webkit-scrollbar-thumb {
            background-color: #475569;
            border-radius: 10px;
        }
        .ts-dropdown .option {
            padding: 0.75rem 1rem !important;
            color: #cbd5e1 !important; /* slate-300 */
            font-size: 0.875rem !important;
            transition: background-color 0.15s, color 0.15s;
        }
        .ts-dropdown .option.active, 
        .ts-dropdown .option:hover {
            background-color: #334155 !important;
            color: #ffffff !important;
        }
        .ts-dropdown .create {
            color: #cbd5e1 !important;
        }
        .has-error .ts-control {
            border-color: #ef4444 !important; /* red-500 */
        }
    </style>

    <form action="{{ route('petugas.peminjaman.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- BAGIAN 1: PILIH ANGGOTA & BUKU -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Pilih Anggota -->
            <div class="bg-[#1e293b] rounded-2xl border border-slate-700/50 p-6 shadow-xl">
                <div class="flex items-center gap-3 mb-4 border-b border-slate-700 pb-3">
                    <div class="w-8 h-8 rounded-lg bg-indigo-500/20 flex items-center justify-center text-indigo-400">
                        <i class="fas fa-user"></i>
                    </div>
                    <h3 class="font-bold text-white">Data Peminjam</h3>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Pilih Anggota <span class="text-red-400">*</span></label>
                        <select name="id_anggota" id="select_anggota" required 
                                class="w-full bg-slate-800 border border-slate-600 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-indigo-500 @error('id_anggota') border-red-500 @enderror">
                            <option value="">-- Cari Nama / NIS --</option>
                            @foreach($anggotas as $anggota)
                                <option value="{{ $anggota->id }}" 
                                    data-kelas="{{ $anggota->kelas }}" 
                                    data-nis="{{ $anggota->nis_nisn }}">
                                    {{ $anggota->nama }} ({{ $anggota->nis_nisn }})
                                </option>
                            @endforeach
                        </select>
                        @error('id_anggota') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- Info Anggota (Muncul setelah pilih) -->
                    <div id="info_anggota" class="hidden p-4 bg-slate-800/50 rounded-xl border border-slate-700">
                        <div class="grid grid-cols-2 gap-2 text-sm">
                            <div class="text-slate-400">NIS:</div>
                            <div class="text-white font-mono" id="info_nis">-</div>
                            <div class="text-slate-400">Kelas:</div>
                            <div class="text-white" id="info_kelas">-</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pilih Buku -->
            <div class="bg-[#1e293b] rounded-2xl border border-slate-700/50 p-6 shadow-xl">
                <div class="flex items-center gap-3 mb-4 border-b border-slate-700 pb-3">
                    <div class="w-8 h-8 rounded-lg bg-emerald-500/20 flex items-center justify-center text-emerald-400">
                        <i class="fas fa-book"></i>
                    </div>
                    <h3 class="font-bold text-white">Data Buku</h3>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Pilih Buku <span class="text-red-400">*</span></label>
                        <select name="id_buku" id="select_buku" required 
                                class="w-full bg-slate-800 border border-slate-600 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-emerald-500 @error('id_buku') border-red-500 @enderror">
                            <option value="">-- Cari Judul Buku --</option>
                            @foreach($bukus as $buku)
                                <option value="{{ $buku->id_buku }}" 
                                    data-stok="{{ $buku->stok_tersedia }}"
                                    data-judul="{{ $buku->judul }}">
                                    {{ $buku->judul }} - {{ $buku->pengarang }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_buku') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                        <p class="text-xs text-slate-500 mt-1">Hanya menampilkan buku dengan stok tersedia > 0</p>
                    </div>

                    <!-- Info Stok (Muncul setelah pilih) -->
                    <div id="info_buku" class="hidden p-4 bg-slate-800/50 rounded-xl border border-slate-700">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-400 text-sm">Stok Tersedia:</span>
                            <span class="text-emerald-400 font-bold text-lg" id="info_stok">0</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- BAGIAN 2: DETAIL TRANSAKSI -->
        <div class="bg-[#1e293b] rounded-2xl border border-slate-700/50 p-6 shadow-xl">
            <div class="flex items-center gap-3 mb-6 border-b border-slate-700 pb-3">
                <div class="w-8 h-8 rounded-lg bg-amber-500/20 flex items-center justify-center text-amber-400">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <h3 class="font-bold text-white">Detail Pinjaman</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Tanggal Pinjam -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Tanggal Pinjam <span class="text-red-400">*</span></label>
                    <input type="date" name="tanggal_pinjam" value="{{ old('tanggal_pinjam', $tanggal_pinjam->format('Y-m-d')) }}" required 
                           class="w-full bg-slate-800 border border-slate-600 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-amber-500 @error('tanggal_pinjam') border-red-500 @enderror">
                    @error('tanggal_pinjam') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>

                <!-- Durasi -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Durasi (Hari) <span class="text-red-400">*</span></label>
                    <input type="number" name="durasi_pinjam" id="durasi" value="{{ old('durasi_pinjam', 7) }}" min="1" max="7" required 
                           class="w-full bg-slate-800 border border-slate-600 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-amber-500 @error('durasi_pinjam') border-red-500 @enderror">
                    <p class="text-xs text-slate-500 mt-1">Maksimal <span class="text-amber-400 font-semibold">7 hari</span></p>
                </div>

                <!-- Tanggal Kembali (Otomatis) -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Jatuh Tempo <span class="text-xs text-slate-500">(Otomatis)</span></label>
                    <input type="date" id="tanggal_kembali_display" readonly 
                           class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 text-slate-400 cursor-not-allowed">
                    <input type="hidden" name="tanggal_kembali_rencana" id="tanggal_kembali_hidden">
                </div>
            </div>

            <!-- Catatan -->
            <div class="mt-6">
                <label class="block text-sm font-medium text-slate-300 mb-2">Catatan Tambahan (Opsional)</label>
                <textarea name="catatan" rows="2" 
                          class="w-full bg-slate-800 border border-slate-600 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-amber-500 @error('catatan') border-red-500 @enderror"
                          placeholder="Misal: Kondisi buku baik, harap jaga kebersihan...">{{ old('catatan') }}</textarea>
                @error('catatan') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- TOMBOL SUBMIT -->
        <div class="flex justify-end gap-4 pt-4">
            <a href="{{ route('petugas.peminjaman.index') }}" class="px-6 py-3 rounded-xl border border-slate-600 text-slate-300 hover:bg-slate-800 transition font-medium">
                Batal
            </a>
            <button type="submit" class="px-8 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/30 transition flex items-center gap-2">
                <i class="fas fa-save"></i> Proses Peminjaman
            </button>
        </div>
    </form>
</div>

<!-- Script JavaScript untuk Interaktivitas -->
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Inisialisasi Tom Select untuk Anggota
        const infoAnggota = document.getElementById('info_anggota');
        const infoNis = document.getElementById('info_nis');
        const infoKelas = document.getElementById('info_kelas');

        new TomSelect("#select_anggota", {
            placeholder: "-- Cari Nama / NIS Anggota --",
            maxOptions: 50,
            render: {
                no_results: function(data, escape) {
                    return '<div class="no-results px-4 py-3 text-sm text-slate-400">Data anggota tidak ditemukan</div>';
                }
            },
            onChange: function(value) {
                if (value) {
                    // Cari data option yang dipilih dari dom select asli (untuk membaca dataset)
                    const selectEl = document.getElementById('select_anggota');
                    const option = selectEl.querySelector(`option[value="${value}"]`);
                    
                    if (option) {
                        infoNis.textContent = option.dataset.nis;
                        infoKelas.textContent = option.dataset.kelas;
                        infoAnggota.classList.remove('hidden');
                    }
                } else {
                    infoAnggota.classList.add('hidden');
                }
            }
        });

        // 2. Inisialisasi Tom Select untuk Buku & Info Stok
        const infoBuku = document.getElementById('info_buku');
        const infoStok = document.getElementById('info_stok');

        new TomSelect("#select_buku", {
            placeholder: "-- Cari Judul / Pengarang Buku --",
            maxOptions: 50,
            render: {
                no_results: function(data, escape) {
                    return '<div class="no-results px-4 py-3 text-sm text-slate-400">Buku tidak ditemukan</div>';
                }
            },
            onChange: function(value) {
                if (value) {
                    const selectEl = document.getElementById('select_buku');
                    const option = selectEl.querySelector(`option[value="${value}"]`);
                    
                    if (option) {
                        const stok = option.dataset.stok;
                        infoStok.textContent = stok;
                        infoBuku.classList.remove('hidden');
                        
                        // Warning jika stok menipis
                        if (stok <= 2) {
                            infoStok.classList.add('text-red-400');
                            infoStok.classList.remove('text-emerald-400');
                        } else {
                            infoStok.classList.remove('text-red-400');
                            infoStok.classList.add('text-emerald-400');
                        }
                    }
                } else {
                    infoBuku.classList.add('hidden');
                }
            }
        });

        // 3. Logic Hitung Tanggal Jatuh Tempo Otomatis
        const inputPinjam = document.querySelector('input[name="tanggal_pinjam"]');
        const inputDurasi = document.getElementById('durasi');
        const displayKembali = document.getElementById('tanggal_kembali_display');
        const hiddenKembali = document.getElementById('tanggal_kembali_hidden');

        function hitungJatuhTempo() {
            const tglPinjam = new Date(inputPinjam.value);
            let durasi = parseInt(inputDurasi.value) || 0;

            // Batasi maksimal 7 hari
            if (durasi > 7) {
                durasi = 7;
                inputDurasi.value = 7;
            }

            if (!isNaN(tglPinjam.getTime()) && durasi > 0) {
                // Tambahkan durasi hari
                tglPinjam.setDate(tglPinjam.getDate() + durasi);
                
                // Format ke YYYY-MM-DD
                const yyyy = tglPinjam.getFullYear();
                const mm = String(tglPinjam.getMonth() + 1).padStart(2, '0');
                const dd = String(tglPinjam.getDate()).padStart(2, '0');
                const result = `${yyyy}-${mm}-${dd}`;

                displayKembali.value = result;
                hiddenKembali.value = result;
            }
        }

        inputPinjam.addEventListener('change', hitungJatuhTempo);
        inputDurasi.addEventListener('input', hitungJatuhTempo);

        // Jalankan sekali saat load
        hitungJatuhTempo();
    });
</script>
@endsection