@extends('layouts.petugas')

@section('title', 'Data Anggota')
@section('page-title', 'Data Anggota')

@section('content')
<div class="space-y-6">

    <!-- 1. NOTIFIKASI -->
    @if(session('success'))
        <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-6 py-4 rounded-xl flex items-center gap-3 shadow-lg animate-fade-in-down">
            <i class="fas fa-check-circle text-xl"></i>
            <span class="font-medium">{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="ml-auto hover:text-white transition"><i class="fas fa-times"></i></button>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-500/10 border border-red-500/20 text-red-400 px-6 py-4 rounded-xl flex items-start gap-3">
            <i class="fas fa-exclamation-circle text-xl mt-0.5"></i>
            <div>
                <h4 class="font-bold mb-1">Terjadi Kesalahan</h4>
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button onclick="this.parentElement.remove()" class="ml-auto hover:text-white transition"><i class="fas fa-times"></i></button>
        </div>
    @endif

    <!-- 2. STATISTIK RINGKAS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-[#1e293b] rounded-2xl p-6 border border-slate-700/50 shadow-xl flex items-center justify-between group hover:border-indigo-500/30 transition">
            <div>
                <p class="text-slate-400 text-sm font-medium mb-1">Total Anggota</p>
                <h3 class="text-3xl font-bold text-white">{{ number_format($totalAnggota ?? 0) }}</h3>
            </div>
            <div class="w-14 h-14 bg-indigo-500/20 rounded-xl flex items-center justify-center text-indigo-400 group-hover:scale-110 transition">
                <i class="fas fa-users text-2xl"></i>
            </div>
        </div>

        <div class="bg-[#1e293b] rounded-2xl p-6 border border-slate-700/50 shadow-xl flex items-center justify-between group hover:border-emerald-500/30 transition">
            <div>
                <p class="text-slate-400 text-sm font-medium mb-1">Anggota Aktif</p>
                <h3 class="text-3xl font-bold text-emerald-400">{{ number_format($anggotaAktif ?? 0) }}</h3>
            </div>
            <div class="w-14 h-14 bg-emerald-500/20 rounded-xl flex items-center justify-center text-emerald-400 group-hover:scale-110 transition">
                <i class="fas fa-user-check text-2xl"></i>
            </div>
        </div>

        <div class="bg-[#1e293b] rounded-2xl p-6 border border-slate-700/50 shadow-xl flex items-center justify-between group hover:border-red-500/30 transition">
            <div>
                <p class="text-slate-400 text-sm font-medium mb-1">Non-Aktif</p>
                <h3 class="text-3xl font-bold text-red-400">{{ number_format($anggotaNonAktif ?? 0) }}</h3>
            </div>
            <div class="w-14 h-14 bg-red-500/20 rounded-xl flex items-center justify-center text-red-400 group-hover:scale-110 transition">
                <i class="fas fa-user-slash text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- 3. TABEL DATA ANGGOTA -->
    <div class="bg-[#1e293b] rounded-2xl border border-slate-700/50 shadow-xl overflow-hidden">
        
        <!-- Header -->
        <div class="p-6 border-b border-slate-700/50 flex flex-col lg:flex-row justify-between items-center gap-4">
            <div class="text-center lg:text-left">
                <h3 class="text-lg font-bold text-white">Daftar Anggota Perpustakaan</h3>
                <p class="text-sm text-slate-400">Kelola data siswa/mahasiswa dan akun login mereka</p>
            </div>
            
            <a href="{{ route('petugas.anggota.create') }}" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold rounded-xl shadow-lg shadow-emerald-500/30 transition flex items-center gap-2 whitespace-nowrap">
                <i class="fas fa-plus"></i>
                <span>Tambah Anggota Baru</span>
            </a>
        </div>

        <!-- Filter & Search -->
        <div class="p-6 bg-slate-800/30 border-b border-slate-700/50">
            <form action="{{ route('petugas.anggota.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                <div class="relative flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama, NIS, atau Email..." 
                           class="w-full pl-10 pr-4 py-2.5 bg-slate-800 border border-slate-600 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                    <i class="fas fa-search absolute left-3.5 top-3 text-slate-500 text-sm"></i>
                </div>
                
                <select name="status" class="w-full md:w-48 px-4 py-2.5 bg-slate-800 border border-slate-600 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500 cursor-pointer">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="non-aktif" {{ request('status') == 'non-aktif' ? 'selected' : '' }}>Non-Aktif</option>
                </select>

                <input type="text" name="kelas" value="{{ request('kelas') }}" placeholder="Filter Kelas..." 
                       class="w-full md:w-48 px-4 py-2.5 bg-slate-800 border border-slate-600 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500">

                <div class="flex gap-2">
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold rounded-xl shadow-lg shadow-indigo-500/30 transition whitespace-nowrap">Filter</button>
                    <a href="{{ route('petugas.anggota.index') }}" class="px-6 py-2.5 bg-slate-700 hover:bg-slate-600 text-white text-sm font-semibold rounded-xl border border-slate-600 transition whitespace-nowrap">Reset</a>
                </div>
            </form>
        </div>

        <!-- Table Content -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-800/50 text-xs uppercase tracking-wider text-slate-400">
                        <th class="px-6 py-4 font-semibold w-16">No</th>
                        <th class="px-6 py-4 font-semibold">Anggota</th>
                        <th class="px-6 py-4 font-semibold">Kelas</th>
                        <th class="px-6 py-4 font-semibold">Kontak</th>
                        <th class="px-6 py-4 font-semibold text-center">Bergabung</th>
                        <th class="px-6 py-4 font-semibold text-center">Status</th>
                        <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/50 text-sm">
                    @forelse($anggotas as $index => $anggota)
                    <tr class="hover:bg-slate-800/30 transition group">
                        <td class="px-6 py-4 text-slate-400 font-medium">{{ $anggotas->firstItem() + $index }}</td>
                        
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm shadow-lg flex-shrink-0">
                                    {{ strtoupper(substr($anggota->nama, 0, 2)) }}
                                </div>
                                <div>
                                    <div class="font-bold text-white group-hover:text-indigo-300 transition">{{ $anggota->nama }}</div>
                                    <div class="text-xs text-slate-500 font-mono mt-0.5">NIS: {{ $anggota->nis_nisn }}</div>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-slate-300">
                            <span class="px-2 py-1 rounded bg-slate-700 text-xs font-medium">{{ $anggota->kelas ?? '-' }}</span>
                        </td>

                        <td class="px-6 py-4">
                            <div class="text-xs text-slate-300"><i class="fas fa-envelope mr-1 opacity-50"></i> {{ $anggota->email ?? '-' }}</div>
                            <div class="text-xs text-slate-400 mt-1"><i class="fas fa-phone mr-1 opacity-50"></i> {{ $anggota->no_telepon ?? '-' }}</div>
                        </td>

                        <td class="px-6 py-4 text-center text-slate-400 text-xs">
                            {{ $anggota->tanggal_bergabung ? $anggota->tanggal_bergabung->format('d M Y') : '-' }}
                        </td>
                        
                        <td class="px-6 py-4 text-center">
                            @if($anggota->status === 'aktif')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                    <i class="fas fa-circle text-[6px] mr-1.5"></i> Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-500/10 text-red-400 border border-red-500/20">
                                    <i class="fas fa-circle text-[6px] mr-1.5"></i> Non-Aktif
                                </span>
                            @endif
                        </td>

                        <!-- KOLOM AKSI (SHOW, EDIT, DELETE) -->
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                
                                <!-- 1. TOMBOL SHOW / DETAIL (WARNA BIRU) -->
                                <button onclick="openDetailModal({{ json_encode($anggota) }})" 
                                        class="w-8 h-8 rounded-lg bg-blue-500/10 hover:bg-blue-500 text-blue-400 hover:text-white flex items-center justify-center transition" 
                                        title="Lihat Detail">
                                    <i class="fas fa-eye text-xs"></i>
                                </button>

                                <!-- 2. TOMBOL EDIT (WARNA UNGU) -->
                                <a href="{{ route('petugas.anggota.edit', $anggota->id) }}" 
                                   class="w-8 h-8 rounded-lg bg-indigo-500/10 hover:bg-indigo-500 text-indigo-400 hover:text-white flex items-center justify-center transition" 
                                   title="Edit">
                                    <i class="fas fa-pen text-xs"></i>
                                </a>
                                
                                <!-- 3. TOMBOL HAPUS (WARNA MERAH) -->
                                <form action="{{ route('petugas.anggota.destroy', $anggota->id) }}" method="POST" onsubmit="return confirm('Hapus anggota {{ $anggota->nama }}? Data tidak bisa dikembalikan.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="w-8 h-8 rounded-lg bg-red-500/10 hover:bg-red-500 text-red-400 hover:text-white flex items-center justify-center transition" 
                                            title="Hapus">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                            <i class="fas fa-users-slash text-5xl mb-4 opacity-30"></i>
                            <p>Tidak ada data anggota ditemukan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($anggotas->hasPages())
        <div class="p-6 border-t border-slate-700/50 flex items-center justify-between">
            <span class="text-sm text-slate-400">
                Menampilkan <strong class="text-white">{{ $anggotas->firstItem() }}</strong>-<strong class="text-white">{{ $anggotas->lastItem() }}</strong> dari <strong class="text-white">{{ $anggotas->total() }}</strong>
            </span>
            <div class="flex gap-2">
                @if ($anggotas->onFirstPage())
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-800/50 text-slate-600 border border-slate-700 cursor-not-allowed"><i class="fas fa-chevron-left text-xs"></i></span>
                @else
                    <a href="{{ $anggotas->previousPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white transition border border-slate-700"><i class="fas fa-chevron-left text-xs"></i></a>
                @endif

                @foreach ($anggotas->links()->elements[0] ?? [] as $page => $url)
                    @if ($page == $anggotas->currentPage())
                        <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-indigo-600 text-white font-semibold shadow-lg shadow-indigo-500/30 border border-indigo-500">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white transition border border-slate-700">{{ $page }}</a>
                    @endif
                @endforeach

                @if ($anggotas->hasMorePages())
                    <a href="{{ $anggotas->nextPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white transition border border-slate-700"><i class="fas fa-chevron-right text-xs"></i></a>
                @else
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-800/50 text-slate-600 border border-slate-700 cursor-not-allowed"><i class="fas fa-chevron-right text-xs"></i></span>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<!-- MODAL DETAIL ANGGOTA (POPUP) -->
<div id="detailModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black/70 backdrop-blur-sm transition-opacity opacity-0" id="modalBackdrop"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            
            <!-- Modal Panel -->
            <div class="relative transform overflow-hidden rounded-2xl bg-[#1e293b] border border-slate-700 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" id="modalPanel">
                
                <!-- Header -->
                <div class="bg-gradient-to-r from-indigo-900/50 to-purple-900/50 px-6 py-4 border-b border-slate-700 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <i class="fas fa-user-circle text-indigo-400"></i> Detail Anggota
                    </h3>
                    <button onclick="closeDetailModal()" class="text-slate-400 hover:text-white transition">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Body -->
                <div class="px-6 py-6 space-y-4">
                    <!-- Avatar & Nama -->
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-16 h-16 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-2xl shadow-lg" id="m-avatar">
                            AB
                        </div>
                        <div>
                            <h4 class="text-xl font-bold text-white" id="m-nama">Nama Anggota</h4>
                            <p class="text-sm text-slate-400" id="m-nis">NIS: -</p>
                            <span class="inline-block mt-1 px-2 py-0.5 rounded text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20" id="m-status">Aktif</span>
                        </div>
                    </div>

                    <!-- Info Grid -->
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div class="bg-slate-800/50 p-3 rounded-lg border border-slate-700">
                            <div class="text-slate-500 text-xs mb-1">Kelas</div>
                            <div class="text-white font-medium" id="m-kelas">-</div>
                        </div>
                        <div class="bg-slate-800/50 p-3 rounded-lg border border-slate-700">
                            <div class="text-slate-500 text-xs mb-1">Tanggal Bergabung</div>
                            <div class="text-white font-medium" id="m-bergabung">-</div>
                        </div>
                        <div class="bg-slate-800/50 p-3 rounded-lg border border-slate-700 col-span-2">
                            <div class="text-slate-500 text-xs mb-1">Email</div>
                            <div class="text-white font-medium" id="m-email">-</div>
                        </div>
                        <div class="bg-slate-800/50 p-3 rounded-lg border border-slate-700 col-span-2">
                            <div class="text-slate-500 text-xs mb-1">No. Telepon</div>
                            <div class="text-white font-medium" id="m-telepon">-</div>
                        </div>
                        <div class="bg-slate-800/50 p-3 rounded-lg border border-slate-700 col-span-2">
                            <div class="text-slate-500 text-xs mb-1">Alamat</div>
                            <div class="text-white font-medium" id="m-alamat">-</div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="bg-slate-800/50 px-6 py-4 flex justify-end gap-3 border-t border-slate-700">
                    <button onclick="closeDetailModal()" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white text-sm font-medium rounded-lg transition">Tutup</button>
                    <a href="#" id="m-btn-edit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-lg transition flex items-center gap-2">
                        <i class="fas fa-pen"></i> Edit Data
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script Modal Logic -->
<script>
    const modal = document.getElementById('detailModal');
    const backdrop = document.getElementById('modalBackdrop');
    const panel = document.getElementById('modalPanel');

    function openDetailModal(anggota) {
        // Isi Data ke Modal
        document.getElementById('m-avatar').textContent = (anggota.nama || '?').substring(0, 2).toUpperCase();
        document.getElementById('m-nama').textContent = anggota.nama || 'Tanpa Nama';
        document.getElementById('m-nis').textContent = 'NIS: ' + (anggota.nis_nisn || '-');
        document.getElementById('m-kelas').textContent = anggota.kelas || '-';
        document.getElementById('m-email').textContent = anggota.email || '-';
        document.getElementById('m-telepon').textContent = anggota.no_telepon || '-';
        document.getElementById('m-alamat').textContent = anggota.alamat || '-';
        
        let tgl = '-';
        if(anggota.tanggal_bergabung) {
            const d = new Date(anggota.tanggal_bergabung);
            tgl = d.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
        }
        document.getElementById('m-bergabung').textContent = tgl;

        // Set Status Badge
        const statusEl = document.getElementById('m-status');
        if(anggota.status === 'aktif') {
            statusEl.className = 'inline-block mt-1 px-2 py-0.5 rounded text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20';
            statusEl.textContent = 'Aktif';
        } else {
            statusEl.className = 'inline-block mt-1 px-2 py-0.5 rounded text-xs font-semibold bg-red-500/10 text-red-400 border border-red-500/20';
            statusEl.textContent = 'Non-Aktif';
        }

        // Set Link Edit
        document.getElementById('m-btn-edit').href = '/petugas/data-anggota/' + anggota.id + '/edit';

        // Show Modal with Animation
        modal.classList.remove('hidden');
        setTimeout(() => {
            backdrop.classList.remove('opacity-0');
            panel.classList.remove('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
            panel.classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
        }, 10);
    }

    function closeDetailModal() {
        backdrop.classList.add('opacity-0');
        panel.classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
        panel.classList.add('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    // Close on click outside
    backdrop.addEventListener('click', closeDetailModal);
</script>
@endsection