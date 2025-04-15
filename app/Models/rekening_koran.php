<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class rekening_koran extends Model
{
    use HasFactory, SoftDeletes;

    protected $filiable = [
        'no_transaksi',
        'tanggal_mutasi',
        'ket_dari_bank',
        'nominal',
        'tipe',
        'saldo',
        'no_refrensi_bank',
        'bank',
        'catatan',
        'up_rekening_koran',
    ];
}
