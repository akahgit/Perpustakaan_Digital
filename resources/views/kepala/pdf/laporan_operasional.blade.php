<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Operasional Perpustakaan - {{ $namaBulan }} {{ $tahun }}</title>
    <style>
        @page { margin: 2cm 1.5cm; }
        body { font-family: 'Times New Roman', serif; font-size: 12pt; color: #000; line-height: 1.4; }
        
        /* Kop Surat */
        .header { text-align: center; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 20px; }
        .header h2 { margin: 0; font-size: 16pt; font-weight: bold; text-transform: uppercase; }
        .header h3 { margin: 5px 0 0; font-size: 14pt; font-weight: bold; }
        .header p { margin: 2px 0; font-size: 10pt; font-style: italic; }
        
        /* Judul Laporan */
        .report-title { text-align: center; margin: 20px 0; }
        .report-title h1 { font-size: 14pt; font-weight: bold; text-decoration: underline; margin: 0; }
        .report-title p { margin: 5px 0 0; font-size: 11pt; }

        /* Box Info */
        .info-box { border: 1px solid #000; padding: 10px; margin-bottom: 15px; background-color: #f9f9f9; }
        .info-row { display: flex; justify-content: space-between; margin-bottom: 5px; }
        .label { font-weight: bold; width: 180px; }
        
        /* Tabel Standar */
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; font-size: 10pt; }
        th, td { border: 1px solid #000; padding: 6px 8px; text-align: left; }
        th { background-color: #e0e0e0; font-weight: bold; text-align: center; text-transform: uppercase; font-size: 9pt; }
        td.text-center { text-align: center; }
        td.text-right { text-align: right; }
        
        /* Grid Statistik */
        .stats-grid { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .stat-card { width: 32%; border: 1px solid #000; padding: 10px; text-align: center; }
        .stat-val { font-size: 18pt; font-weight: bold; display: block; }
        .stat-desc { font-size: 9pt; text-transform: uppercase; }

        /* Tanda Tangan */
        .signature-section { margin-top: 40px; display: flex; justify-content: space-between; page-break-inside: avoid; }
        .sig-box { width: 45%; text-align: center; }
        .sig-space { height: 80px; }
        .sig-name { font-weight: bold; text-decoration: underline; margin-top: 10px; }
        .sig-nip { font-size: 10pt; margin-top: 2px; }

        .footer { position: fixed; bottom: 0; left: 0; right: 0; text-align: center; font-size: 8pt; border-top: 1px solid #ccc; padding-top: 5px; color: #666; }
        
        .highlight { background-color: #ffffcc; }
        .text-bold { font-weight: bold; }
    </style>
</head>
<body>

    <!-- KOP SURAT -->
    <div class="header">
        <h2>PERPUSTAKAAN DIGITAL</h2>
        <h3>SISTEM INFORMASI MANAJEMEN PERPUSTAKAAN</h3>
        <p>Jl. Pendidikan No. 123, Kota Ilmu, Indonesia | Telp: (021) 555-0199</p>
        <p>Email: info@perpusdigital.id | Website: www.perpusdigital.id</p>
    </div>

    <!-- JUDUL LAPORAN -->
    <div class="report-title">
        <h1>LAPORAN OPERASIONAL PERPUSTAKAAN</h1>
        <p>Periode: {{ $namaBulan }} {{ $tahun }}</p>
    </div>

    <!-- RINGKASAN EKSEKUTIF -->
    <div class="info-box">
        <div class="info-row"><span class="label">Tanggal Cetak</span><span>: {{ date('d F Y H:i:s') }}</span></div>
        <div class="info-row"><span class="label">Disusun Oleh</span><span>: Sistem Otomatis</span></div>
        <div class="info-row"><span class="label">Status Data</span><span>: Final & Terverifikasi</span></div>
    </div>

    <p style="text-align: justify; margin-bottom: 20px;">
        Berikut adalah ringkasan kinerja operasional perpustakaan pada periode <strong>{{ $namaBulan }} {{ $tahun }}</strong>. 
        Total transaksi peminjaman tercatat sebanyak <strong>{{ $totalPinjam }}</strong> kali, 
        {{ $statusTren == 'Naik' ? 'mengalami kenaikan' : 'mengalami penurunan' }} sebesar 
        <strong>{{ number_format(abs($trenPinjam), 1) }}%</strong> dibandingkan bulan sebelumnya. 
        Tingkat kepatuhan pengembalian tepat waktu mencapai <strong>{{ number_format($persenTepat, 1) }}%</strong>.
    </p>

    <!-- STATISTIK UTAMA (GRID) -->
    <div class="stats-grid">
        <div class="stat-card">
            <span class="stat-val">{{ $totalPinjam }}</span>
            <span class="stat-desc">Total Peminjaman</span>
        </div>
        <div class="stat-card">
            <span class="stat-val">{{ $totalKembali }}</span>
            <span class="stat-desc">Buku Dikembalikan</span>
        </div>
        <div class="stat-card">
            <span class="stat-val">{{ $anggotaBaru }}</span>
            <span class="stat-desc">Anggota Baru</span>
        </div>
    </div>

    <!-- 1. ANALISIS KATEGORI BUKU -->
    <h3 style="border-bottom: 2px solid #000; padding-bottom: 5px; margin-top: 25px;">I. MINAT BACA BERDASARKAN KATEGORI</h3>
    <table>
        <thead>
            <tr>
                <th width="10%">No</th>
                <th width="60%">Nama Kategori</th>
                <th width="15%" class="text-center">Jumlah Pinjam</th>
                <th width="15%" class="text-center">Persentase</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = $kategoriStats->sum('total'); @endphp
            @foreach($kategoriStats as $index => $kat)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $kat->nama_kategori }}</td>
                <td class="text-center text-bold">{{ $kat->total }}</td>
                <td class="text-center">{{ number_format(($kat->total / $grandTotal) * 100, 1) }}%</td>
            </tr>
            @endforeach
            <tr style="background-color: #eee; font-weight: bold;">
                <td colspan="2" class="text-center">TOTAL</td>
                <td class="text-center">{{ $grandTotal }}</td>
                <td class="text-center">100%</td>
            </tr>
        </tbody>
    </table>

    <!-- 2. TOP 5 BUKU TERPOPULER -->
    <h3 style="border-bottom: 2px solid #000; padding-bottom: 5px; margin-top: 25px;">II. LIMA BUKU TERPOPULER</h3>
    <table>
        <thead>
            <tr>
                <th width="10%">No</th>
                <th width="50%">Judul Buku</th>
                <th width="20%">Pengarang</th>
                <th width="10%" class="text-center">Kategori</th>
                <th width="10%" class="text-center">Frekuensi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topBuku as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $item->buku->judul ?? '-' }}</td>
                <td>{{ $item->buku->pengarang ?? '-' }}</td>
                <td class="text-center">{{ $item->buku->kategori->nama_kategori ?? '-' }}</td>
                <td class="text-center text-bold">{{ $item->total }}x</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- 3. LIMA ANGGOTA TERAKTIF -->
    <h3 style="border-bottom: 2px solid #000; padding-bottom: 5px; margin-top: 25px;">III. LIMA ANGGOTA PALING AKTIF</h3>
    <table>
        <thead>
            <tr>
                <th width="10%">No</th>
                <th width="40%">Nama Anggota</th>
                <th width="20%">NIS / ID</th>
                <th width="15%">Kelas / Jabatan</th>
                <th width="15%" class="text-center">Total Pinjam</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topAnggota as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $item->anggota->nama ?? '-' }}</td>
                <td>{{ $item->anggota->nis_nisn ?? '-' }}</td>
                <td>{{ $item->anggota->kelas ?? '-' }}</td>
                <td class="text-center text-bold">{{ $item->total }}x</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- 4. REKEPITULASI DENDA & KEPATUHAN -->
    <h3 style="border-bottom: 2px solid #000; padding-bottom: 5px; margin-top: 25px;">IV. REKAPITULASI DENDA & KEPATUHAN</h3>
    <table>
        <tbody>
            <tr>
                <td width="50%" class="text-bold">Total Denda Terhitung</td>
                <td class="text-right">Rp {{ number_format($totalDenda, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="text-bold">Denda Belum Lunas (Piutang)</td>
                <td class="text-right" style="color: red;">Rp {{ number_format($dendaBelumLunas, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="text-bold">Denda Sudah Lunas</td>
                <td class="text-right" style="color: green;">Rp {{ number_format($totalDenda - $dendaBelumLunas, 0, ',', '.') }}</td>
            </tr>
            <tr style="background-color: #f0f0f0;">
                <td class="text-bold">Buku Kembali Tepat Waktu</td>
                <td class="text-right">{{ $tepat }} Buku ({{ number_format($persenTepat, 1) }}%)</td>
            </tr>
            <tr style="background-color: #f0f0f0;">
                <td class="text-bold">Buku Kembali Terlambat</td>
                <td class="text-right">{{ $telat }} Buku</td>
            </tr>
        </tbody>
    </table>

    <!-- KESIMPULAN -->
    <div style="margin-top: 20px; border: 1px dashed #000; padding: 10px; font-size: 10pt;">
        <strong>Catatan Kepala Perpustakaan:</strong><br>
        @if($trenPinjam > 10)
        Kinerja perpustakaan bulan ini sangat memuaskan dengan peningkatan peminjaman signifikan. Disarankan untuk menambah stok pada kategori terpopuler.
        @elseif($trenPinjam < -10)
        Terjadi penurunan peminjaman bulan ini. Perlu dilakukan evaluasi strategi promosi atau pengecekan ketersediaan buku populer.
        @else
        Kinerja perpustakaan bulan ini stabil. Pertahankan pelayanan dan tingkatkan koleksi buku secara bertahap.
        @endif
    </div>

    <!-- TANDA TANGAN -->
    <div class="signature-section">
        <div class="sig-box">
            <p>Mengetahui,<br>Kepala Perpustakaan</p>
            <div class="sig-space"></div>
            <div class="sig-name">Dr. Kartini Putri, M.Pust.</div>
            <div class="sig-nip">NIP. 19800101 200501 2 001</div>
        </div>
        <div class="sig-box">
            <p>Dibuat oleh,<br>Petugas Administrasi</p>
            <div class="sig-space"></div>
            <div class="sig-name">Ahmad Suryana, S.Kom.</div>
            <div class="sig-nip">NIP. 19900505 201503 1 002</div>
        </div>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        Laporan ini dihasilkan secara otomatis oleh Sistem Perpustakaan Digital pada {{ date('d/m/Y H:i') }}.
    </div>

</body>
</html>