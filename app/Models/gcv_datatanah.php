<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class gcv_datatanah extends Model
{
    use HasFactory, HasRoles, SoftDeletes;
        protected $filliable = [
            "no_bidang",
            "nama_pemilik_asal",
            "alas_hak",
            "luas_surat",
            "luas_ukur",
            "nop",
            "harga_jual",
            "sph",
            "notaris",
            "catatan"
        ];

    protected $casts = [
        "up_sertifikat" => 'array',
        "up_nop" => 'array',
        "up_sph" => 'array',
        "up_tambahan_lainnya" => 'array',
    ];
}
