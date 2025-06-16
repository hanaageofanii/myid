<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class BukuRekonsil extends Model
{
    use HasFactory, SoftDeletes, HasRoles;

    protected $fillable=[
        'nama_perusahaan',
        'no_check',
        'tanggal_check',
        'nama_pencair',
        'tanggal_dicairkan',
        'nama_penerima',
        'account_bank',
        'bank',
        'jenis',
        'rekening',
        'deskripsi',
        'jumlah_uang',
        'tipe',
        'saldo',
        'status_disalurkan',
        'catatan',
        'bukti_bukti',


    ];

    protected $casts = [
        'bukti_bukti' => 'array',
    ];
    


}
