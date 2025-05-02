<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class PencairanAkadTkr extends Model
{
    use HasFactory, SoftDeletes, HasRoles;

    protected $fillable = [
        "siteplan",
        "bank",
        "nama_konsumen",
        "max_kpr",
        "tanggal_pencairan",
        "nilai_pencairan",
        "dana_jaminan",
        "up_rekening_koran",
    ];

    protected $casts = [
        "up_rekening_koran" => 'array'
    ];
}
