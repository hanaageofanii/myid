<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class form_dp extends Model
{
    use HasFactory, SoftDeletes;

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
}
