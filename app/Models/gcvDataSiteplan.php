<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class gcvDataSiteplan extends Model
{
    use HasFactory, SoftDeletes, HasRoles;
    protected $fillable = [
        "siteplan",
        "kavling",
        "type",
        "terbangun",
        "luas",
        "keterangan",
    ];
}
