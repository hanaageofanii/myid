<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Team;

class gcv_datatanah extends Model
{
    use HasFactory, HasRoles, SoftDeletes;

        protected $fillable = [
            "no_bidang",
            "team_id",
            "nama_pemilik_asal",
            "alas_hak",
            "luas_surat",
            "luas_ukur",
            "nop",
            "harga_jual",
            "sph",
            "notaris",
            "catatan",
            "up_sertifikat",
            "up_nop",
            "data_diri",
            "up_sph",
            "up_tambahan_lainnya"
        ];

    protected $casts = [
        "up_sertifikat" => 'array',
        "up_nop" => 'array',
        "data_diri" => 'array',
        "up_sph" => 'array',
        "up_tambahan_lainnya" => 'array',
    ];
public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}