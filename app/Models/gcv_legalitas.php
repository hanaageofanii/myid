<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class gcv_legalitas extends Model
{
    use HasFactory, HasRoles, HasFactory;

    protected $fillable = [
        'id','siteplan','kavling','id_rumah','status_sertifikat','nib','imb_pbg','nop','nop1',
        'up_sertifikat','up_img','up_pbb','sertifikat_list'
    ];

    protected $attributes = [
        'up_sertifikat' => null,
        'up_pbb' => null,
        'up_img' => null,
        'sertifikat_list' => null,
    ];

    protected $casts =[
        "up_sertifikat" => 'array',
        "up_pbb" => 'array',
        "up_img" => 'array',
        'sertifikat_list' => 'array', //noted: ini bukan file upload
    ];
}
