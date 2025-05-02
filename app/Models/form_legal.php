<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;


class form_legal extends Model
{
    use HasFactory, SoftDeletes, HasRoles;

    protected $fillable = [
        'id','siteplan','nama_konsumen','id_rumah','status_sertifikat',
        'no_sertifikat','nib','luas_sertifikat','imb_pbg','nop','nop1',
        'up_sertifikat','up_img','up_pbb'
    ];

    protected $attributes = [
        'up_sertifikat' => null,
        'up_pbb' => null,
        'up_img' => null,
    ];

    protected $casts =[
        "up_sertifikat" => 'array',
        "up_pbb" => 'array',
        "up_img" => 'array'
    ];
    



}
