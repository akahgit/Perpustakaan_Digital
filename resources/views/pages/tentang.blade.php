@extends('layouts.app')

@section('title', 'Tentang Kami — Perpustakaan Digital')

@section('content')
<div class="bg-[#020617] min-h-screen text-slate-200 overflow-x-hidden">

    <!-- ══ 1. HERO STORY ══ -->
    <section class="relative pt-24 pb-32 overflow-hidden border-b border-white/5">
        {{-- Decorations --}}
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[1000px] h-[600px] bg-indigo-600/10 rounded-full blur-[150px] pointer-events-none"></div>

        <div class="max-w-[1600px] mx-auto px-6 lg:px-12 relative z-10 text-center">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 mb-8 rounded-full bg-white/5 border border-white/10 backdrop-blur-xl">
                <span class="text-indigo-400 text-[10px] font-black uppercase tracking-[0.2em]">Our Manifesto</span>
            </div>
            <h1 class="text-6xl lg:text-[100px] font-black leading-[0.9] tracking-tighter mb-10">
                Lebih Dari Sekadar <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400 italic">Tempat Membaca</span>.
            </h1>
            <p class="text-xl text-slate-400 max-w-3xl mx-auto leading-relaxed font-medium opacity-80">
                Perpustakaan Digital lahir dari visi untuk mendemokratisasi akses pengetahuan melalui teknologi tercanggih. Kami percaya bahwa setiap lembar buku adalah benih perubahan.
            </p>
        </div>
    </section>

    <!-- ══ 2. VISION & MISSION (BENTO DESIGN) ══ -->
    <section class="py-32 relative">
        <div class="max-w-[1600px] mx-auto px-6 lg:px-12">
            <div class="grid lg:grid-cols-3 gap-6">
                
                {{-- Visi Card --}}
                <div class="lg:col-span-2 relative group p-12 rounded-[56px] border border-white/10 bg-white/3 backdrop-blur-3xl overflow-hidden flex flex-col justify-between min-h-[400px]">
                    <div class="w-16 h-16 bg-indigo-600 rounded-3xl flex items-center justify-center text-3xl shadow-2xl shadow-indigo-600/30">
                        <i class="fas fa-eye"></i>
                    </div>
                    <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-indigo-500/10 rounded-full blur-[100px]"></div>
                    <div class="relative z-10">
                        <h4 class="text-gray-500 font-black uppercase tracking-widest text-xs mb-4">Visi Utama</h4>
                        <h3 class="text-4xl lg:text-5xl font-black text-white leading-tight">Membangun Ekosistem <span class="text-indigo-400">Literasi Global</span> yang Luas & Tanpa Batas.</h3>
                    </div>
                </div>

                {{-- Achievement Card --}}
                <div class="relative group p-12 rounded-[56px] bg-gradient-to-br from-purple-600 to-indigo-800 flex flex-col items-center justify-center text-center">
                    <div class="text-[120px] font-black text-white/10 absolute top-1/2 -translate-y-1/2 select-none font-serif italic">12</div>
                    <div class="relative z-10">
                        <h4 class="text-white/60 font-black uppercase tracking-widest text-[10px] mb-4">Beroperasi Sejak</h4>
                        <h3 class="text-6xl font-black text-white mb-2">2014</h3>
                        <p class="text-white/60 text-xs font-bold font-serif italic">Dedikasi untuk Pendidikan</p>
                    </div>
                </div>

                {{-- Mission Card --}}
                <div class="lg:col-span-3 relative group p-12 rounded-[56px] border border-white/10 bg-white/3 backdrop-blur-3xl overflow-hidden">
                    <div class="grid md:grid-cols-3 gap-12">
                        <div class="space-y-4">
                            <div class="text-3xl text-indigo-400"><i class="fas fa-microchip"></i></div>
                            <h4 class="text-white font-black text-lg">Inovasi Teknologi</h4>
                            <p class="text-slate-500 text-sm leading-relaxed">Terus memperbarui infrastruktur digital demi kecepatan akses dan keamanan data anggota.</p>
                        </div>
                        <div class="space-y-4">
                            <div class="text-3xl text-purple-400"><i class="fas fa-users"></i></div>
                            <h4 class="text-white font-black text-lg">Inklusivitas</h4>
                            <p class="text-slate-500 text-sm leading-relaxed">Menyediakan akses yang setara bagi semua lapisan masyarakat untuk mendapatkan buku berkualitas.</p>
                        </div>
                        <div class="space-y-4">
                            <div class="text-3xl text-pink-400"><i class="fas fa-shield-alt"></i></div>
                            <h4 class="text-white font-black text-lg">Keamanan Transaksi</h4>
                            <p class="text-slate-500 text-sm leading-relaxed">Menjamin keamanan setiap data peminjaman dan pembayaran dengan standar privasi tinggi.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ══ 3. CORE VALUES (HORIZONTAL TIMELINE) ══ -->
    <section class="py-32 border-t border-white/5 relative overflow-hidden">
        <div class="absolute -right-24 top-1/2 -translate-y-1/2 text-[300px] font-black text-white/[0.02] select-none pointer-events-none tracking-tighter">VALUES</div>
        <div class="max-w-[1600px] mx-auto px-6 lg:px-12">
            <div class="mb-20">
                <h4 class="text-indigo-500 font-black uppercase tracking-[0.3em] text-[10px] mb-4">Internal Culture</h4>
                <h2 class="text-5xl font-black text-white">Nilai Inti <span class="italic text-indigo-500">Kami</span>.</h2>
            </div>

            <div class="grid md:grid-cols-3 gap-16 relative">
                @php
                    $values = [
                        ['title' => 'Integritas Tinggi', 'desc' => 'Menjaga kejujuran dalam setiap pengelolaan aset dan data perpustakaan.'],
                        ['title' => 'Pelayanan Prima', 'desc' => 'Memberikan respon tercepat dan solusi terbaik bagi setiap kebutuhan anggota.'],
                        ['title' => 'Semangat Belajar', 'desc' => 'Percaya bahwa proses belajar tidak pernah berakhir, seperti koleksi buku kami.'],
                    ];
                @endphp
                @foreach($values as $i => $v)
                <div class="relative group">
                    <div class="text-[80px] font-black text-white/5 absolute -top-12 -left-4 group-hover:text-indigo-500/10 transition-colors">{{ str_pad($i+1, 2, '0', STR_PAD_LEFT) }}</div>
                    <div class="relative z-10">
                        <h4 class="text-xl font-black text-white mb-4">{{ $v['title'] }}</h4>
                        <p class="text-slate-500 text-sm leading-relaxed">{{ $v['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ══ 4. MEET THE TEAM (PREMIUM CARDS) ══ -->
    <section class="py-32 bg-white/[0.02] border-y border-white/5 relative">
        <div class="max-w-[1600px] mx-auto px-6 lg:px-12">
            <div class="text-center mb-24">
                <h4 class="text-indigo-500 font-black uppercase tracking-[0.3em] text-[10px] mb-4">The Custodians</h4>
                <h2 class="text-5xl font-black text-white">Pengelola <span class="text-indigo-500">Profesional</span>.</h2>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                @php
                    $team = [
                        ['name' => 'Dr. Kartini Putri', 'role' => 'Kepala Perpustakaan', 'initials' => 'KP', 'color' => 'from-purple-500 to-pink-600', 'desc' => 'Ph.D in Library Science with 15 years experience in digital archiving.'],
                        ['name' => 'Ahmad Suryana', 'role' => 'Petugas Operasional', 'initials' => 'AS', 'color' => 'from-blue-500 to-indigo-600', 'desc' => 'Expert in collection management and member relations.'],
                        ['name' => 'Rina Wulandari', 'role' => 'IT & Digital Librarian', 'initials' => 'RW', 'color' => 'from-emerald-500 to-teal-600', 'desc' => 'Responsible for maintaining the integrity of our digital catalog.'],
                    ];
                @endphp
                @foreach($team as $member)
                <div class="group relative p-10 rounded-[48px] bg-white/3 border border-white/5 hover:border-white/20 hover:bg-white/5 transition-all duration-500">
                    <div class="w-24 h-24 mb-8 bg-gradient-to-br {{ $member['color'] }} rounded-[32px] flex items-center justify-center text-3xl font-black text-white shadow-2xl group-hover:-rotate-6 transition-transform">
                        {{ $member['initials'] }}
                    </div>
                    <h4 class="text-gray-500 text-[10px] font-black uppercase tracking-widest mb-2">{{ $member['role'] }}</h4>
                    <h3 class="text-2xl font-black text-white mb-4">{{ $member['name'] }}</h3>
                    <p class="text-slate-500 text-xs leading-relaxed mb-8">{{ $member['desc'] }}</p>
                    <div class="flex gap-4 opacity-40 group-hover:opacity-100 transition-opacity">
                        <a href="#" class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center hover:bg-indigo-600 transition-colors"><i class="fab fa-linkedin-in text-xs"></i></a>
                        <a href="#" class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center hover:bg-indigo-400 transition-colors"><i class="fab fa-twitter text-xs"></i></a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ══ 5. FINAL MANIFESTO ══ -->
    <section class="py-32 relative">
        <div class="max-w-[800px] mx-auto px-6 text-center">
            <i class="fas fa-quote-left text-5xl text-indigo-500/20 mb-8"></i>
            <h2 class="text-3xl lg:text-4xl font-black text-white italic leading-tight mb-12">
                "Literasi adalah jalan utama menuju kebebasan berpikir. Kami di sini untuk memastikan jalan itu tetap terbuka lebar bagi Anda."
            </h2>
            <div class="w-20 h-1 bg-gradient-to-r from-indigo-500 to-purple-500 mx-auto rounded-full"></div>
            <p class="mt-6 text-[10px] font-black uppercase tracking-[0.3em] text-slate-500">Board of Directors, Perpustakaan Digital</p>
        </div>
    </section>

</div>
@endsection