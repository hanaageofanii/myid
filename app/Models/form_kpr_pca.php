<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

use Illuminate\Database\Eloquent\SoftDeletes;


class form_kpr_pca extends Model
{
    use HasFactory, SoftDeletes, HasRoles;
    
    protected $fillable = [
        'jenis_unit', 'siteplan', 'type', 'luas', 'agent', 'tanggal_booking', 'tanggal_akad',
        'harga', 'maksimal_kpr', 'nama_konsumen', 'nik', 'npwp', 'alamat', 'no_hp',
        'no_email', 'pembayaran', 'bank', 'no_rekening', 'status_akad',
        'ktp', 'kk', 'npwp_upload', 'buku_nikah', 'akte_cerai', 'akte_kematian',
        'kartu_bpjs', 'drk'
    ];

    protected $casts = [
        "ktp" => 'array',
        "kk" => 'array',
        "npwp_upload" => 'array',
        "buku_nikah" => 'array',
        "akte_cerai" => 'array',
        "akte_kematian" => 'array',
        "kartu_bpjs" => 'array',
        "drk" => 'array',

    ];
}


