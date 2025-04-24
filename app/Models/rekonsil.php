<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;


class rekonsil extends Model
{
    use HasFactory, SoftDeletes, HasRoles;

    protected $fillable=[
        'no_transaksi',
        'tanggal_transaksi',
        'nama_yang_mencairkan',
        'nama_penerima',
        'tanggal_diterima',
        'bank',
        'deskripsi',
        'jumlah_uang',
        'tipe',
        'status_rekonsil',
        'catatan',

    ];
}
