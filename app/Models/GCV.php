<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;


class GCV extends Model
{
    use HasFactory, SoftDeletes, HasRoles;

    protected $fillable = [
        "proyek",
        "nama_perusahaan",
        "kavling",
        "siteplan",
        "type",
        "luas_tanah",
        "status",
        "tanggal_booking",
        "nama_konsumen",
        "agent",
        "kpr_status",
        "tanggal_akad",
        "ket",
        "user",
        "tanggal_update",

    ];

    public function audit()
    {
        return $this->belongsTo(Audit::class, 'siteplan');
    }
}
