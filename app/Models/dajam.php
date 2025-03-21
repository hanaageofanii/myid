<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class dajam extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "siteplan",
        "bank",
        "no_debitur",
        "nama_konsumen",
        "max_kpr",
        "nilai_pencairan",
        "jumlah_dajam",
        "dajam_sertifikat",
        "dajam_imb",
        "dajam_listrik",
        "dajam_jkk",
        "dajam_bestek",
        "jumlah_realisasi_dajam",
        "dajam_pph",
        "dajam_bphtb",
        "pembukuan",
        "up_spd5",
        "up_lainnya",
    ];
}
