@extends('layouts.app')

@section('title', 'Peminjaman Saya - Perpustakaan Digital')

@section('content')
<div class="bg-[#050505] min-h-screen pb-20">
    
    <!-- HEADER SECTION -->
    <section class="pt-12 pb-8 border-b border-white/5">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl lg:text-5xl font-bold mb-2">
                Peminjaman <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-indigo-400">Saya</span>
            </h1>
            <p class="text-gray-400 text-lg">Kelola buku yang sedang Anda pinjam dan proses pengembalian.</p>
        </div>
    </section>

    <!-- STATS CARDS -->
    <section class="py-8">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <!-- Card 1: Sedang Dipinjam -->
                <div class="bg-[#0a0a0a] border border-white/10 rounded-2xl p-6 flex items-center gap-4 hover:border-purple-500/30 transition group">
                    <div class="w-14 h-14 bg-purple-500/10 rounded-xl flex items-center justify-center group-hover:scale-110 transition">
                        <i class="fas fa-book-open text-2xl text-purple-400"></i>
                    </div>
                    <div>
                        <div class="text-3xl font-bold text-white mb-1">3</div>
                        <div class="text-sm text-gray-400">Sedang Dipinjam</div>
                    </div>
                </div>

                <!-- Card 2: Terlambat (Warning) -->
                <div class="bg-[#0a0a0a] border border-red-500/20 rounded-2xl p-6 flex items-center gap-4 hover:border-red-500/40 transition group">
                    <div class="w-14 h-14 bg-red-500/10 rounded-xl flex items-center justify-center group-hover:scale-110 transition">
                        <i class="fas fa-exclamation-triangle text-2xl text-red-400"></i>
                    </div>
                    <div>
                        <div class="text-3xl font-bold text-white mb-1">1</div>
                        <div class="text-sm text-gray-400">Terlambat</div>
                    </div>
                </div>

                <!-- Card 3: Total Denda -->
                <div class="bg-[#0a0a0a] border border-amber-500/20 rounded-2xl p-6 flex items-center gap-4 hover:border-amber-500/40 transition group">
                    <div class="w-14 h-14 bg-amber-500/10 rounded-xl flex items-center justify-center group-hover:scale-110 transition">
                        <i class="fas fa-coins text-2xl text-amber-400"></i>
                    </div>
                    <div>
                        <div class="text-3xl font-bold text-white mb-1">Rp 8.000</div>
                        <div class="text-sm text-gray-400">Total Denda</div>
                    </div>
                </div>

                <!-- Card 4: Total Dikembalikan -->
                <div class="bg-[#0a0a0a] border border-emerald-500/20 rounded-2xl p-6 flex items-center gap-4 hover:border-emerald-500/40 transition group">
                    <div class="w-14 h-14 bg-emerald-500/10 rounded-xl flex items-center justify-center group-hover:scale-110 transition">
                        <i class="fas fa-check-circle text-2xl text-emerald-400"></i>
                    </div>
                    <div>
                        <div class="text-3xl font-bold text-white mb-1">24</div>
                        <div class="text-sm text-gray-400">Total Dikembalikan</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- WARNING ALERT (Muncul jika ada keterlambatan) -->
    <section class="py-4">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-red-500/10 border border-red-500/30 rounded-2xl p-4 flex items-start gap-4">
                <div class="w-10 h-10 bg-red-500/20 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-bell text-red-400"></i>
                </div>
                <div class="flex-1">
                    <h4 class="font-bold text-red-400 mb-1">Peringatan Keterlambatan!</h4>
                    <p class="text-sm text-red-300/80">
                        Anda memiliki <strong class="text-white">1 buku</strong> yang sudah melewati batas pengembalian. Segera kembalikan untuk menghindari denda yang bertambah.
                    </p>
                </div>
                <button class="text-red-400 hover:text-red-300 text-sm font-medium whitespace-nowrap">
                    Bayar Denda
                </button>
            </div>
        </div>
    </section>

    <!-- LIST BUKU DIPINJAM -->
    <section class="py-8">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold mb-6 flex items-center gap-3">
                <i class="fas fa-list-ul text-purple-400"></i>
                Buku yang Sedang Dipinjam
            </h2>

            <div class="space-y-4">
                @php
                    $borrowedBooks = [
                        [
                            'id' => 'PM001',
                            'title' => 'Filosofi Teras',
                            'author' => 'Henry Manampiring',
                            'cover_color' => 'from-indigo-500 to-blue-600',
                            'borrow_date' => '10 Feb 2026',
                            'due_date' => '17 Feb 2026',
                            'status' => 'terlambat', // normal, terlambat
                            'denda' => 8000
                        ],
                        [
                            'id' => 'PM002',
                            'title' => 'Laskar Pelangi',
                            'author' => 'Andrea Hirata',
                            'cover_color' => 'from-purple-500 to-pink-600',
                            'borrow_date' => '18 Feb 2026',
                            'due_date' => '25 Feb 2026',
                            'status' => 'normal',
                            'denda' => 0
                        ],
                        [
                            'id' => 'PM003',
                            'title' => 'Negeri 5 Menara',
                            'author' => 'Ahmad Fuadi',
                            'cover_color' => 'from-emerald-500 to-teal-600',
                            'borrow_date' => '19 Feb 2026',
                            'due_date' => '26 Feb 2026',
                            'status' => 'normal',
                            'denda' => 0
                        ]
                    ];
                @endphp

                @foreach($borrowedBooks as $book)
                <div class="bg-[#0a0a0a] border {{ $book['status'] == 'terlambat' ? 'border-red-500/30' : 'border-white/10' }} rounded-2xl p-6 flex flex-col md:flex-row items-center gap-6 hover:border-purple-500/30 transition">
                    
                    <!-- Cover Mini -->
                    <div class="w-20 h-28 bg-gradient-to-br {{ $book['cover_color'] }} rounded-lg flex items-center justify-center flex-shrink-0 shadow-lg">
                        <i class="fas fa-book text-3xl text-white/40"></i>
                    </div>

                    <!-- Info Buku -->
                    <div class="flex-1 text-center md:text-left">
                        <h3 class="text-xl font-bold text-white mb-1">{{ $book['title'] }}</h3>
                        <p class="text-sm text-gray-400 mb-3">{{ $book['author'] }}</p>
                        
                        <div class="flex flex-wrap justify-center md:justify-start gap-4 text-sm">
                            <div class="flex items-center gap-2 text-gray-300">
                                <i class="fas fa-calendar-alt text-purple-400"></i>
                                <span>Dipinjam: <strong class="text-white">{{ $book['borrow_date'] }}</strong></span>
                            </div>
                            <div class="flex items-center gap-2 {{ $book['status'] == 'terlambat' ? 'text-red-400' : 'text-gray-300' }}">
                                <i class="fas fa-clock {{ $book['status'] == 'terlambat' ? 'text-red-400' : 'text-purple-400' }}"></i>
                                <span>Batas Kembali: <strong class="{{ $book['status'] == 'terlambat' ? 'text-red-400' : 'text-white' }}">{{ $book['due_date'] }}</strong></span>
                            </div>
                            @if($book['status'] == 'terlambat')
                            <div class="flex items-center gap-2 text-amber-400 font-semibold">
                                <i class="fas fa-coins"></i>
                                <span>Denda: Rp {{ number_format($book['denda'], 0, ',', '.') }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center gap-3 w-full md:w-auto">
                        @if($book['status'] == 'terlambat')
                            <button class="flex-1 md:flex-none px-6 py-3 bg-amber-600 hover:bg-amber-500 text-white font-semibold rounded-xl transition shadow-lg shadow-amber-500/20">
                                <i class="fas fa-wallet mr-2"></i>Bayar Denda
                            </button>
                        @endif
                        <button class="flex-1 md:flex-none px-6 py-3 bg-purple-600 hover:bg-purple-500 text-white font-semibold rounded-xl transition shadow-lg shadow-purple-500/20">
                            <i class="fas fa-undo mr-2"></i>Kembalikan Buku
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CTA SECTION (Jika ingin pinjam lagi) -->
    <section class="py-12">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-r from-purple-900/20 to-indigo-900/20 border border-white/10 rounded-3xl p-12 text-center relative overflow-hidden">
                <!-- Background Decor -->
                <div class="absolute top-0 left-0 w-full h-full bg-[url('data:image/svg+xml;base64,...')] opacity-5"></div>
                
                <div class="relative z-10">
                    <div class="w-16 h-16 mx-auto mb-6 bg-purple-600/20 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-book-open text-3xl text-purple-400"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-2">Ingin pinjam buku lagi?</h3>
                    <p class="text-gray-400 mb-8 max-w-md mx-auto">Jelajahi katalog dan temukan buku bacaan selanjutnya.</p>
                    <a href="{{ route('katalog') }}" class="inline-flex items-center gap-2 px-8 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white font-bold rounded-full shadow-xl shadow-purple-500/25 transition transform hover:scale-105">
                        <i class="fas fa-search"></i>
                        Cari Buku
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection