<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class form_kpr extends Model{
    use HasFactory, SoftDeletes;

    protected $table = 'form_kprs';
    
    protected $fillable = [
        'jenis_unit', 'siteplan', 'type', 'luas', 'agent', 'tanggal_booking', 'tanggal_akad',
        'harga', 'maksimal_kpr', 'nama_konsumen', 'nik', 'npwp', 'alamat', 'no_hp',
        'no_email', 'pembayaran', 'bank', 'no_rekening', 'status_akad',
        'ktp', 'kk', 'npwp_upload', 'buku_nikah', 'akte_cerai', 'akte_kematian',
        'kartu_bpjs', 'drk'
    ];
}

