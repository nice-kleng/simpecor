{{-- resources/views/pemesanan/surat-jalan.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Jalan Pengecoran #{{ $pemesanan->id }}</title>
    <style>
        /* Reset CSS */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'DejaVu Sans', Arial, sans-serif;
        }

        body {
            font-size: 12pt;
            line-height: 1.5;
            color: #333;
            padding: 20px;
        }

        /* Container */
        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
        }

        /* Header Styles */
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .company-name {
            font-size: 20pt;
            font-weight: bold;
            margin: 5px 0;
        }

        .company-info {
            font-size: 10pt;
        }

        .document-title {
            font-size: 16pt;
            font-weight: bold;
            margin: 15px 0 5px;
            text-decoration: underline;
        }

        /* Info Section */
        .info-section {
            display: flex;
            margin-bottom: 20px;
            width: 100%;
        }

        .info-left {
            width: 50%;
            float: left;
        }

        .info-right {
            width: 50%;
            float: right;
            text-align: right;
        }

        .info-item {
            margin-bottom: 5px;
            font-size: 10pt;
        }

        .label {
            font-weight: bold;
        }

        /* Detail Table */
        .detail-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .detail-table th,
        .detail-table td {
            border: 1px solid #333;
            padding: 8px;
            font-size: 10pt;
        }

        .detail-table th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }

        .detail-table td {
            vertical-align: top;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        /* Notes Section */
        .notes {
            font-size: 10pt;
            margin: 15px 0;
        }

        .notes ol {
            margin-left: 25px;
        }

        .notes li {
            margin-bottom: 5px;
        }

        /* Signature Section - IMPROVED */
        .signatures-container {
            margin-top: 50px;
            width: 100%;
            page-break-inside: avoid;
        }

        .signatures {
            width: 100%;
            display: table;
            table-layout: fixed;
        }

        .signature-box {
            display: table-cell;
            width: 33.33%;
            text-align: center;
            vertical-align: top;
            padding: 0 10px;
        }

        .signature-line {
            border-top: 1px solid #333;
            margin: 60px auto 5px;
            width: 80%;
        }

        .signature-name {
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 10pt;
        }

        .signature-position {
            font-size: 9pt;
        }

        /* Footer */
        .footer {
            margin-top: 40px;
            padding-top: 10px;
            border-top: 1px solid #333;
            font-size: 9pt;
            text-align: center;
            color: #666;
            page-break-inside: avoid;
        }

        /* Clear float */
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="company-name">PT. JASA PENGECORAN</div>
            <div class="company-info">Jl. Contoh No. 123, Kota, Indonesia</div>
            <div class="company-info">Telp: (021) 123-4567 | Email: info@jasapengecoran.com</div>
            <div class="document-title">SURAT JALAN PENGECORAN</div>
        </div>

        <div class="info-section clearfix">
            <div class="info-left">
                <div class="info-item">
                    <span class="label">No. Surat Jalan:</span>
                    SJ-{{ $pemesanan->id }}/JPC/{{ date('m/Y') }}
                </div>
                <div class="info-item">
                    <span class="label">Mitra:</span>
                    {{ $pemesanan->mitra->nama_mitra }}
                </div>
                <div class="info-item">
                    <span class="label">Alamat Pengecoran:</span>
                    {{ $pemesanan->mitra->alamat ?? '[Alamat Lokasi Pengecoran]' }}
                </div>
                <div class="info-item">
                    <span class="label">No. Telp Mitra:</span>
                    {{ $pemesanan->mitra->telepon ?? '[Nomor Telepon Mitra]' }}
                </div>
            </div>

            <div class="info-right">
                <div class="info-item">
                    <span class="label">Tanggal Cetak:</span>
                    {{ date('d-m-Y') }}
                </div>
                <div class="info-item">
                    <span class="label">Tanggal Pengecoran:</span>
                    {{ date('d-m-Y', strtotime($pemesanan->tanggal_pengecoran)) }}
                </div>
                <div class="info-item">
                    <span class="label">No. Pemesanan:</span>
                    PO-{{ $pemesanan->id }}
                </div>
                <div class="info-item">
                    <span class="label">Status Pembayaran:</span>
                    {{ ucfirst($pemesanan->status_pembayaran) }}
                </div>
            </div>
        </div>

        <table class="detail-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="30%">Kategori Pengecoran</th>
                    <th width="13%">Luas (m²)</th>
                    <th width="13%">Volume (m³)</th>
                    <th width="13%">Jml Unit</th>
                    <th width="13%">Jml Petugas</th>
                    <th width="13%">Harga</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="center">1</td>
                    <td>{{ $pemesanan->kategoriCor->nama_kategori }}</td>
                    <td class="center">{{ number_format($pemesanan->luas_cor, 2) }} (m²)</td>
                    <td class="center">{{ number_format($pemesanan->volume_cor, 2) }} (m³)</td>
                    <td class="center">{{ (int) $pemesanan->jumlah_unit_cor }}</td>
                    <td class="center">{{ (int) $pemesanan->jumlah_petugas }}</td>
                    <td class="right">Rp.
                        {{ number_format($pemesanan->harga ?? $pemesanan->getHarga(), 0, ',', '.') }}
                    </td>
                </tr>
                <tr>
                    <td colspan="7" height="60" valign="top">
                        <strong>Catatan Tambahan:</strong><br>
                        {{ $pemesanan->keterangan_pembayaran ?? '-' }}
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="notes">
            <p><strong>Keterangan:</strong></p>
            <ol>
                <li>Surat jalan ini merupakan bukti pengiriman material dan petugas untuk proses pengecoran.</li>
                <li>Harap periksa semua material dan peralatan sebelum ditandatangani.</li>
                <li>Status pengerjaan:
                    <strong>{{ ucwords(str_replace('_', ' ', $pemesanan->status_pengerjaan)) }}</strong>
                </li>
                <li>Dokumen ini dibawa oleh petugas pengecoran dan harus ditandatangani oleh penerima.</li>
                <li>Foto lokasi pengecoran telah didokumentasikan dan disetujui kedua belah pihak.</li>
            </ol>
        </div>

        <!-- Bagian tanda tangan yang diperbaiki -->
        <div class="signatures-container">
            <div class="signatures">
                {{-- <div class="signature-box">
                    <div class="signature-line"></div>
                    <div class="signature-name">Pengirim</div>
                    <div class="signature-position">PT. Jasa Pengecoran</div>
                </div> --}}
                <div class="signature-box">
                    <div class="signature-line"></div>
                    <div class="signature-name">Koordinator Lapangan</div>
                    <div class="signature-position">{{ Str::upper($pemesanan->pj_lapangan ?? 'Koordinator Lapangan') }}
                    </div>
                </div>
                <div class="signature-box">
                    <div class="signature-line"></div>
                    <div class="signature-name">Penerima</div>
                    <div class="signature-position">{{ $pemesanan->mitra->nama_mitra }}</div>
                </div>
            </div>
        </div>

        <div class="footer">
            <p>Dokumen ini dicetak pada {{ date('d-m-Y H:i') }} WIB dan sah tanpa tanda tangan dan stempel.</p>
            <p>© {{ date('Y') }} PT. Jasa Pengecoran - Semua hak dilindungi.</p>
        </div>
    </div>
</body>

</html>
