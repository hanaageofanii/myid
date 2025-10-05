<!DOCTYPE html>
<html>
<head>
    <title>Data Verifikasi Dana Jaminan</title>
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
    <h2>Data Verifikasi Dana Jaminan</h2>
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Kavling</th>
                <th>Siteplan</th>
                <th>Bank</th>
                <th>No. Debitur</th>
                <th>Nama Konsumen</th>
                <th>Maksimal KPR</th>
                <th>Nilai Pencairan</th>
                <th>Total Dajam</th>
                <th>Dajam Sertifikat</th>
                <th>Dajam IMB</th>
                <th>Dajam Listrik</th>
                <th>Dajam JKK</th>
                <th>Dajam Bestek</th>
                <th>Jumlah Realisasi Dajam</th>
                <th>Dajam PPh</th>
                <th>Dajam BPHTB</th>
                <th>Pembukuan</th>
                <th>No. Surat Pengajuan</th>
                <th>Tgl. Cair Sertifikat</th>
                <th>Tgl. Cair IMB</th>
                <th>Tgl. Cair Listrik</th>
                <th>Tgl. Cair JKK</th>
                <th>Tgl. Cair Bestek</th>
                <th>Tgl. Cair PPh</th>
                <th>Tgl. Cair BPHTB</th>
                <th>Total Pencairan Dajam</th>
                <th>Sisa Dajam</th>
                <th>Status Dajam</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $record)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $record->kavling ?? '-' }}</td>
                <td>{{ $record->siteplan ?? '-' }}</td>
                <td>{{ $record->bank ?? '-' }}</td>
                <td>{{ $record->no_debitur ?? '-' }}</td>
                <td>{{ $record->nama_konsumen ?? '-' }}</td>
                <td>{{ $record->max_kpr ? number_format($record->max_kpr, 0, ',', '.') : '-' }}</td>
                <td>{{ $record->nilai_pencairan ? number_format($record->nilai_pencairan, 0, ',', '.') : '-' }}</td>
                <td>{{ $record->total_dajam ? number_format($record->total_dajam, 0, ',', '.') : '-' }}</td>
                <td>{{ $record->dajam_sertifikat ? number_format($record->dajam_sertifikat, 0, ',', '.') : '-' }}</td>
                <td>{{ $record->dajam_imb ? number_format($record->dajam_imb, 0, ',', '.') : '-' }}</td>
                <td>{{ $record->dajam_listrik ? number_format($record->dajam_listrik, 0, ',', '.') : '-' }}</td>
                <td>{{ $record->dajam_jkk ? number_format($record->dajam_jkk, 0, ',', '.') : '-' }}</td>
                <td>{{ $record->dajam_bestek ? number_format($record->dajam_bestek, 0, ',', '.') : '-' }}</td>
                <td>{{ $record->jumlah_realisasi_dajam ? number_format($record->jumlah_realisasi_dajam, 0, ',', '.') : '-' }}</td>
                <td>{{ $record->dajam_pph ? number_format($record->dajam_pph, 0, ',', '.') : '-' }}</td>
                <td>{{ $record->dajam_bphtb ? number_format($record->dajam_bphtb, 0, ',', '.') : '-' }}</td>
                <td>{{ $record->pembukuan ?? '-' }}</td>
                <td>{{ $record->no_surat_pengajuan ?? '-' }}</td>
                <td>{{ $record->tgl_pencairan_dajam_sertifikat ? \Carbon\Carbon::parse($record->tgl_pencairan_dajam_sertifikat)->format('d-m-Y') : '-' }}</td>
                <td>{{ $record->tgl_pencairan_dajam_imb ? \Carbon\Carbon::parse($record->tgl_pencairan_dajam_imb)->format('d-m-Y') : '-' }}</td>
                <td>{{ $record->tgl_pencairan_dajam_listrik ? \Carbon\Carbon::parse($record->tgl_pencairan_dajam_listrik)->format('d-m-Y') : '-' }}</td>
                <td>{{ $record->tgl_pencairan_dajam_jkk ? \Carbon\Carbon::parse($record->tgl_pencairan_dajam_jkk)->format('d-m-Y') : '-' }}</td>
                <td>{{ $record->tgl_pencairan_dajam_bester ? \Carbon\Carbon::parse($record->tgl_pencairan_dajam_bester)->format('d-m-Y') : '-' }}</td>
                <td>{{ $record->tgl_pencairan_dajam_pph ? \Carbon\Carbon::parse($record->tgl_pencairan_dajam_pph)->format('d-m-Y') : '-' }}</td>
                <td>{{ $record->tgl_pencairan_dajam_bphtb ? \Carbon\Carbon::parse($record->tgl_pencairan_dajam_bphtb)->format('d-m-Y') : '-' }}</td>
                <td>{{ $record->total_pencairan_dajam ? number_format($record->total_pencairan_dajam, 0, ',', '.') : '-' }}</td>
                <td>{{ $record->sisa_dajam ? number_format($record->sisa_dajam, 0, ',', '.') : '-' }}</td>
                <td>{{ $record->status_dajam ?? '-' }}</td>
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
