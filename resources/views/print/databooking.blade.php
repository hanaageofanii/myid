<!DOCTYPE html>
<html>
<head>
    <title>Data Booking</title>
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
    <h2>Data Booking</h2>
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Proyek</th>
                <th>Nama Perusahaan</th>
                <th>Kavling</th>
                <th>Siteplan</th>
                <th>Type</th>
                <th>Luas Tanah</th>
                <th>Status</th>
                <th>Tanggal Booking</th>
                <th>Nama Konsumen</th>
                <th>Agent</th>
                <th>Status Sertifikat</th>
                <th>KPR Status</th>
                <th>Tanggal Akad</th>
                <th>Status Pembayaran</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $record)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $record->proyek ?? '-' }}</td>
                <td>{{ $record->nama_perusahaan ?? '-' }}</td>
                <td>{{ $record->kavling ?? '-' }}</td>
                <td>{{ $record->siteplan ?? '-' }}</td>
                <td>{{ $record->type ?? '-' }}</td>
                <td>{{ $record->luas_tanah ?? '-' }} m²</td>
                <td>{{ $record->status ?? '-' }}</td>
                                <td>
                    {{ $record->tanggal_booking ? \Carbon\Carbon::parse($record->tanggal_booking)->format('d-m-Y') : '-' }}
                </td>
                <td>{{ $record->nama_konsumen ?? '-' }}</td>
                <td>{{ $record->agent ?? '-' }}</td>
                <td>{{ $record->status_sertifikat ?? '-' }}</td>
                <td>{{ $record->kpr_status ?? '-' }}</td>
                <td>
                    {{ $record->tanggal_akad ? \Carbon\Carbon::parse($record->tanggal_akad)->format('d-m-Y') : '-' }}
                </td>
                <td>{{ $record->status_pembayaran ?? '-' }}</td>
                <td>{{ $record->ket ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="printed">
        Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
    </div>
</body>
</html>
