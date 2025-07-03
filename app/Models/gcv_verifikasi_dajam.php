<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class gcv_verifikasi_dajam extends Model
{
    use HasFactory, SoftDeletes, HasRoles;

    protected $fillable = [
        'kavling',
        'siteplan',
        'bank',
        'no_debitur',
        'nama_konsumen',
        'max_kpr',
        'nilai_pencairan',
        'total_dajam',
        'dajam_sertifikat',
        'dajam_imb',
        'dajam_listrik',
        'dajam_jkk',
        'dajam_bestek',
        'jumlah_realisasi_dajam',
        'dajam_pph',
        'dajam_bphtb',
        'pembukuan',
        'no_surat_pengajuan',
        'tgl_pencairan_dajam_sertifikat',
        'tgl_pencairan_dajam_imb',
        'tgl_pencairan_dajam_listrik',
        'tgl_pencairan_dajam_jkk',
        'tgl_pencairan_dajam_bester',
        'tgl_pencairan_dajam_pph',
        'tgl_pencairan_dajam_bphtb',
        'total_pencairan_dajam',
        'sisa_dajam',
        'status_dajam',
        'catatan',
        'up_spd5',
        'up_lainnya',
    ];

    protected $casts = [
        "up_spd5" => 'array',
        "up_lainnya" => 'array'
    ];
}