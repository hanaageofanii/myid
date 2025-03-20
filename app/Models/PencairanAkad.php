<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PencairanAkad extends Model
{
    use HasFactory, SoftDeletes;

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
}
