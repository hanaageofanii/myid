<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Team;

class gcv_faktur extends Model
{
    use HasFactory, SoftDeletes, HasRoles;

    protected $fillable = [
        "team_id",
        "siteplan",
        "kavling",
        "nama_konsumen",
        "nik",
        "npwp",
        "alamat",
        "no_seri_faktur",
        "tanggal_faktur",
        "harga_jual",
        "dpp_ppn",
        "tarif_ppn",
        "jumlah_ppn",
        "status_ppn",
        "tanggal_bayar_ppn",
        "ntpn_ppn",
        "up_bukti_setor_ppn",
        "up_efaktur",
                'user_id',

    ];

    protected $casts = [
        "up_bukti_setor_ppn" => 'array',
        "up_efaktur" => 'array'
    ];

     public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
{
    static::creating(function ($model) {
        if (! $model->user_id) {
            $model->user_id = filament()->auth()->id();
        }

        if (! $model->team_id) {
            $model->team_id = filament()->getTenant()?->id;
        }
    });
}

}
