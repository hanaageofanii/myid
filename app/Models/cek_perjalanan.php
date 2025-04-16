<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class cek_perjalanan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        
            'no_ref_bank',            
            'no_transaksi',            
            'nama_pencair',            
            'tanggal_dicairkan',
            'nama_penerima',            
            'tanggal_diterima',            
            'tujuan_dana',
            'status_disalurkan',        
            'bukti_pendukung'
    ];

    protected $casts = [
        "bukti_pendukung" => 'array',
    ];
}
