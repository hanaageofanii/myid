<!DOCTYPE html>
<html>
<head>
    <title>Data Legalitas</title>
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
    <h2>Data Legalitas</h2>
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Siteplan</th>
                <th>Kavling</th>
                <th>ID Rumah</th>
                <th>Status Sertifikat</th>
                <th>NIB</th>
                <th>IMB/PBG</th>
                <th>Daftar NOP</th>
                <th>Daftar Sertifikat</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $record)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $record->siteplan ?? '-' }}</td>
                <td>{{ $record->kavling ?? '-' }}</td>
                <td>{{ $record->id_rumah ?? '-' }}</td>
                <td>{{ $record->status_sertifikat ?? '-' }}</td>
                <td>{{ $record->nib ?? '-' }}</td>
                <td>{{ $record->imb_pbg ?? '-' }}</td>
                <td>
                    @if(is_array($record->nop) && count($record->nop))
                        {{ implode(', ', array_map(fn($n) => $n['nop'] ?? '-', $record->nop)) }}
                    @else
                        -
                    @endif
                </td>
                <td>
                    @if(is_array($record->sertifikat_list) && count($record->sertifikat_list))
                        @foreach($record->sertifikat_list as $sertifikat)
                            {{ $sertifikat['kode'] ?? '-' }} ({{ $sertifikat['luas'] ?? '-' }} m²) <br>
                        @endforeach
                    @else
                        -
                    @endif
                </td>
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
