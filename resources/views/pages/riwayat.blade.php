@extends('layouts.app')

@section('title', 'Riwayat Transaksi - Perpustakaan Digital')

@section('content')
<div class="bg-[#050505] min-h-screen pb-20">
    
    <!-- HEADER SECTION -->
    <section class="pt-12 pb-8 border-b border-white/5">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl lg:text-5xl font-bold mb-2">
                Riwayat <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-indigo-400">Transaksi</span>
            </h1>
            <p class="text-gray-400 text-lg">Pantau seluruh riwayat peminjaman, pengembalian, dan denda Anda.</p>
        </div>
    </section>

    <!-- TABS & STATS -->
    <section class="py-8">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            <!-- Filter Tabs -->
            <div class="flex flex-wrap gap-2">
                <button class="px-5 py-2.5 bg-purple-600 text-white text-sm font-semibold rounded-xl shadow-lg shadow-purple-500/25 transition">
                    <i class="fas fa-list mr-2"></i>Semua
                </button>
                <button class="px-5 py-2.5 bg-white/5 hover:bg-white/10 text-gray-300 hover:text-white text-sm font-medium rounded-xl border border-white/5 transition">
                    <i class="fas fa-hand-holding-heart mr-2"></i>Peminjaman
                </button>
                <button class="px-5 py-2.5 bg-white/5 hover:bg-white/10 text-gray-300 hover:text-white text-sm font-medium rounded-xl border border-white/5 transition">
                    <i class="fas fa-undo mr-2"></i>Pengembalian
                </button>
                <button class="px-5 py-2.5 bg-white/5 hover:bg-white/10 text-gray-300 hover:text-white text-sm font-medium rounded-xl border border-white/5 transition">
                    <i class="fas fa-coins mr-2"></i>Denda
                </button>
            </div>

            <!-- Stats Summary -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-[#0a0a0a] border border-white/10 rounded-2xl p-5">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 bg-purple-500/10 rounded-lg flex items-center justify-center">
                            <i class="fas fa-book-open text-purple-400 text-sm"></i>
                        </div>
                        <span class="text-xs text-gray-400 uppercase font-bold tracking-wider">Total Pinjam</span>
                    </div>
                    <div class="text-2xl font-bold text-white">27</div>
                </div>
                <div class="bg-[#0a0a0a] border border-white/10 rounded-2xl p-5">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 bg-emerald-500/10 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check-circle text-emerald-400 text-sm"></i>
                        </div>
                        <span class="text-xs text-gray-400 uppercase font-bold tracking-wider">Dikembalikan</span>
                    </div>
                    <div class="text-2xl font-bold text-white">24</div>
                </div>
                <div class="bg-[#0a0a0a] border border-white/10 rounded-2xl p-5">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 bg-amber-500/10 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clock text-amber-400 text-sm"></i>
                        </div>
                        <span class="text-xs text-gray-400 uppercase font-bold tracking-wider">Sedang Dipinjam</span>
                    </div>
                    <div class="text-2xl font-bold text-white">3</div>
                </div>
                <div class="bg-[#0a0a0a] border border-red-500/20 rounded-2xl p-5">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 bg-red-500/10 rounded-lg flex items-center justify-center">
                            <i class="fas fa-exclamation-circle text-red-400 text-sm"></i>
                        </div>
                        <span class="text-xs text-gray-400 uppercase font-bold tracking-wider">Total Denda</span>
                    </div>
                    <div class="text-2xl font-bold text-red-400">Rp 42.000</div>
                </div>
            </div>
        </div>
    </section>

    <!-- TABEL RIWAYAT UTAMA -->
    <section class="py-4">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Search & Filter Bar -->
            <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
                <div class="relative w-full md:w-96">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-500"></i>
                    </div>
                    <input type="text" placeholder="Cari judul buku atau ID transaksi..." 
                           class="w-full pl-11 pr-4 py-3 bg-[#0a0a0a] border border-white/10 rounded-xl text-white text-sm placeholder-gray-500 focus:outline-none focus:border-purple-500/50 transition">
                </div>
                <div class="relative w-full md:w-48">
                    <select class="w-full appearance-none pl-4 pr-10 py-3 bg-[#0a0a0a] border border-white/10 rounded-xl text-white text-sm focus:outline-none focus:border-purple-500/50 cursor-pointer">
                        <option>Semua Bulan</option>
                        <option>Februari 2026</option>
                        <option>Januari 2026</option>
                        <option>Desember 2025</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <i class="fas fa-chevron-down text-xs text-gray-500"></i>
                    </div>
                </div>
            </div>

            <!-- Table Container -->
            <div class="bg-[#0a0a0a] border border-white/10 rounded-2xl overflow-hidden shadow-xl">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white/5 border-b border-white/10 text-xs uppercase tracking-wider text-gray-400">
                                <th class="px-6 py-4 font-semibold">ID</th>
                                <th class="px-6 py-4 font-semibold">Buku</th>
                                <th class="px-6 py-4 font-semibold">Tgl Pinjam</th>
                                <th class="px-6 py-4 font-semibold">Tgl Kembali</th>
                                <th class="px-6 py-4 font-semibold text-center">Status</th>
                                <th class="px-6 py-4 font-semibold text-right">Denda</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5 text-sm">
                            @php
                                $transactions = [
                                    ['id' => '#PM001', 'title' => 'Laskar Pelangi', 'borrow' => '18 Feb 2026', 'return' => '25 Feb 2026', 'status' => 'dipinjam', 'fine' => '-'],
                                    ['id' => '#PM003', 'title' => 'Filosofi Teras', 'borrow' => '10 Feb 2026', 'return' => '17 Feb 2026', 'status' => 'terlambat', 'fine' => 'Rp 8.000'],
                                    ['id' => '#PM004', 'title' => 'Negeri 5 Menara', 'borrow' => '19 Feb 2026', 'return' => '26 Feb 2026', 'status' => 'dipinjam', 'fine' => '-'],
                                    ['id' => '#PM010', 'title' => 'Bumi Manusia', 'borrow' => '01 Feb 2026', 'return' => '08 Feb 2026', 'status' => 'kembali', 'fine' => '-'],
                                    ['id' => '#PM011', 'title' => 'Pulang', 'borrow' => '20 Jan 2026', 'return' => '27 Jan 2026', 'status' => 'kembali', 'fine' => '-'],
                                    ['id' => '#PM012', 'title' => 'Sang Pemimpi', 'borrow' => '15 Jan 2026', 'return' => '24 Jan 2026', 'status' => 'kembali', 'fine' => 'Rp 4.000'],
                                    ['id' => '#PM013', 'title' => 'Sapiens', 'borrow' => '10 Jan 2026', 'return' => '17 Jan 2026', 'status' => 'kembali', 'fine' => '-'],
                                    ['id' => '#PM014', 'title' => 'Atomic Habits', 'borrow' => '05 Jan 2026', 'return' => '15 Jan 2026', 'status' => 'kembali', 'fine' => 'Rp 6.000'],
                                ];
                            @endphp

                            @foreach($transactions as $item)
                            <tr class="hover:bg-white/5 transition group">
                                <td class="px-6 py-4 font-mono text-purple-400 font-medium">{{ $item['id'] }}</td>
                                <td class="px-6 py-4 font-medium text-white group-hover:text-purple-300 transition">{{ $item['title'] }}</td>
                                <td class="px-6 py-4 text-gray-400">{{ $item['borrow'] }}</td>
                                <td class="px-6 py-4 text-gray-400">{{ $item['return'] }}</td>
                                <td class="px-6 py-4 text-center">
                                    @if($item['status'] == 'dipinjam')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                            Dipinjam
                                        </span>
                                    @elseif($item['status'] == 'terlambat')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-500/10 text-red-400 border border-red-500/20">
                                            Terlambat
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                            Dikembalikan
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right font-medium {{ strpos($item['fine'], 'Rp') !== false ? 'text-red-400' : 'text-gray-500' }}">
                                    {{ $item['fine'] }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="flex items-center justify-between px-6 py-4 border-t border-white/10 bg-white/5">
                    <span class="text-sm text-gray-400">Menampilkan <strong class="text-white">1-10</strong> dari <strong class="text-white">27</strong></span>
                    <div class="flex gap-2">
                        <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/5 hover:bg-white/10 text-gray-400 hover:text-white transition"><i class="fas fa-chevron-left text-xs"></i></button>
                        <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-purple-600 text-white font-semibold shadow-lg shadow-purple-500/25">1</button>
                        <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/5 hover:bg-white/10 text-gray-400 hover:text-white transition">2</button>
                        <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/5 hover:bg-white/10 text-gray-400 hover:text-white transition">3</button>
                        <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/5 hover:bg-white/10 text-gray-400 hover:text-white transition"><i class="fas fa-chevron-right text-xs"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION RIWAYAT DENDA -->
    <section class="py-12">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold mb-6 flex items-center gap-3">
                <i class="fas fa-file-invoice-dollar text-red-400"></i>
                Riwayat Denda
            </h2>

            <!-- Denda Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-red-500/10 border border-red-500/20 rounded-xl p-4 text-center">
                    <div class="text-xs text-red-300 uppercase font-bold mb-1">Total Denda</div>
                    <div class="text-xl font-bold text-red-400">Rp 42.000</div>
                </div>
                <div class="bg-amber-500/10 border border-amber-500/20 rounded-xl p-4 text-center">
                    <div class="text-xs text-amber-300 uppercase font-bold mb-1">Belum Dibayar</div>
                    <div class="text-xl font-bold text-amber-400">Rp 8.000</div>
                </div>
                <div class="bg-emerald-500/10 border border-emerald-500/20 rounded-xl p-4 text-center">
                    <div class="text-xs text-emerald-300 uppercase font-bold mb-1">Sudah Dibayar</div>
                    <div class="text-xl font-bold text-emerald-400">Rp 34.000</div>
                </div>
            </div>

            <!-- Denda Table -->
            <div class="bg-[#0a0a0a] border border-white/10 rounded-2xl overflow-hidden shadow-xl">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white/5 border-b border-white/10 text-xs uppercase tracking-wider text-gray-400">
                                <th class="px-6 py-4 font-semibold">ID Denda</th>
                                <th class="px-6 py-4 font-semibold">Buku</th>
                                <th class="px-6 py-4 font-semibold text-center">Terlambat</th>
                                <th class="px-6 py-4 font-semibold text-right">Jumlah</th>
                                <th class="px-6 py-4 font-semibold text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5 text-sm">
                            @php
                                $fines = [
                                    ['id' => '#DN001', 'title' => 'Filosofi Teras', 'days' => '4 hari', 'amount' => 'Rp 8.000', 'status' => 'belum'],
                                    ['id' => '#DN005', 'title' => 'Sang Pemimpi', 'days' => '2 hari', 'amount' => 'Rp 4.000', 'status' => 'lunas'],
                                    ['id' => '#DN006', 'title' => 'Atomic Habits', 'days' => '3 hari', 'amount' => 'Rp 6.000', 'status' => 'lunas'],
                                    ['id' => '#DN007', 'title' => 'Sejarah Dunia', 'days' => '2 hari', 'amount' => 'Rp 4.000', 'status' => 'lunas'],
                                ];
                            @endphp

                            @foreach($fines as $fine)
                            <tr class="hover:bg-white/5 transition">
                                <td class="px-6 py-4 font-mono text-red-400 font-medium">{{ $fine['id'] }}</td>
                                <td class="px-6 py-4 font-medium text-white">{{ $fine['title'] }}</td>
                                <td class="px-6 py-4 text-center text-gray-400">{{ $fine['days'] }}</td>
                                <td class="px-6 py-4 text-right font-bold text-red-400">{{ $fine['amount'] }}</td>
                                <td class="px-6 py-4 text-center">
                                    @if($fine['status'] == 'belum')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-500/10 text-red-400 border border-red-500/20">
                                            Belum Lunas
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                            Lunas
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection