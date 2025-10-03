<!DOCTYPE html>
<html>
<head>
    <title>Data Tanda Terima</title>
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
    <h2>Data Tanda Terima</h2>
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Siteplan</th>
                <th>Type</th>
                <th>Terbangun</th>
                <th>Kavling</th>
                <th>Status</th>
                <th>Status BN</th>
                <th>Luas</th>
                <th>Kode1</th>
                <th>Kode2</th>
                <th>Kode3</th>
                <th>Kode4</th>
                <th>Luas1</th>
                <th>Luas2</th>
                <th>Luas3</th>
                <th>Luas4</th>
                <th>Tanda Terima Sertifikat</th>
                <th>NOP PBB Pecahan</th>
                <th>Tanda Terima NOP</th>
                <th>IMB/PBG</th>
                <th>Tanda Terima IMB/PBG</th>
                <th>Tanda Terima Tambahan</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $record)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $record->siteplan }}</td>
                <td>{{ $record->type ?? '-' }}</td>
                <td>{{ $record->terbangun ? 'Ya' : 'Tidak' }}</td>
                <td>{{ $record->kavling }}</td>
                <td>{{ $record->status ?? '-' }}</td>
                <td>{{ $record->status_bn ?? '-' }}</td>
                <td>{{ $record->luas ? $record->luas . ' m²' : '-' }}</td>
                <td>{{ $record->kode1 ?? '-' }}</td>
                <td>{{ $record->kode2 ?? '-' }}</td>
                <td>{{ $record->kode3 ?? '-' }}</td>
                <td>{{ $record->kode4 ?? '-' }}</td>
                <td>{{ $record->luas1 ?? '-' }}</td>
                <td>{{ $record->luas2 ?? '-' }}</td>
                <td>{{ $record->luas3 ?? '-' }}</td>
                <td>{{ $record->luas4 ?? '-' }}</td>
                <td>{{ $record->tanda_terima_sertifikat ?? '-' }}</td>
                <td>{{ $record->nop_pbb_pecahan ?? '-' }}</td>
                <td>{{ $record->tanda_terima_nop ?? '-' }}</td>
                <td>{{ $record->imb_pbg ?? '-' }}</td>
                <td>{{ $record->tanda_terima_imb_pbg ?? '-' }}</td>
                <td>{{ $record->tanda_terima_tambahan ?? '-' }}</td>
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
