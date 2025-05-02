<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class FormDpTkr extends Model
{
    use HasFactory, SoftDeletes, HasRoles;

    protected $fillable = [
        'siteplan',
        'nama_konsumen',
        'harga',
        'max_kpr',
        'sbum',
        'sisa_pembayaran',
        'dp',
        'laba_rugi',
        'tanggal_terima_dp',
        'pembayaran',
        'up_kwitansi',
        'up_pricelist',
    ];

    protected $casts = [
        "up_kwitansi" => 'array',
        "up_pricelist" => 'array'
    ];
}
