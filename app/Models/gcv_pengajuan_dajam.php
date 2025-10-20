<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Team;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;


class gcv_pengajuan_dajam extends Model
{
    use HasFactory, SoftDeletes, HasRoles;

    protected $fillable =[
    'user_id',
'team_id',
    'siteplan',
    'kavling',
    'bank',
    'no_debitur',
    'nama_konsumen',
    'nama_dajam',
    'no_surat',
    'tanggal_pengajuan',
    'nilai_pencairan',
    // 'status_dajam',
    'catatan',
    'up_surat_pengajuan',
    'up_nominatif_pengajuan',
    ];

    protected $casts = [
        "up_surat_pengajuan" => 'array',
        "up_nominatif_pengajuan" => 'array'
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
