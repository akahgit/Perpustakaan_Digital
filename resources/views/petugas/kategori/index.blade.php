@extends('layouts.petugas')

@section('title', 'Manajemen Kategori Buku')
@section('page-title', 'Kategori Buku')
@section('page-subtitle', 'Kelola kategori dan pengelompokan koleksi buku')

@section('content')
<div class="space-y-5 animate-fade-in-down"
     x-data="kategoriPage()"
     x-init="init()">

    {{-- ══ STATISTIK ══ --}}
    <div class="grid grid-cols-2 md:grid-cols-2 gap-4">
        <div class="bg-[#1e293b] rounded-2xl p-5 border border-white/5 shadow-xl">
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Total Kategori</p>
            <span class="text-3xl font-extrabold text-white">{{ $totalKategori }}</span>
        </div>
        <button @click="openAddModal()"
                class="bg-indigo-600 hover:bg-indigo-500 rounded-2xl p-5 shadow-xl flex flex-col items-center justify-center text-center group transition-all">
            <i class="fas fa-plus text-white text-2xl mb-2 group-hover:scale-110 transition-transform"></i>
            <span class="text-sm font-bold text-white">Tambah Kategori</span>
        </button>
    </div>

    {{-- ══ TABEL KATEGORI ══ --}}
    <div class="bg-[#1e293b] rounded-2xl border border-white/5 shadow-xl overflow-hidden">
        <div class="px-5 py-4 border-b border-white/5 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-white">Daftar Kategori</h3>
                <p class="text-xs text-slate-500 mt-0.5">Kategori digunakan untuk mengelompokkan koleksi buku</p>
            </div>
            <button @click="openAddModal()"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold rounded-xl shadow-lg transition">
                <i class="fas fa-plus text-sm"></i> Tambah
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-white/3 border-b border-white/5">
                        <th class="px-5 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-[0.12em] w-10">#</th>
                        <th class="px-5 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-[0.12em]">Kategori</th>
                        <th class="px-5 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-[0.12em]">Deskripsi</th>
                        <th class="px-5 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-[0.12em] text-center">Jml Buku</th>
                        <th class="px-5 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-[0.12em] text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/3">
                    @forelse($kategoris as $index => $kat)
                    <tr class="table-row-hover transition-colors group">
                        <td class="px-5 py-3.5 text-xs text-slate-600 tabular-nums">{{ $kategoris->firstItem() + $index }}</td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-3">
                                {{-- Color swatch --}}
                                <div class="w-3 h-8 rounded-sm flex-shrink-0" style="background-color: {{ $kat->warna }}"></div>
                                <div>
                                    <div class="text-sm font-bold text-white">{{ $kat->nama_kategori }}</div>
                                    <div class="text-[10px] text-slate-600 font-mono">/{{ $kat->slug }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3.5 max-w-xs">
                            <p class="text-xs text-slate-400 truncate">{{ $kat->deskripsi ?? '—' }}</p>
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            <span class="text-sm font-bold text-indigo-400">{{ $kat->bukus_count }}</span>
                            <span class="text-xs text-slate-600 ml-1">buku</span>
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <button @click="openEditModal({{ $kat->id_kategori }}, '{{ addslashes($kat->nama_kategori) }}', '{{ addslashes($kat->deskripsi ?? '') }}', '{{ $kat->warna }}')"
                                        class="w-8 h-8 rounded-lg bg-indigo-500/10 hover:bg-indigo-500 border border-indigo-500/20 text-indigo-400 hover:text-white flex items-center justify-center transition-all"
                                        title="Edit">
                                    <i class="fas fa-pen text-xs"></i>
                                </button>
                                <button @click="openDeleteModal({{ $kat->id_kategori }}, '{{ addslashes($kat->nama_kategori) }}', {{ $kat->bukus_count }})"
                                        class="w-8 h-8 rounded-lg bg-rose-500/10 hover:bg-rose-500 border border-rose-500/20 text-rose-400 hover:text-white flex items-center justify-center transition-all"
                                        title="Hapus">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-14 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-14 h-14 bg-white/3 rounded-2xl flex items-center justify-center mb-4">
                                    <i class="fas fa-tags text-slate-600 text-2xl"></i>
                                </div>
                                <p class="text-sm text-slate-400">Belum ada kategori.</p>
                                <button @click="openAddModal()" class="mt-2 text-xs text-indigo-400 hover:text-indigo-300 font-semibold transition">
                                    Tambah kategori pertama →
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($kategoris->hasPages())
        <div class="px-5 py-4 border-t border-white/5 flex items-center justify-between">
            <span class="text-xs text-slate-500">
                Menampilkan {{ $kategoris->firstItem() }}–{{ $kategoris->lastItem() }} dari {{ $kategoris->total() }} kategori
            </span>
            <div class="flex gap-1.5">
                @if($kategoris->onFirstPage())
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/3 text-slate-700 border border-white/5 cursor-not-allowed"><i class="fas fa-chevron-left text-xs"></i></span>
                @else
                    <a href="{{ $kategoris->previousPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/5 hover:bg-white/10 text-slate-400 hover:text-white border border-white/8 transition"><i class="fas fa-chevron-left text-xs"></i></a>
                @endif
                @if($kategoris->hasMorePages())
                    <a href="{{ $kategoris->nextPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/5 hover:bg-white/10 text-slate-400 hover:text-white border border-white/8 transition"><i class="fas fa-chevron-right text-xs"></i></a>
                @else
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/3 text-slate-700 border border-white/5 cursor-not-allowed"><i class="fas fa-chevron-right text-xs"></i></span>
                @endif
            </div>
        </div>
        @endif
    </div>

    {{-- ══ MODAL TAMBAH/EDIT ══ --}}
    <div x-show="modal.open"
         x-cloak
         class="fixed inset-0 z-[999] flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        <div class="absolute inset-0 modal-backdrop" @click="modal.open = false"></div>

        <div class="relative z-10 w-full max-w-md bg-[#1e293b] border border-white/10 rounded-2xl shadow-2xl overflow-hidden animate-scale-in">
            <div class="h-0.5 bg-gradient-to-r from-indigo-500 via-violet-500 to-indigo-500"></div>

            <div class="px-6 py-5 border-b border-white/5 flex items-center justify-between">
                <h2 class="text-base font-bold text-white" x-text="modal.isEdit ? 'Edit Kategori' : 'Tambah Kategori Baru'"></h2>
                <button @click="modal.open = false" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-500 hover:text-white hover:bg-white/10 transition">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>

            <form :action="modal.isEdit ? modal.editAction : '{{ route('petugas.kategori.store') }}'" method="POST" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="_method" value="PUT" :disabled="!modal.isEdit">

                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Nama Kategori *</label>
                    <input type="text" name="nama_kategori" :value="modal.nama" required
                           class="w-full px-4 py-2.5 bg-slate-900/60 border border-white/8 rounded-xl text-sm text-white placeholder-slate-500
                                  focus:outline-none focus:border-indigo-500/60 focus:ring-2 focus:ring-indigo-500/20 transition"
                           placeholder="mis: Fiksi, Sains, Sejarah">
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Deskripsi</label>
                    <textarea name="deskripsi" rows="2" :value="modal.deskripsi"
                              class="w-full px-4 py-2.5 bg-slate-900/60 border border-white/8 rounded-xl text-sm text-white placeholder-slate-500
                                     focus:outline-none focus:border-indigo-500/60 focus:ring-2 focus:ring-indigo-500/20 transition resize-none"
                              placeholder="Deskripsi singkat kategori…"></textarea>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Warna Badge</label>
                    <div class="flex items-center gap-3">
                        <input type="color" name="warna" :value="modal.warna"
                               class="w-10 h-10 rounded-lg border border-white/10 bg-slate-900 cursor-pointer">
                        <span class="text-xs text-slate-500">Klik untuk pilih</span>
                    </div>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" @click="modal.open = false"
                            class="flex-1 px-4 py-2.5 bg-white/5 hover:bg-white/10 border border-white/10 text-slate-300 text-sm font-semibold rounded-xl transition">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-500/25 transition flex items-center justify-center gap-2">
                        <i class="fas fa-save text-sm"></i>
                        <span x-text="modal.isEdit ? 'Perbarui' : 'Simpan'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ══ MODAL HAPUS ══ --}}
    <div x-show="deleteModal.open"
         x-cloak
         class="fixed inset-0 z-[999] flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100">
        <div class="absolute inset-0 modal-backdrop" @click="deleteModal.open = false"></div>
        <div class="relative z-10 w-full max-w-sm bg-[#1e293b] border border-white/10 rounded-2xl shadow-2xl overflow-hidden animate-scale-in">
            <div class="h-0.5 bg-gradient-to-r from-rose-600 to-red-500"></div>
            <div class="p-6 text-center">
                <div class="w-14 h-14 bg-rose-500/15 border-2 border-rose-500/30 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-triangle-exclamation text-rose-400 text-xl"></i>
                </div>
                <h2 class="text-base font-extrabold text-white mb-2">Hapus Kategori?</h2>
                <p class="text-sm text-slate-400 mb-1">Kategori <strong class="text-rose-300" x-text="deleteModal.nama"></strong> akan dihapus.</p>
                <p x-show="deleteModal.jumlahBuku > 0" class="text-xs text-amber-400 mt-2 bg-amber-500/10 border border-amber-500/20 rounded-lg p-2">
                    ⚠ Kategori ini dipakai oleh <strong x-text="deleteModal.jumlahBuku"></strong> buku dan tidak dapat dihapus!
                </p>
                <form :action="deleteModal.action" method="POST" class="flex gap-3 mt-5">
                    @csrf @method('DELETE')
                    <button type="button" @click="deleteModal.open = false"
                            class="flex-1 px-4 py-2.5 bg-white/5 border border-white/10 text-slate-300 text-sm font-semibold rounded-xl hover:bg-white/10 transition">
                        Batal
                    </button>
                    <button type="submit" :disabled="deleteModal.jumlahBuku > 0"
                            class="flex-1 px-4 py-2.5 bg-rose-600 hover:bg-rose-500 text-white text-sm font-bold rounded-xl shadow-lg transition disabled:opacity-40 disabled:cursor-not-allowed">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    function kategoriPage() {
        return {
            modal: { open: false, isEdit: false, nama: '', deskripsi: '', warna: '#6366f1', editAction: '' },
            deleteModal: { open: false, nama: '', jumlahBuku: 0, action: '' },

            init() {},

            openAddModal() {
                this.modal = { open: true, isEdit: false, nama: '', deskripsi: '', warna: '#6366f1', editAction: '' };
            },

            openEditModal(id, nama, deskripsi, warna) {
                this.modal = {
                    open: true, isEdit: true, nama, deskripsi, warna,
                    editAction: `/petugas/kategori/${id}`,
                };
            },

            openDeleteModal(id, nama, jumlahBuku) {
                this.deleteModal = { open: true, nama, jumlahBuku, action: `/petugas/kategori/${id}` };
            }
        };
    }
</script>
@endpush
