<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>History Pemesanan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2 {
            margin-bottom: 5px;
        }

        .header p {
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .filter-info {
            margin-bottom: 15px;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>LAPORAN HISTORY PEMESANAN COR</h2>
        <p>Tanggal Cetak: {{ \Carbon\Carbon::now()->locale('id')->isoFormat('DD MMMM YYYY, HH:mm') }}</p>
    </div>

    @if (!empty($filterInfo))
        <div class="filter-info">
            <b>Filter yang diterapkan:</b>
            <ul>
                @foreach ($filterInfo as $info)
                    <li>{{ $info }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Mitra</th>
                <th>Kategori Cor</th>
                <th>Luas (m²)</th>
                <th>Volume (m³)</th>
                <th>Harga</th>
                <th>Tanggal Pemesanan</th>
                <th>Tanggal Selesai</th>
            </tr>
        </thead>
        <tbody>
            @php $totalHarga = 0; @endphp
            @foreach ($data as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->mitra->nama_mitra }}</td>
                    <td>{{ $item->kategoriCor->nama_kategori }}</td>
                    <td>{{ number_format($item->luas_cor, 2) }}</td>
                    <td>{{ number_format($item->volume_cor, 2) }}</td>
                    <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->created_at)->locale('id')->isoFormat('DD MMM YYYY') }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_selesai)->locale('id')->isoFormat('DD MMM YYYY') }}</td>
                </tr>
                @php $totalHarga += $item->harga; @endphp
            @endforeach
            <tr>
                <td colspan="5" style="text-align: right;"><b>Total Harga:</b></td>
                <td><b>Rp {{ number_format($totalHarga, 0, ',', '.') }}</b></td>
                <td colspan="2"></td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>
            Dicetak oleh: {{ auth()->user()->name }}<br>
            {{ \Carbon\Carbon::now()->locale('id')->isoFormat('DD MMMM YYYY, HH:mm') }}
        </p>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>

</body>

</html>
