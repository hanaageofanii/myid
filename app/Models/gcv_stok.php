<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Team;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class gcv_stok extends Model
{
    use HasFactory, HasRoles, SoftDeletes;

    protected $fillable = [
        "team_id",
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
                'user_id',
        "status_sertifikat",
        "kpr_status",
        "tanggal_akad",
        "status_pembayaran",
        "ket",


    ];

     public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

        public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}