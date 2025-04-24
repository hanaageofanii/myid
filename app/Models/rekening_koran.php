<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;


class rekening_koran extends Model
{
    use HasFactory, SoftDeletes, HasRoles;

    protected $fillable = [
        'no_transaksi',
        'tanggal_mutasi',
        'keterangan_dari_bank',
        'nominal',
        'tipe',
        'saldo',
        'no_referensi_bank',
        'bank',
        'catatan',
        'up_rekening_koran',
    ];

    protected $casts = [
        "up_rekening_koran" => 'array',
    ];
}
