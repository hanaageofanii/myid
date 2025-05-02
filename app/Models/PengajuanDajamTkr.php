<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class PengajuanDajamTkr extends Model
{
    use HasFactory, SoftDeletes, HasRoles;

    protected $fillable =[
    'siteplan',
    'bank',
    'no_debitur',
    'nama_konsumen',
    'nama_dajam',
    'no_surat',
    'tanggal_pengajuan',
    'nilai_pencairan',
    'status_dajam',
    'up_surat_pengajuan',
    'up_nominatif_pengajuan',
    ];

    protected $casts = [
        "up_surat_pengajuan" => 'array',
        "up_nominatif_pengajuan" => 'array'
    ];
}
