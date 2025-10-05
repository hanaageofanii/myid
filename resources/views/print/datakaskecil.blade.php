<!DOCTYPE html>
<html>
<head>
    <title>Data Kas Kecil</title>
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
    <h2>Data Kas Kecil</h2>
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Tanggal</th>
                <th>Nama Perusahaan</th>
                <th>Deskripsi</th>
                <th>Jumlah Uang</th>
                <th>Tipe</th>
                <th>Saldo</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $record)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                    {{ $record->tanggal ? \Carbon\Carbon::parse($record->tanggal)->format('d-m-Y') : '-' }}
                </td>
                <td>{{ $record->nama_perusahaan ?? '-' }}</td>
                <td>{{ $record->deskripsi ?? '-' }}</td>
                <td>{{ $record->jumlah_uang ? number_format($record->jumlah_uang, 0, ',', '.') : '-' }}</td>
                <td>{{ $record->tipe ?? '-' }}</td>
                <td>{{ $record->saldo ? number_format($record->saldo, 0, ',', '.') : '-' }}</td>
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
