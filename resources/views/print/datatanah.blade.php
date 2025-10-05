<!DOCTYPE html>
<html>
<head>
    <title>Data Tanah</title>
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
    <h2>Data Tanah</h2>
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>No. Bidang</th>
                <th>Nama Pemilik Asal</th>
                <th>Alas Hak</th>
                <th>Luas Surat</th>
                <th>Luas Ukur</th>
                <th>NOP</th>
                <th>Harga Jual</th>
                <th>SPH</th>
                <th>Notaris</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $record)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $record->no_bidang ?? '-' }}</td>
                <td>{{ $record->nama_pemilik_asal ?? '-' }}</td>
                <td>{{ $record->alas_hak ?? '-' }}</td>
                <td>{{ $record->luas_surat ? number_format((float)$record->luas_surat, 0, ',', '.') . ' m²' : '-' }}</td>
                <td>{{ $record->luas_ukur ? number_format((float)$record->luas_ukur, 0, ',', '.') . ' m²' : '-' }}</td>
                <td>{{ $record->nop ?? '-' }}</td>
                <td>{{ $record->harga_jual ? number_format((float)$record->harga_jual, 0, ',', '.') : '-' }}</td>
                <td>{{ $record->sph ?? '-' }}</td>
                <td>{{ $record->notaris ?? '-' }}</td>
                <td>{{ $record->catatan ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="printed">
        Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
    </div>
</body>
</html>
