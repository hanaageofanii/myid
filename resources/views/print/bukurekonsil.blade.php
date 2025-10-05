<!DOCTYPE html>
<html>
<head>
    <title>Data Buku Rekonsil</title>
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
    <h2>Data Buku Rekonsil</h2>
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Nama Perusahaan</th>
                <th>No. Check</th>
                <th>Tanggal Check</th>
                <th>Nama Pencair</th>
                <th>Tanggal Dicairkan</th>
                <th>Nama Penerima</th>
                <th>Account Bank</th>
                <th>Bank</th>
                <th>Jenis</th>
                <th>Rekening</th>
                <th>Deskripsi</th>
                <th>Jumlah Uang</th>
                <th>Tipe</th>
                <th>Saldo</th>
                <th>Status Disalurkan</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $record)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $record->nama_perusahaan ?? '-' }}</td>
                <td>{{ $record->no_check ?? '-' }}</td>
                <td>
                    {{ $record->tanggal_check ? \Carbon\Carbon::parse($record->tanggal_check)->format('d-m-Y') : '-' }}
                </td>
                <td>{{ $record->nama_pencair ?? '-' }}</td>
                <td>
                    {{ $record->tanggal_dicairkan ? \Carbon\Carbon::parse($record->tanggal_dicairkan)->format('d-m-Y') : '-' }}
                </td>
                <td>{{ $record->nama_penerima ?? '-' }}</td>
                <td>{{ $record->account_bank ?? '-' }}</td>
                <td>{{ $record->bank ?? '-' }}</td>
                <td>{{ $record->jenis ?? '-' }}</td>
                <td>{{ $record->rekening ?? '-' }}</td>
                <td>{{ $record->deskripsi ?? '-' }}</td>
                <td>{{ $record->jumlah_uang ? number_format((float)$record->jumlah_uang, 0, ',', '.') : '-' }}</td>
                <td>{{ $record->tipe ?? '-' }}</td>
                <td>{{ $record->saldo ? number_format((float)$record->saldo, 0, ',', '.') : '-' }}</td>
                <td>{{ $record->status_disalurkan ?? '-' }}</td>
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
