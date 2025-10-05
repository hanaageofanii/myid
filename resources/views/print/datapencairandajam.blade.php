<!DOCTYPE html>
<html>
<head>
    <title>Data Pencairan Dajam</title>
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
    <h2>Data Pencairan Dajam</h2>
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Siteplan</th>
                <th>Kavling</th>
                <th>Bank</th>
                <th>No. Debitur</th>
                <th>Nama Konsumen</th>
                <th>Nama Dana Jaminan</th>
                <th>Nilai Dana Jaminan</th>
                <th>Tanggal Pencairan</th>
                <th>Nilai Pencairan</th>
                <th>Selisih Dana Jaminan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $record)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $record->siteplan ?? '-' }}</td>
                <td>{{ $record->kavling ?? '-' }}</td>
                <td>{{ $record->bank ?? '-' }}</td>
                <td>{{ $record->no_debitur ?? '-' }}</td>
                <td>{{ $record->nama_konsumen ?? '-' }}</td>
                <td>{{ $record->nama_dajam ?? '-' }}</td>
                <td>{{ $record->nilai_dajam ? number_format($record->nilai_dajam, 0, ',', '.') : '-' }}</td>
                <td>
                    {{ $record->tanggal_pencairan ? \Carbon\Carbon::parse($record->tanggal_pencairan)->format('d-m-Y') : '-' }}
                </td>
                <td>{{ $record->nilai_pencairan ? number_format($record->nilai_pencairan, 0, ',', '.') : '-' }}</td>
                <td>{{ $record->selisih_dajam ? number_format($record->selisih_dajam, 0, ',', '.') : '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="printed">
        Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
    </div>
</body>
</html>
