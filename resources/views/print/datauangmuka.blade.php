<!DOCTYPE html>
<html>
<head>
    <title>Data Uang Muka</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: center; }
        th { background: #f2f2f2; }
        h2 { text-align: center; margin-bottom: 10px; }
        .printed { margin-top: 20px; font-style: italic; text-align: right; }
    </style>
</head>
<body onload="window.print()">
    <h2>Data Uang Muka</h2>
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Kavling</th>
                <th>Siteplan</th>
                <th>Nama Konsumen</th>
                <th>Harga</th>
                <th>Maksimal KPR</th>
                <th>SBUM</th>
                <th>Sisa Pembayaran</th>
                <th>DP</th>
                <th>Laba/Rugi</th>
                <th>Tanggal Terima DP</th>
                <th>Pembayaran</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $record)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $record->kavling ?? '-' }}</td>
                <td>{{ $record->siteplan ?? '-' }}</td>
                <td>{{ $record->nama_konsumen ?? '-' }}</td>
                <td>{{ $record->harga ? number_format($record->harga, 0, ',', '.') : '-' }}</td>
                <td>{{ $record->max_kpr ? number_format($record->max_kpr, 0, ',', '.') : '-' }}</td>
                <td>{{ $record->sbum ? number_format($record->sbum, 0, ',', '.') : '-' }}</td>
                <td>{{ $record->sisa_pembayaran ? number_format($record->sisa_pembayaran, 0, ',', '.') : '-' }}</td>
                <td>{{ $record->dp ? number_format($record->dp, 0, ',', '.') : '-' }}</td>
                <td>{{ $record->laba_rugi ? number_format($record->laba_rugi, 0, ',', '.') : '-' }}</td>
                <td>
                    {{ $record->tanggal_terima_dp ? \Carbon\Carbon::parse($record->tanggal_terima_dp)->format('d-m-Y') : '-' }}
                </td>
                <td>{{ $record->pembayaran ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="printed">
        Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
    </div>
</body>
</html>
