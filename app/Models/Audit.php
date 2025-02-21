<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    use HasFactory;

    protected $fillable = [
        "siteplan",
        "type",
        "terbangun",
        "status",
        "kode1",
        "kode2",
        "kode3",
        "kode4",
        "luas1",
        "luas2",
        "luas3",
        "luas4",
        "tanda_terima_sertifikat",
        "nop_pbb_pecahan",
        "tanda_terima_nop",
        "imb_pbg",
        "tanda_terima_imb_pbg",
        "tanda_terima_tambahan"
    ];
}
