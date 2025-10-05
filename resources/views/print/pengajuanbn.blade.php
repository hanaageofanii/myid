<!DOCTYPE html>
<html>
<head>
    <title>Data Pengajuan BN</title>
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
    <h2>Data Pengajuan BN</h2>
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Kavling</th>
                <th>Siteplan</th>
                <th>Nama Konsumen</th>
                <th>Luas (m²)</th>
                <th>Harga Jual</th>
                <th>Tanggal Lunas</th>
                <th>NOP</th>
                <th>Nama Notaris</th>
                <th>Biaya Notaris</th>
                <th>PPh</th>
                <th>PPN</th>
                <th>BPHTB</th>
                <th>Adm. BPHTB</th>
                <th>Catatan</th>
                <th>Status BN</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $record)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $record->kavling ?? '-' }}</td>
                <td>{{ $record->siteplan ?? '-' }}</td>
                <td>{{ $record->nama_konsumen ?? '-' }}</td>
                <td>{{ $record->luas ?? '-' }}</td>
                <td>{{ $record->harga_jual ? number_format($record->harga_jual, 0, ',', '.') : '-' }}</td>
                <td>
                    {{ $record->tanggal_lunas ? \Carbon\Carbon::parse($record->tanggal_lunas)->format('d-m-Y') : '-' }}
                </td>
                <td>{{ $record->nop ?? '-' }}</td>
                <td>{{ $record->nama_notaris ?? '-' }}</td>
                <td>{{ $record->biaya_notaris ? number_format($record->biaya_notaris, 0, ',', '.') : '-' }}</td>
                <td>{{ $record->pph ? number_format($record->pph, 0, ',', '.') : '-' }}</td>
                <td>{{ $record->ppn ? number_format($record->ppn, 0, ',', '.') : '-' }}</td>
                <td>{{ $record->bphtb ? number_format($record->bphtb, 0, ',', '.') : '-' }}</td>
                <td>{{ $record->adm_bphtb ? number_format($record->adm_bphtb, 0, ',', '.') : '-' }}</td>
                <td>{{ $record->catatan ?? '-' }}</td>
                <td>{{ $record->status_bn ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="printed">
        Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
    </div>
</body>
</html>
