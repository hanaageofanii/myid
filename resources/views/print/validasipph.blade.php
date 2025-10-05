<!DOCTYPE html>
<html>
<head>
    <title>Data Validasi PPh</title>
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
    <h2>Data Validasi PPh</h2>
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Siteplan</th>
                <th>No. Sertifikat</th>
                <th>Kavling</th>
                <th>Nama Konsumen</th>
                <th>NIK</th>
                <th>NPWP</th>
                <th>Alamat</th>
                <th>Nama Notaris</th>
                <th>NOP</th>
                <th>Luas Tanah (m²)</th>
                <th>Harga</th>
                <th>NPOPTKP</th>
                <th>Jumlah BPHTB</th>
                <th>Tarif PPh</th>
                <th>Jumlah PPh</th>
                <th>Kode Billing PPh</th>
                <th>Tanggal Bayar PPh</th>
                <th>NTPN PPh</th>
                <th>Validasi PPh</th>
                <th>Tanggal Validasi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $record)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $record->siteplan ?? '-' }}</td>
                <td>{{ $record->no_sertifikat ?? '-' }}</td>
                <td>{{ $record->kavling ?? '-' }}</td>
                <td>{{ $record->nama_konsumen ?? '-' }}</td>
                <td>{{ $record->nik ?? '-' }}</td>
                <td>{{ $record->npwp ?? '-' }}</td>
                <td>{{ $record->alamat ?? '-' }}</td>
                <td>{{ $record->nama_notaris ?? '-' }}</td>
                <td>{{ $record->nop ?? '-' }}</td>
                <td>{{ $record->luas_tanah ?? '-' }}</td>
                <td>{{ $record->harga ? number_format($record->harga, 0, ',', '.') : '-' }}</td>
                <td>{{ $record->npoptkp ? number_format($record->npoptkp, 0, ',', '.') : '-' }}</td>
                <td>{{ $record->jumlah_bphtb ? number_format($record->jumlah_bphtb, 0, ',', '.') : '-' }}</td>
                <td>{{ $record->tarif_pph ?? '-' }}%</td>
                <td>{{ $record->jumlah_pph ? number_format($record->jumlah_pph, 0, ',', '.') : '-' }}</td>
                <td>{{ $record->kode_billing_pph ?? '-' }}</td>
                <td>
                    {{ $record->tanggal_bayar_pph ? \Carbon\Carbon::parse($record->tanggal_bayar_pph)->format('d-m-Y') : '-' }}
                </td>
                <td>{{ $record->ntpnpph ?? '-' }}</td>
                <td>{{ $record->validasi_pph ?? '-' }}</td>
                <td>
                    {{ $record->tanggal_validasi ? \Carbon\Carbon::parse($record->tanggal_validasi)->format('d-m-Y') : '-' }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="printed">
        Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
    </div>
</body>
</html>
