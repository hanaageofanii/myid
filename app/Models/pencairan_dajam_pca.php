<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class pencairan_dajam_pca extends Model
{
    use HasFactory, SoftDeletes, HasRoles;

    protected $fillable = [
            'siteplan',
            'bank',
            'no_debitur',
            'nama_konsumen',
            'nama_dajam',
            'nilai_dajam',
            'tanggal_pencairan',
            'nilai_pencairan',
            'selisih_dajam',
            'up_rekening_koran',
            'up_lainnya',
    ];

    protected $casts =[
        "up_rekening_korang" => 'array',
        "up_lainnya" => 'array'
    ];
}
