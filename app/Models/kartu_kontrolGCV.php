<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Team;
use Illuminate\Database\Eloquent\SoftDeletes;

class kartu_kontrolGCV extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'team_id',
        'proyek',
        'lokasi_proyek',
        'nama_konsumen',
        'nama_perusahaan',
        'alamat',
        'no_telepon',
        'kavling',
        'siteplan',
        'type',
        'luas',
        'tanggal_booking',
        'agent',
        'bank',
        'notaris',
        'tanggal_akad',
        'harga_jual',
        'harga_m',
        'pajak',
        'biaya_proses',
        'uang_muka',
        'estimasi_kpr',
        'realisasi_kpr',
        'selisih_kpr',
        'sbum_disct',
        'biaya_lain',
        'total_biaya',
        'no_konsumen',
        'tanggal_pembayaran',
        'keterangan',
        'nilai_kontrak',
        'pembayaran',
        'sisa_saldo',
        'paraf',
        'catatan',
        'bukti_lainnya',
        'status',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }




}
