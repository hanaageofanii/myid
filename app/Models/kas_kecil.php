<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class kas_kecil extends Model
{
    use HasFactory, SoftDeletes, HasRoles;


    protected $fillable = [
        "tanggal",
        "deskripsi",
        "debit",
        "kredit",
        "keterangan",
    ];
}
