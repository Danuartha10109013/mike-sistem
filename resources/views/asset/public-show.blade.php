<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Detail Aset Publik - {{ $asset->name }}</title>
    <style>
        body {
            font-family: sans-serif;
            max-width: 900px;
            margin: 20px auto;
        }
        h1 {
            margin-bottom: 0.5em;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2em;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background: #f4f4f4;
            text-align: left;
            width: 200px;
            vertical-align: top;
        }
    </style>
</head>
<body>

    <h1>Detail Aset: {{ $asset->name }}</h1>

    <table>
        <tr>
            <th>Nomor Aset</th>
            <td>{{ $asset->number }}</td>
        </tr>
        <tr>
            <th>Kategori</th>
            <td>{{ $asset->category?->name ?? '-' }}</td>
        </tr>
        <tr>
            <th>Ruangan</th>
            <td>{{ $asset->room?->name ?? '-' }}</td>
        </tr>
        <tr>
            <th>Pemilik / User</th>
            <td>{{ $asset->user?->name ?? '-' }}</td>
        </tr>
        <tr>
            <th>Merek / Brand</th>
            <td>{{ $asset->brand?->name ?? '-' }}</td>
        </tr>
        <tr>
            <th>Kondisi</th>
            <td>{{ $asset->condition->name }}</td>
        </tr>
    </table>

    <h2>Riwayat Pembelian (Disetujui)</h2>
    @if($asset->approvedPurchases->isEmpty())
        <p>Tidak ada data pembelian disetujui.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Tanggal Pembelian</th>
                    <th>Harga</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($asset->approvedPurchases as $purchase)
                <tr>
                    <td>{{ $purchase->completion_date ? \Carbon\Carbon::parse($purchase->completion_date)->format('d M Y') : '-' }}</td>
                    <td>{{ number_format($purchase->price, 0, ',', '.') }}</td>
                    <td>{{ $purchase->status }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <h2>Riwayat Pemeliharaan (Disetujui)</h2>
    @if($asset->approvedMaintenances->isEmpty())
        <p>Tidak ada data pemeliharaan disetujui.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Tanggal Pemeliharaan</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($asset->approvedMaintenances as $maintenance)
                <tr>
                    <td>{{ $maintenance->completion_date ? \Carbon\Carbon::parse($maintenance->completion_date)->format('d M Y') : '-' }}</td>
                    <td>Rp{{ number_format($maintenance->total, 0, ',', '.')   }}</td>
                    <td>{{ $maintenance->status }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

   

</body>
</html>
