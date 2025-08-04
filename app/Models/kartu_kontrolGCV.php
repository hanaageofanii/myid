<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class kartu_kontrolGCV extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nama_proyek',
        'lokasi_proyek',
        'nama_konsumen',
        'perusahaan',
        'alamat',
        'no_telepon',
        'kavling',
        'blok',
        'type',
        'luas',
        'tanggal_booking',
        'agent',
        'catatan',
        'bank',
        'notaris',
        'tanggal_akad',
        'harga_jual',
        'harga/m',
        'pajak',
        'biaya_proses',
        'uang_muka',
        'estimasi_kpr',
        'realisasi_kpr',
        'selisih_kpr',
        'sbum&disct',
        'biaya_lain',
        'total_biaya',
        'no_konsumen',
        'tanggal_pembayaran',
        'keterangan',
        'nilai_kontrak',
        'pembayaran',
        'sisa/saldo',
        'paraf',
        'catatan',
        'bukti_lainnya',
        'status',
    ];


}
