<!DOCTYPE html>
<html>
<head>
    <title>Data KPR</title>
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
    <h2>Data KPR</h2>
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Jenis Unit</th>
                <th>Siteplan</th>
                <th>Type</th>
                <th>Luas</th>
                <th>Agent</th>
                <th>Tanggal Booking</th>
                <th>Tanggal Akad</th>
                <th>Harga</th>
                <th>Maksimal KPR</th>
                <th>Nama Konsumen</th>
                <th>NIK</th>
                <th>NPWP</th>
                <th>Alamat</th>
                <th>No. HP</th>
                <th>Email</th>
                <th>Pembayaran</th>
                <th>Bank</th>
                <th>No. Rekening</th>
                <th>Status Akad</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $record)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $record->jenis_unit ?? '-' }}</td>
                <td>{{ $record->siteplan ?? '-' }}</td>
                <td>{{ $record->type ?? '-' }}</td>
                <td>{{ $record->luas ?? '-' }} m²</td>
                <td>{{ $record->agent ?? '-' }}</td>
                <td>
                    {{ $record->tanggal_booking ? \Carbon\Carbon::parse($record->tanggal_booking)->format('d-m-Y') : '-' }}
                </td>
                <td>
                    {{ $record->tanggal_akad ? \Carbon\Carbon::parse($record->tanggal_akad)->format('d-m-Y') : '-' }}
                </td>
                <td>{{ $record->harga ? number_format($record->harga, 0, ',', '.') : '-' }}</td>
                <td>{{ $record->maksimal_kpr ? number_format($record->maksimal_kpr, 0, ',', '.') : '-' }}</td>
                <td>{{ $record->nama_konsumen ?? '-' }}</td>
                <td>{{ $record->nik ?? '-' }}</td>
                <td>{{ $record->npwp ?? '-' }}</td>
                <td>{{ $record->alamat ?? '-' }}</td>
                <td>{{ $record->no_hp ?? '-' }}</td>
                <td>{{ $record->no_email ?? '-' }}</td>
                <td>{{ $record->pembayaran ?? '-' }}</td>
                <td>{{ $record->bank ?? '-' }}</td>
                <td>{{ $record->no_rekening ?? '-' }}</td>
                <td>{{ $record->status_akad ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="printed">
        Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
    </div>
</body>
</html>
