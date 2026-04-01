@extends('layouts.app')

@section('title', 'Katalog Buku - Perpustakaan Digital')

@section('content')
<div class="bg-[#050505] min-h-screen pb-20">
    
    <!-- HEADER SECTION -->
    <section class="pt-12 pb-8 border-b border-white/5">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
                <div>
                    <h1 class="text-4xl lg:text-5xl font-bold mb-2">
                        Katalog <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-indigo-400">Buku</span>
                    </h1>
                    <p class="text-gray-400 text-lg">Jelajahi koleksi buku kami dan temukan bacaan favoritmu.</p>
                </div>
                
                <!-- Stats Kecil (Opsional, sesuai design kadang ada) -->
                <div class="hidden md:flex items-center gap-4 text-sm text-gray-400 bg-white/5 px-4 py-2 rounded-full border border-white/10">
                    <span><strong class="text-white">124</strong> Buku Ditampilkan</span>
                    <span class="w-px h-4 bg-white/20"></span>
                    <span><strong class="text-green-400">1.8k+</strong> Total Stok</span>
                </div>
            </div>

            <!-- SEARCH & FILTER BAR -->
            <div class="bg-[#0a0a0a] border border-white/10 rounded-2xl p-4 md:p-6 shadow-2xl">
                <div class="flex flex-col lg:flex-row gap-4">
                    
                    <!-- Search Input -->
                    <div class="flex-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-500"></i>
                        </div>
                        <input type="text" 
                               placeholder="Cari judul buku, pengarang, atau penerbit..." 
                               class="w-full pl-12 pr-4 py-3.5 bg-[#050505] border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:border-purple-500/50 focus:ring-1 focus:ring-purple-500/50 transition">
                    </div>

                    <!-- Filter Genre (Scrollable Horizontal on Mobile) -->
                    <div class="flex items-center gap-2 overflow-x-auto pb-2 lg:pb-0 no-scrollbar">
                        <button class="px-4 py-2.5 bg-purple-600 text-white text-sm font-medium rounded-lg whitespace-nowrap shadow-lg shadow-purple-500/20">Semua</button>
                        <button class="px-4 py-2.5 bg-white/5 hover:bg-white/10 text-gray-300 hover:text-white text-sm font-medium rounded-lg whitespace-nowrap border border-white/5 transition">Novel</button>
                        <button class="px-4 py-2.5 bg-white/5 hover:bg-white/10 text-gray-300 hover:text-white text-sm font-medium rounded-lg whitespace-nowrap border border-white/5 transition">Pendidikan</button>
                        <button class="px-4 py-2.5 bg-white/5 hover:bg-white/10 text-gray-300 hover:text-white text-sm font-medium rounded-lg whitespace-nowrap border border-white/5 transition">Sains</button>
                        <button class="px-4 py-2.5 bg-white/5 hover:bg-white/10 text-gray-300 hover:text-white text-sm font-medium rounded-lg whitespace-nowrap border border-white/5 transition">Sejarah</button>
                        <button class="px-4 py-2.5 bg-white/5 hover:bg-white/10 text-gray-300 hover:text-white text-sm font-medium rounded-lg whitespace-nowrap border border-white/5 transition">Self-Help</button>
                    </div>

                    <!-- Sort Dropdown -->
                    <div class="relative min-w-[200px]">
                        <select class="w-full appearance-none pl-4 pr-10 py-3.5 bg-[#050505] border border-white/10 rounded-xl text-white text-sm focus:outline-none focus:border-purple-500/50 cursor-pointer">
                            <option>Urutkan: Judul A-Z</option>
                            <option>Urutkan: Judul Z-A</option>
                            <option>Terbaru Ditambahkan</option>
                            <option>Paling Populer</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <i class="fas fa-chevron-down text-xs text-gray-500"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CATALOG GRID SECTION -->
    <section class="py-12">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Results Info & View Toggle -->
            <div class="flex justify-between items-center mb-8">
                <p class="text-gray-400 text-sm">Menampilkan <span class="text-white font-medium">12 buku</span></p>
                
                <div class="flex items-center gap-2 bg-[#0a0a0a] p-1 rounded-lg border border-white/10">
                    <button class="p-2 bg-purple-600 text-white rounded-md shadow-lg shadow-purple-500/20 transition">
                        <i class="fas fa-th-large text-sm"></i>
                    </button>
                    <button class="p-2 text-gray-400 hover:text-white hover:bg-white/5 rounded-md transition">
                        <i class="fas fa-list text-sm"></i>
                    </button>
                </div>
            </div>

            <!-- Grid Books -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                @php
                    // Data Dummy untuk 15 Buku dengan Warna Berbeda
                    $books = [
                        ['title' => 'Laskar Pelangi', 'author' => 'Andrea Hirata', 'year' => '2005', 'color' => 'from-purple-500 to-pink-600', 'stock' => 12],
                        ['title' => 'Bumi Manusia', 'author' => 'Pramoedya A.T.', 'year' => '1980', 'color' => 'from-blue-500 to-cyan-600', 'stock' => 5],
                        ['title' => 'Filosofi Teras', 'author' => 'Henry M.', 'year' => '2018', 'color' => 'from-indigo-500 to-blue-600', 'stock' => 18],
                        ['title' => 'Negeri 5 Menara', 'author' => 'Ahmad Fuadi', 'year' => '2009', 'color' => 'from-emerald-500 to-teal-600', 'stock' => 9],
                        ['title' => 'Sapiens', 'author' => 'Yuval Noah Harari', 'year' => '2011', 'color' => 'from-orange-500 to-red-600', 'stock' => 3],
                        ['title' => 'Atomic Habits', 'author' => 'James Clear', 'year' => '2018', 'color' => 'from-amber-500 to-orange-600', 'stock' => 14],
                        ['title' => 'Pulang', 'author' => 'Tere Liye', 'year' => '2015', 'color' => 'from-pink-500 to-rose-600', 'stock' => 7],
                        ['title' => 'Sang Pemimpi', 'author' => 'Andrea Hirata', 'year' => '2006', 'color' => 'from-violet-500 to-purple-600', 'stock' => 11],
                        ['title' => 'Laut Bercerita', 'author' => 'Leila S. Chudori', 'year' => '2017', 'color' => 'from-cyan-500 to-blue-600', 'stock' => 6],
                        ['title' => 'Sejarah Dunia', 'author' => 'Hutton Webster', 'year' => '2016', 'color' => 'from-red-500 to-orange-600', 'stock' => 4],
                        ['title' => 'Fisika Dasar', 'author' => 'Halliday & Resnick', 'year' => '2010', 'color' => 'from-blue-600 to-indigo-700', 'stock' => 15],
                        ['title' => 'Matematika Diskrit', 'author' => 'Kenneth H. Rosen', 'year' => '2012', 'color' => 'from-green-500 to-emerald-600', 'stock' => 8],
                    ];
                @endphp

                @foreach($books as $book)
                <div class="group bg-[#0a0a0a] rounded-2xl border border-white/5 overflow-hidden hover:border-purple-500/30 hover:shadow-xl hover:shadow-purple-500/10 transition duration-300 flex flex-col">
                    
                    <!-- Cover Image Area -->
                    <div class="aspect-[2/3] bg-gradient-to-br {{ $book['color'] }} relative overflow-hidden">
                        <!-- Icon Buku -->
                        <div class="absolute inset-0 flex items-center justify-center">
                            <i class="fas fa-book text-6xl text-white/20 group-hover:scale-110 transition duration-500"></i>
                        </div>
                        
                        <!-- Stock Badge -->
                        <div class="absolute top-3 left-3 px-2.5 py-1 bg-black/40 backdrop-blur-md rounded-md text-xs font-semibold text-green-400 border border-white/10 flex items-center gap-1.5">
                            <i class="fas fa-check-circle text-[10px]"></i>
                            Tersedia: {{ $book['stock'] }}
                        </div>

                        <!-- Hover Overlay Actions -->
                        <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center gap-3 backdrop-blur-[2px]">
                            <button class="w-10 h-10 bg-white/20 hover:bg-white/30 backdrop-blur-sm rounded-full flex items-center justify-center transition transform hover:scale-110" title="Detail">
                                <i class="fas fa-eye text-white text-sm"></i>
                            </button>
                            <button class="w-10 h-10 bg-purple-600 hover:bg-purple-500 backdrop-blur-sm rounded-full flex items-center justify-center transition transform hover:scale-110 shadow-lg shadow-purple-500/30" title="Pinjam">
                                <i class="fas fa-hand-holding-heart text-white text-sm"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Book Info -->
                    <div class="p-4 flex-1 flex flex-col">
                        <h3 class="font-bold text-white text-base mb-1 line-clamp-1 group-hover:text-purple-400 transition">{{ $book['title'] }}</h3>
                        <p class="text-sm text-gray-400 mb-3 line-clamp-1">{{ $book['author'] }}</p>
                        
                        <div class="mt-auto pt-3 border-t border-white/5 flex items-center justify-between">
                            <span class="text-xs text-gray-500 bg-white/5 px-2 py-1 rounded">{{ $book['year'] }}</span>
                            <button class="text-xs font-bold text-purple-400 hover:text-purple-300 transition flex items-center gap-1 group/btn">
                                + Pinjam
                                <i class="fas fa-plus text-[10px] group-hover/btn:rotate-90 transition-transform"></i>
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- PAGINATION -->
            <div class="mt-16 flex justify-center">
                <nav class="flex items-center gap-2">
                    <!-- Prev -->
                    <button class="w-10 h-10 flex items-center justify-center rounded-xl bg-white/5 border border-white/10 text-gray-400 hover:bg-white/10 hover:text-white transition">
                        <i class="fas fa-chevron-left text-sm"></i>
                    </button>
                    
                    <!-- Pages -->
                    <button class="w-10 h-10 flex items-center justify-center rounded-xl bg-purple-600 border border-purple-500 text-white font-semibold shadow-lg shadow-purple-500/25">1</button>
                    <button class="w-10 h-10 flex items-center justify-center rounded-xl bg-white/5 border border-white/10 text-gray-400 hover:bg-white/10 hover:text-white transition">2</button>
                    <button class="w-10 h-10 flex items-center justify-center rounded-xl bg-white/5 border border-white/10 text-gray-400 hover:bg-white/10 hover:text-white transition">3</button>
                    <span class="text-gray-500 px-2">...</span>
                    <button class="w-10 h-10 flex items-center justify-center rounded-xl bg-white/5 border border-white/10 text-gray-400 hover:bg-white/10 hover:text-white transition">8</button>
                    
                    <!-- Next -->
                    <button class="w-10 h-10 flex items-center justify-center rounded-xl bg-white/5 border border-white/10 text-gray-400 hover:bg-white/10 hover:text-white transition">
                        <i class="fas fa-chevron-right text-sm"></i>
                    </button>
                </nav>
            </div>
        </div>
    </section>
</div>
@endsection