<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;


class gcv_rekening extends Model
{
    use HasFactory, SoftDeletes, HasRoles;

    protected $fillable=[
        'nama_perusahaan',
        'bank',
        'jenis',
        'rekening',
    ];
}
