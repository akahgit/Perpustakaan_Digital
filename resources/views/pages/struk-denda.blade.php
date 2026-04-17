<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Denda #DN-{{ str_pad($denda->id_denda, 4, '0', STR_PAD_LEFT) }}</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f1f5f9;
            color: #0f172a;
        }

        .container {
            max-width: 840px;
            margin: 0 auto;
            padding: 32px 16px;
        }

        .toolbar {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 24px;
        }

        .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 18px;
            border-radius: 14px;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            transition: 0.2s ease;
            border: 1px solid #cbd5e1;
            background: #fff;
            color: #0f172a;
            cursor: pointer;
        }

        .button.primary {
            background: #0f172a;
            color: #fff;
            border-color: #0f172a;
        }

        .receipt-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(15, 23, 42, 0.12);
        }

        .header {
            background: #0f172a;
            color: #fff;
            padding: 32px;
        }

        .eyebrow {
            font-size: 12px;
            letter-spacing: 0.35em;
            text-transform: uppercase;
            color: #94a3b8;
        }

        .title {
            margin: 12px 0 8px;
            font-size: 36px;
            font-weight: 800;
        }

        .subtitle {
            margin: 0;
            font-size: 14px;
            color: #cbd5e1;
        }

        .content {
            padding: 32px;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .info-box {
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 18px;
        }

        .info-box.success {
            background: #ecfdf5;
            border-color: #a7f3d0;
        }

        .label {
            margin: 0 0 8px;
            font-size: 11px;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: #64748b;
        }

        .value {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }

        .value.success {
            color: #047857;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .detail-text {
            margin: 0;
            line-height: 1.6;
        }

        .detail-text strong {
            display: block;
            font-size: 22px;
            color: #0f172a;
        }

        .muted {
            color: #475569;
            font-size: 14px;
        }

        .table {
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            overflow: hidden;
            margin-bottom: 24px;
        }

        .row {
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        .row.header-row {
            background: #f8fafc;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: #64748b;
        }

        .cell {
            padding: 16px;
            border-bottom: 1px solid #e2e8f0;
        }

        .cell.right {
            text-align: right;
        }

        .row:last-child .cell {
            border-bottom: 0;
        }

        .total {
            font-size: 28px;
            font-weight: 800;
            color: #e11d48;
        }

        .footer {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            padding-top: 16px;
            border-top: 1px dashed #cbd5e1;
            font-size: 12px;
            color: #64748b;
        }

        @media (max-width: 640px) {
            .toolbar,
            .grid-2,
            .detail-grid,
            .row,
            .footer {
                grid-template-columns: 1fr;
                flex-direction: column;
            }

            .title {
                font-size: 28px;
            }

            .content,
            .header {
                padding: 24px;
            }

            .cell.right {
                text-align: left;
            }
        }

        @media print {
            .print-hidden {
                display: none !important;
            }

            body {
                background: #fff !important;
            }

            .receipt-card {
                box-shadow: none !important;
                border-color: #d1d5db !important;
            }
        }
    </style>
</head>
<body>
    @php
        $peminjaman = $denda->peminjaman;
        $kondisiPengembalian = $peminjaman?->kondisi_pengembalian ?? 'baik';
        $hariTerlambat = (int) ($denda->hari_terlambat ?? 0);
        $tarifHarian = (float) ($denda->denda_per_hari ?? 0);
        $biayaTerlambat = $hariTerlambat * $tarifHarian;
        $biayaKerusakan = $kondisiPengembalian === 'rusak' ? 50000 : 0;
        $biayaKehilangan = $kondisiPengembalian === 'hilang'
            ? (float) ($peminjaman?->buku?->harga_ganti ?? $denda->jumlah_denda ?? 0)
            : 0;

        $ringkasanKondisi = match ($kondisiPengembalian) {
            'rusak' => 'Buku dikembalikan dalam kondisi rusak.',
            'hilang' => 'Buku dinyatakan hilang dan diganti sesuai harga buku.',
            default => $hariTerlambat > 0
                ? 'Buku dalam kondisi baik, tetapi pengembalian melewati jatuh tempo.'
                : 'Buku dikembalikan dalam kondisi baik dan tanpa catatan kerusakan.',
        };
    @endphp
    <div class="container">
        <div class="toolbar print-hidden">
            <a href="{{ url()->previous() }}"
               class="button">
                Kembali
            </a>
            <button onclick="window.print()"
                    class="button primary">
                Cetak Struk
            </button>
        </div>

        <div class="receipt-card">
            <div class="header">
                <p class="eyebrow">Perpustakan Digital</p>
                <h1 class="title">Struk Pembayaran Denda</h1>
                <p class="subtitle">Bukti pembayaran resmi untuk denda keterlambatan, kerusakan, atau kehilangan buku.</p>
            </div>

            <div class="content">
                <div class="grid-2">
                    <div class="info-box">
                        <p class="label">Nomor Struk</p>
                        <p class="value">#DN-{{ str_pad($denda->id_denda, 4, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <div class="info-box success">
                        <p class="label">Status</p>
                        <p class="value success">LUNAS</p>
                    </div>
                </div>

                <div class="detail-grid">
                    <div>
                        <p class="label">Anggota</p>
                        <p class="detail-text"><strong>{{ $denda->peminjaman?->anggota?->nama ?? '-' }}</strong></p>
                        <p class="muted">NIS/NISN: {{ $denda->peminjaman?->anggota?->nis_nisn ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="label">Tanggal Bayar</p>
                        <p class="detail-text"><strong>{{ $denda->tanggal_bayar?->translatedFormat('d F Y') ?? '-' }}</strong></p>
                        <p class="muted">Metode: {{ strtoupper($denda->metode_pembayaran ?? '-') }}</p>
                    </div>
                </div>

                <div class="info-box" style="margin-bottom: 24px;">
                    <p class="label">Keterangan Pengembalian</p>
                    <p class="detail-text"><strong>{{ ucfirst($kondisiPengembalian) }}</strong></p>
                    <p class="muted">{{ $ringkasanKondisi }}</p>
                    @if($peminjaman?->catatan_kondisi)
                        <p class="muted" style="margin-top: 8px;">Catatan petugas: {{ $peminjaman->catatan_kondisi }}</p>
                    @endif
                </div>

                <div class="table">
                    <div class="row header-row">
                        <div class="cell">Detail</div>
                        <div class="cell right">Nilai</div>
                    </div>
                    <div class="row">
                        <div class="cell">Judul Buku</div>
                        <div class="cell right">{{ $denda->peminjaman?->buku?->judul ?? '-' }}</div>
                    </div>

                    <div class="row">
                        <div class="cell">Hari Terlambat</div>
                        <div class="cell right">{{ $hariTerlambat }} hari</div>
                    </div>

                    <div class="row">
                        <div class="cell">Tarif per Hari</div>
                        <div class="cell right">Rp {{ number_format($tarifHarian, 0, ',', '.') }}</div>
                    </div>

                    <div class="row">
                        <div class="cell">Denda Keterlambatan</div>
                        <div class="cell right">
                            @if($biayaTerlambat > 0)
                                Rp {{ number_format($biayaTerlambat, 0, ',', '.') }}
                            @else
                                Tidak ada
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="cell">Denda Buku Rusak</div>
                        <div class="cell right">
                            @if($biayaKerusakan > 0)
                                Rp {{ number_format($biayaKerusakan, 0, ',', '.') }}
                            @else
                                Tidak ada
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="cell">Denda Buku Hilang</div>
                        <div class="cell right">
                            @if($biayaKehilangan > 0)
                                Rp {{ number_format($biayaKehilangan, 0, ',', '.') }}
                            @else
                                Tidak ada
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="cell"><strong>Total Dibayar</strong></div>
                        <div class="cell right total">Rp {{ number_format($denda->jumlah_denda, 0, ',', '.') }}</div>
                    </div>
                </div>

                <div class="footer">
                    <p>Dicetak pada {{ now()->translatedFormat('d F Y H:i') }}</p>
                    <p>Terima kasih telah menyelesaikan pembayaran denda.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
