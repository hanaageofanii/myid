<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class gcv_kpr extends Model
{
    use HasFactory, HasRoles, SoftDeletes;

     protected $fillable = [
        'jenis_unit', 'siteplan', 'type', 'luas', 'agent', 'tanggal_booking', 'tanggal_akad',
        'harga', 'maksimal_kpr', 'nama_konsumen', 'nik', 'npwp', 'alamat', 'no_hp',
        'no_email', 'pembayaran', 'bank', 'no_rekening', 'status_akad', 'data_diri',
        'ktp', 'kk', 'npwp_upload', 'buku_nikah', 'akte_cerai', 'akte_kematian',
        'kartu_bpjs', 'drk'
    ];


   protected $casts = [
    'data_diri' => 'array',
    'ktp' => 'boolean',
    'kk' => 'boolean',
    'npwp_upload' => 'boolean',
    'buku_nikah' => 'boolean',
    'akte_cerai' => 'boolean',
    'akte_kematian' => 'boolean',
    'kartu_bpjs' => 'boolean',
    'drk' => 'boolean',
];
}
