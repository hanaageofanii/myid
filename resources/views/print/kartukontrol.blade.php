<!DOCTYPE html>
<html>
<head>
    <title>Data Kartu Kontrol</title>
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
    <h2>Data Kartu Kontrol</h2>
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Proyek</th>
                <th>Nama Konsumen</th>
                <th>Siteplan</th>
                <th>Type</th>
                <th>Bank</th>
                <th>Tanggal Akad</th>
                <th>Harga Jual</th>
                <th>Pajak</th>
                <th>Biaya Proses</th>
                <th>Uang Muka</th>
                <th>Estimasi KPR</th>
                <th>Realisasi KPR</th>
                <th>Selisih KPR</th>
                <th>SBUM/Discount</th>
                <th>Biaya Lain</th>
                <th>Total Biaya</th>
                <th>No. Konsumen</th>
                <th>Tanggal Pembayaran</th>
                <th>Nilai Kontrak</th>
                <th>Pembayaran</th>
                <th>Sisa Saldo</th>
                <th>Paraf</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $record)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $record->proyek ?? '-' }}</td>
                <td>{{ $record->nama_konsumen ?? '-' }}</td>
                <td>{{ $record->siteplan ?? '-' }}</td>
                <td>{{ $record->type ?? '-' }}</td>
                <td>{{ $record->bank ?? '-' }}</td>
                <td>{{ $record->tanggal_akad ? \Carbon\Carbon::parse($record->tanggal_akad)->format('d-m-Y') : '-' }}</td>
                <td>{{ $record->harga_jual ? number_format((float)$record->harga_jual, 0, ',', '.') : '-' }}</td>
                <td>{{ $record->pajak ? number_format((float)$record->pajak, 0, ',', '.') : '-' }}</td>
                <td>{{ $record->biaya_proses ? number_format((float)$record->biaya_proses, 0, ',', '.') : '-' }}</td>
                <td>{{ $record->uang_muka ? number_format((float)$record->uang_muka, 0, ',', '.') : '-' }}</td>
                <td>{{ $record->estimasi_kpr ? number_format((float)$record->estimasi_kpr, 0, ',', '.') : '-' }}</td>
                <td>{{ $record->realisasi_kpr ? number_format((float)$record->realisasi_kpr, 0, ',', '.') : '-' }}</td>
                <td>{{ $record->selisih_kpr ? number_format((float)$record->selisih_kpr, 0, ',', '.') : '-' }}</td>
                <td>{{ $record->sbum_disct ? number_format((float)$record->sbum_disct, 0, ',', '.') : '-' }}</td>
                <td>{{ $record->biaya_lain ? number_format((float)$record->biaya_lain, 0, ',', '.') : '-' }}</td>
                <td>{{ $record->total_biaya ? number_format((float)$record->total_biaya, 0, ',', '.') : '-' }}</td>
                <td>{{ $record->no_konsumen ?? '-' }}</td>
                <td>{{ $record->tanggal_pembayaran ? \Carbon\Carbon::parse($record->tanggal_pembayaran)->format('d-m-Y') : '-' }}</td>
                <td>{{ $record->nilai_kontrak ? number_format((float)$record->nilai_kontrak, 0, ',', '.') : '-' }}</td>
                <td>{{ $record->pembayaran ? number_format((float)$record->pembayaran, 0, ',', '.') : '-' }}</td>
                <td>{{ $record->sisa_saldo ? number_format((float)$record->sisa_saldo, 0, ',', '.') : '-' }}</td>
                <td>{{ $record->paraf ?? '-' }}</td>
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
