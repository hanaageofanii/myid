<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Team;

class gcv_datatandaterima extends Model
{
   use HasFactory, SoftDeletes, HasRoles;

    protected $fillable = [
        "team_id",
        "siteplan",
        "type",
        "terbangun",
        "kavling",
        "status",
        "status_bn",
        "luas",
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
        "tanda_terima_tambahan",
        "up_sertifikat",
        "up_nop",
        "up_imb_pbg",
        "up_tambahan_lainnya",
        "keterangan",
                'user_id',




    ];

    protected $casts = [
        "up_sertifikat" => 'array',
        "up_nop" => 'array',
        "up_imb_pbg" => 'array',
        "up_tambahan_lainnya" => 'array',
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
