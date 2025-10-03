<!DOCTYPE html>
<html>
<head>
    <title>Data Siteplan</title>
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
    <h2>Data Siteplan</h2>
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Siteplan</th>
                <th>Kavling</th>
                <th>Type</th>
                <th>Luas</th>
                <th>Terbangun</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $record)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $record->siteplan }}</td>
                <td>{{ ucfirst(str_replace('_',' ', $record->kavling)) }}</td>
                <td>{{ $record->type ?? '-' }}</td>
                <td>{{ $record->luas ? $record->luas . ' m²' : '-' }}</td>
                <td>{{ $record->terbangun ? 'Ya' : 'Tidak' }}</td>
                <td>{{ $record->keterangan ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="printed">
        Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
    </div>
</body>
</html>
