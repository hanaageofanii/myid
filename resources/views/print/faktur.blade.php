<!DOCTYPE html>
<html>
<head>
    <title>Data Faktur</title>
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
    <h2>Data Faktur</h2>
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Siteplan</th>
                <th>Kavling</th>
                <th>Nama Konsumen</th>
                <th>NIK</th>
                <th>NPWP</th>
                <th>Alamat</th>
                <th>No. Seri Faktur</th>
                <th>Tanggal Faktur</th>
                <th>Harga Jual</th>
                <th>DPP PPN</th>
                <th>Tarif PPN</th>
                <th>Jumlah PPN</th>
                <th>Status PPN</th>
                <th>Tanggal Bayar PPN</th>
                <th>NTPN PPN</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $record)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $record->siteplan ?? '-' }}</td>
                <td>{{ $record->kavling ?? '-' }}</td>
                <td>{{ $record->nama_konsumen ?? '-' }}</td>
                <td>{{ $record->nik ?? '-' }}</td>
                <td>{{ $record->npwp ?? '-' }}</td>
                <td>{{ $record->alamat ?? '-' }}</td>
                <td>{{ $record->no_seri_faktur ?? '-' }}</td>
                <td>
                    {{ $record->tanggal_faktur ? \Carbon\Carbon::parse($record->tanggal_faktur)->format('d-m-Y') : '-' }}
                </td>
                <td>{{ $record->harga_jual ? number_format($record->harga_jual, 0, ',', '.') : '-' }}</td>
                <td>{{ $record->dpp_ppn ? number_format($record->dpp_ppn, 0, ',', '.') : '-' }}</td>
                <td>{{ $record->tarif_ppn ?? '-' }}%</td>
                <td>{{ $record->jumlah_ppn ? number_format($record->jumlah_ppn, 0, ',', '.') : '-' }}</td>
                <td>{{ $record->status_ppn ?? '-' }}</td>
                <td>
                    {{ $record->tanggal_bayar_ppn ? \Carbon\Carbon::parse($record->tanggal_bayar_ppn)->format('d-m-Y') : '-' }}
                </td>
                <td>{{ $record->ntpn_ppn ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="printed">
        Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
    </div>
</body>
</html>
