@extends('layouts.petugas')

@section('title', 'Kelola Denda')
@section('page-title', 'Manajemen Denda')

@section('content')
<div class="space-y-6" x-data="{}">

    <!-- 1. STATISTIK KEUANGAN DENDA (DINAMIS) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Card 1: Total Piutang (Belum Lunas) -->
        <div class="bg-[#1e293b] rounded-2xl p-6 border border-amber-500/20 shadow-xl flex items-center justify-between relative overflow-hidden group hover:border-amber-500/40 transition">
            <div class="absolute right-0 top-0 w-24 h-24 bg-amber-500/10 rounded-full blur-2xl -mr-10 -mt-10"></div>
            <div class="relative z-10">
                <p class="text-slate-400 text-sm font-medium mb-1">Total Piutang (Belum Lunas)</p>
                <h3 class="text-3xl font-bold text-amber-400">Rp {{ number_format($totalPiutang ?? 0, 0, ',', '.') }}</h3>
                <p class="text-xs text-amber-200/60 mt-1">Wajib ditagih</p>
            </div>
            <div class="w-14 h-14 bg-amber-500/20 rounded-xl flex items-center justify-center text-amber-400 relative z-10 group-hover:scale-110 transition">
                <i class="fas fa-exclamation-triangle text-2xl"></i>
            </div>
        </div>

        <!-- Card 2: Total Pendapatan (Sudah Lunas) -->
        <div class="bg-[#1e293b] rounded-2xl p-6 border border-emerald-500/20 shadow-xl flex items-center justify-between relative overflow-hidden group hover:border-emerald-500/40 transition">
            <div class="absolute right-0 top-0 w-24 h-24 bg-emerald-500/10 rounded-full blur-2xl -mr-10 -mt-10"></div>
            <div class="relative z-10">
                <p class="text-slate-400 text-sm font-medium mb-1">Total Pendapatan (Lunas)</p>
                <h3 class="text-3xl font-bold text-emerald-400">Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}</h3>
                <p class="text-xs text-emerald-200/60 mt-1">Masuk kas perpustakaan</p>
            </div>
            <div class="w-14 h-14 bg-emerald-500/20 rounded-xl flex items-center justify-center text-emerald-400 relative z-10 group-hover:scale-110 transition">
                <i class="fas fa-check-circle text-2xl"></i>
            </div>
        </div>

        <!-- Card 3: Jumlah Transaksi Denda -->
        <div class="bg-[#1e293b] rounded-2xl p-6 border border-slate-700/50 shadow-xl flex items-center justify-between relative overflow-hidden group hover:border-indigo-500/30 transition">
            <div class="absolute right-0 top-0 w-24 h-24 bg-indigo-500/10 rounded-full blur-2xl -mr-10 -mt-10"></div>
            <div class="relative z-10">
                <p class="text-slate-400 text-sm font-medium mb-1">Total Kasus Denda</p>
                <h3 class="text-3xl font-bold text-white">{{ $dendas->total() }} <span class="text-lg text-slate-500 font-normal">Kasus</span></h3>
                <p class="text-xs text-slate-500 mt-1">{{ $dendas->where('status_pembayaran', 'belum_lunas')->count() }} belum dibayar</p>
            </div>
            <div class="w-14 h-14 bg-indigo-500/20 rounded-xl flex items-center justify-center text-indigo-400 relative z-10 group-hover:scale-110 transition">
                <i class="fas fa-file-invoice-dollar text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- 2. TABEL DAFTAR DENDA -->
    <div class="bg-[#1e293b] rounded-2xl border border-slate-700/50 shadow-xl overflow-hidden">
        
        <!-- Header Tabel & Filter -->
        <div class="p-6 border-b border-slate-700/50 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div>
                <h3 class="text-lg font-bold text-white">Daftar Denda & Penggantian</h3>
                <p class="text-sm text-slate-400">Kelola pembayaran keterlambatan, buku rusak, dan buku hilang</p>
            </div>
            
            <!-- Form Filter -->
            <form action="{{ route('petugas.denda.index') }}" method="GET" class="flex items-center gap-3 w-full sm:w-auto">
                <div class="relative flex-1 sm:flex-none">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama Anggota..." 
                           class="w-full sm:w-64 pl-10 pr-4 py-2.5 bg-slate-800/50 border border-slate-600 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 transition">
                    <i class="fas fa-search absolute left-3.5 top-3 text-slate-500 text-sm"></i>
                </div>
                <button type="submit" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold rounded-xl shadow-lg transition whitespace-nowrap">
                    Cari
                </button>
                <select name="jenis" class="px-4 py-2.5 bg-slate-800/50 border border-slate-600 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500">
                    <option value="">Semua Jenis</option>
                    <option value="keterlambatan" {{ request('jenis') === 'keterlambatan' ? 'selected' : '' }}>Keterlambatan</option>
                    <option value="kerusakan" {{ request('jenis') === 'kerusakan' ? 'selected' : '' }}>Kerusakan</option>
                    <option value="kehilangan" {{ request('jenis') === 'kehilangan' ? 'selected' : '' }}>Kehilangan</option>
                    <option value="gabungan" {{ request('jenis') === 'gabungan' ? 'selected' : '' }}>Gabungan</option>
                </select>
                <a href="{{ route('petugas.denda.index') }}" class="px-4 py-2.5 bg-slate-700 hover:bg-slate-600 text-white text-sm font-semibold rounded-xl border border-slate-600 transition whitespace-nowrap">
                    Reset
                </a>
            </form>
        </div>

        <!-- Table Content -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-800/50 text-xs uppercase tracking-wider text-slate-400">
                        <th class="px-6 py-4 font-semibold">ID Denda</th>
                        <th class="px-6 py-4 font-semibold">Anggota</th>
                        <th class="px-6 py-4 font-semibold">Buku</th>
                        <th class="px-6 py-4 font-semibold text-center">Jenis</th>
                        <th class="px-6 py-4 font-semibold text-right">Jumlah Denda</th>
                        <th class="px-6 py-4 font-semibold text-center">Status</th>
                        <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/50 text-sm">
                    @forelse($dendas as $denda)
                    <tr class="hover:bg-slate-800/30 transition group">
                        <!-- ID Denda -->
                        <td class="px-6 py-4 font-mono text-red-400 font-medium">
                            #DN-{{ str_pad($denda->id_denda, 4, '0', STR_PAD_LEFT) }}
                        </td>
                        
                        <!-- Anggota -->
                        <td class="px-6 py-4">
                            @if($denda->peminjaman && $denda->peminjaman->anggota)
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-indigo-500/20 flex items-center justify-center text-indigo-400 text-xs font-bold border border-indigo-500/30">
                                        {{ substr($denda->peminjaman->anggota->nama, 0, 2) }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-white group-hover:text-indigo-300 transition">{{ $denda->peminjaman->anggota->nama }}</div>
                                        <div class="text-[10px] text-slate-500 font-mono">NIS: {{ $denda->peminjaman->anggota->nis_nisn }}</div>
                                    </div>
                                </div>
                            @else
                                <span class="text-slate-500 italic">Data anggota dihapus</span>
                            @endif
                        </td>

                        <!-- Buku -->
                        <td class="px-6 py-4">
                            @if($denda->peminjaman && $denda->peminjaman->buku)
                                <div class="text-slate-300 line-clamp-1">{{ $denda->peminjaman->buku->judul }}</div>
                                <div class="text-[10px] text-slate-500">{{ $denda->peminjaman->buku->pengarang }}</div>
                            @else
                                <span class="text-slate-500 italic">Buku dihapus</span>
                            @endif
                        </td>

                        <!-- Jenis -->
                        <td class="px-6 py-4 text-center">
                            <span class="inline-block px-2 py-1 rounded text-xs font-bold border
                                {{ $denda->jenis_denda === 'kehilangan' ? 'bg-rose-500/10 text-rose-400 border-rose-500/20' : '' }}
                                {{ $denda->jenis_denda === 'kerusakan' ? 'bg-amber-500/10 text-amber-400 border-amber-500/20' : '' }}
                                {{ $denda->jenis_denda === 'gabungan' ? 'bg-indigo-500/10 text-indigo-400 border-indigo-500/20' : '' }}
                                {{ $denda->jenis_denda === 'keterlambatan' ? 'bg-red-500/10 text-red-400 border-red-500/20' : '' }}">
                                {{ $denda->label_jenis_denda }}
                            </span>
                            @if($denda->hari_terlambat > 0)
                                <div class="text-[10px] text-slate-500 mt-1">{{ $denda->hari_terlambat }} hari telat</div>
                            @endif
                        </td>
                        
                        <!-- Jumlah Denda -->
                        <td class="px-6 py-4 text-right font-bold text-red-400 text-base">
                            Rp {{ number_format($denda->jumlah_denda, 0, ',', '.') }}
                        </td>

                        <!-- Status Badge -->
                        <td class="px-6 py-4 text-center">
                            @if($denda->status_pembayaran === 'belum_lunas')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                    <i class="fas fa-clock text-[8px] mr-1.5"></i> Belum Lunas
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                    <i class="fas fa-check-circle text-[8px] mr-1.5"></i> Lunas
                                </span>
                            @endif
                        </td>

                        <!-- Aksi -->
                        <td class="px-6 py-4 text-center">
                            @if($denda->status_pembayaran === 'belum_lunas')
                                <!-- Tombol Bayar (Memicu Modal) -->
                                <button onclick="openModal({{ $denda->id_denda }}, '{{ $denda->peminjaman->anggota->nama ?? 'Anggota' }}', {{ $denda->jumlah_denda }})" 
                                        class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold rounded-lg shadow-lg shadow-emerald-500/30 transition flex items-center gap-2 mx-auto">
                                    <i class="fas fa-money-bill"></i>
                                    Bayar
                                </button>
                            @else
                                <a href="{{ route('denda.receipt', $denda->id_denda) }}"
                                   target="_blank"
                                   class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white text-xs font-semibold rounded-lg border border-slate-600 transition flex items-center gap-2 mx-auto w-fit">
                                    <i class="fas fa-print"></i>
                                    Cetak Struk
                                </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-slate-500">
                                <i class="fas fa-receipt text-5xl mb-4 opacity-20"></i>
                                <p class="text-lg font-medium">Belum ada data denda.</p>
                                <p class="text-sm">Denda akan muncul otomatis saat pengembalian terlambat.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($dendas->hasPages())
        <div class="p-6 border-t border-slate-700/50 flex items-center justify-between">
            <span class="text-sm text-slate-400">
                Menampilkan <strong class="text-white">{{ $dendas->firstItem() }}</strong>-<strong class="text-white">{{ $dendas->lastItem() }}</strong> dari <strong class="text-white">{{ $dendas->total() }}</strong> denda
            </span>
            
            <div class="flex gap-2">
                {{-- Previous --}}
                @if ($dendas->onFirstPage())
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-800/50 text-slate-600 border border-slate-700 cursor-not-allowed">
                        <i class="fas fa-chevron-left text-xs"></i>
                    </span>
                @else
                    <a href="{{ $dendas->previousPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white transition border border-slate-700">
                        <i class="fas fa-chevron-left text-xs"></i>
                    </a>
                @endif

                {{-- Numbers --}}
                @foreach ($dendas->links()->elements[0] ?? [] as $page => $url)
                    @if ($page == $dendas->currentPage())
                        <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-indigo-600 text-white font-semibold shadow-lg shadow-indigo-500/30 border border-indigo-500">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white transition border border-slate-700">{{ $page }}</a>
                    @endif
                @endforeach

                {{-- Next --}}
                @if ($dendas->hasMorePages())
                    <a href="{{ $dendas->nextPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white transition border border-slate-700">
                        <i class="fas fa-chevron-right text-xs"></i>
                    </a>
                @else
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-800/50 text-slate-600 border border-slate-700 cursor-not-allowed">
                        <i class="fas fa-chevron-right text-xs"></i>
                    </span>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<!-- MODAL PEMBAYARAN DENDA -->
<div id="paymentModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden flex items-center justify-center opacity-0 transition-opacity duration-300">
    <div class="bg-[#1e293b] rounded-2xl border border-slate-700 w-full max-w-md p-6 shadow-2xl transform scale-95 transition-transform duration-300" id="modalContent">
        
        <!-- Modal Header -->
        <div class="flex justify-between items-center mb-6 border-b border-slate-700 pb-4">
            <h3 class="text-xl font-bold text-white flex items-center gap-2">
                <i class="fas fa-cash-register text-emerald-400"></i>
                Pembayaran Denda
            </h3>
            <button onclick="closeModal()" class="text-slate-400 hover:text-white transition">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <form id="paymentForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <input type="hidden" id="modalDendaId" name="id_denda_hidden">

            <div class="space-y-4">
                <!-- Info Anggota -->
                <div class="bg-slate-800/50 rounded-xl p-4 border border-slate-700">
                    <p class="text-xs text-slate-400 uppercase mb-1">Anggota</p>
                    <p class="text-white font-bold" id="modalNamaAnggota">-</p>
                </div>

                <!-- Info Jumlah -->
                <div class="bg-slate-800/50 rounded-xl p-4 border border-slate-700 flex justify-between items-center">
                    <div>
                        <p class="text-xs text-slate-400 uppercase mb-1">Total Denda</p>
                        <p class="text-red-400 font-bold text-lg" id="modalJumlahDenda">-</p>
                    </div>
                    <i class="fas fa-tag text-slate-600 text-2xl"></i>
                </div>

                <!-- Metode Pembayaran -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Metode Pembayaran</label>
                    <select name="metode_pembayaran" required class="w-full bg-slate-800 border border-slate-600 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-emerald-500">
                        <option value="tunai">Tunai (Cash)</option>
                        <option value="transfer">Transfer Bank</option>
                        <option value="qris">QRIS / E-Wallet</option>
                    </select>
                </div>

                <!-- Upload Bukti (Muncul jika non-tunai) -->
                <div id="buktiUploadSection" class="hidden">
                    <label class="block text-sm font-medium text-slate-300 mb-2">Upload Bukti Pembayaran</label>
                    <input type="file" name="bukti_pembayaran" accept="image/*" class="w-full text-sm text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-emerald-600 file:text-white hover:file:bg-emerald-700">
                    <p class="text-xs text-slate-500 mt-1">Format: JPG, PNG. Max 2MB.</p>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="mt-8 flex gap-3">
                <button type="button" onclick="closeModal()" class="flex-1 px-4 py-3 rounded-xl border border-slate-600 text-slate-300 hover:bg-slate-800 transition font-medium">
                    Batal
                </button>
                <button type="submit" 
                        class="flex-1 px-4 py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl shadow-lg shadow-emerald-500/30 transition flex items-center justify-center gap-2">
                    <i class="fas fa-check-circle"></i> Konfirmasi Bayar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Script Modal & Logic -->
<script>
    const modal = document.getElementById('paymentModal');
    const modalContent = document.getElementById('modalContent');
    const form = document.getElementById('paymentForm');
    const metodeSelect = document.querySelector('select[name="metode_pembayaran"]');
    const buktiSection = document.getElementById('buktiUploadSection');

    // Fungsi Buka Modal
    function openModal(id, nama, jumlah) {
        // Set Data
        document.getElementById('modalDendaId').value = id;
        document.getElementById('modalNamaAnggota').textContent = nama;
        document.getElementById('modalJumlahDenda').textContent = 'Rp ' + jumlah.toLocaleString('id-ID');
        
        // Set Action URL Form
        form.action = "{{ route('petugas.denda.update', ':id') }}".replace(':id', id);

        // Show Modal
        modal.classList.remove('hidden');
        // Trigger reflow untuk animasi
        void modal.offsetWidth; 
        modal.classList.remove('opacity-0');
        modalContent.classList.remove('scale-95');
        modalContent.classList.add('scale-100');
    }

    // Fungsi Tutup Modal
    function closeModal() {
        modal.classList.add('opacity-0');
        modalContent.classList.remove('scale-100');
        modalContent.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    // Toggle Upload Bukti
    metodeSelect.addEventListener('change', function() {
        if (this.value === 'tunai') {
            buktiSection.classList.add('hidden');
            // Hapus requirement file jika tunai
            document.querySelector('input[name="bukti_pembayaran"]').removeAttribute('required');
        } else {
            buktiSection.classList.remove('hidden');
            // Opsional: tambahkan required jika perlu validasi ketat
            // document.querySelector('input[name="bukti_pembayaran"]').setAttribute('required', 'true');
        }
    });

    // Close on click outside
    modal.addEventListener('click', function(e) {
        if (e.target === modal) closeModal();
    });
</script>
@endsection
