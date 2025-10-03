<!DOCTYPE html>
<html>
<head>
    <title>Data AJB</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: center; }
        th { background: #f2f2f2; }
        h2 { text-align: center; margin-bottom: 10px; }
    </style>
</head>
<body onload="window.print()">
    <h2>Data AJB</h2>
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Kavling</th>
                <th>Siteplan</th>
                <th>NOP</th>
                <th>Nama Konsumen</th>
                <th>NIK</th>
                <th>NPWP</th>
                <th>Alamat</th>
                <th>No. Suket Validasi</th>
                <th>No. SSPD BPHTB</th>
                <th>Tanggal SSPD BPHTB</th>
                <th>No. Validasi SSPD</th>
                <th>Tanggal Validasi SSPD</th>
                <th>Notaris</th>
                <th>No. AJB</th>
                <th>Tanggal AJB</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $record)
            <tr>
                <td>{{ $loop->iteration }}</td> {{-- nomor urut otomatis --}}
                <td>{{ $record->kavling }}</td>
                <td>{{ $record->siteplan }}</td>
                <td>{{ $record->nop }}</td>
                <td>{{ $record->nama_konsumen }}</td>
                <td>{{ $record->nik }}</td>
                <td>{{ $record->npwp }}</td>
                <td>{{ $record->alamat }}</td>
                <td>{{ $record->suket_validasi }}</td>
                <td>{{ $record->no_sspd_bphtb }}</td>
                <td>{{ \Carbon\Carbon::parse($record->tanggal_sspd_bphtb)->format('d-m-Y') }}</td>
                <td>{{ $record->no_validasi_sspd }}</td>
                <td>{{ \Carbon\Carbon::parse($record->tanggal_validasi_sspd)->format('d-m-Y') }}</td>
                <td>{{ $record->notaris }}</td>
                <td>{{ $record->no_ajb }}</td>
                <td>{{ \Carbon\Carbon::parse($record->tanggal_ajb)->format('d-m-Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
