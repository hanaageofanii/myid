<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class gcv_faktur extends Model
{
    use HasFactory, SoftDeletes, HasRoles;

    protected $fillable = [
        "siteplan",
        "kavling",
        "nama_konsumen",
        "nik",
        "npwp",
        "alamat",
        "no_seri_faktur",
        "tanggal_faktur",
        "harga_jual",
        "dpp_ppn",
        "tarif_ppn",
        "jumlah_ppn",
        "status_ppn",
        "tanggal_bayar_ppn",
        "ntpn_ppn",
        "up_bukti_setor_ppn",
        "up_efaktur",
    ];

    protected $casts = [
        "up_bukti_setor_ppn" => 'array',
        "up_efaktur" => 'array'
    ];

}