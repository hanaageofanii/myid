<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\Concerns\Has;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Team;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;


class GcvMasterDajam extends Model
{
    use HasFactory, SoftDeletes, HasRoles;

    protected $fillable = [
        'user_id',
        'team_id',
        'kavling',
        'siteplan',
        'nop',
        'nama_konsumen',
        'nik',
        'npwp',
        'alamat',
        'suket_validasi',
        'no_sspd_bphtb',
        'tanggal_sspd_bphtb',
        'no_validasi_sspd',
        'tanggal_validasi_sspd',
        'notaris',
        'no_ajb',
        'tanggal_ajb',
        'up_bast',
        'up_validasi',
    ];
protected $casts = [
        "up_bast" => 'array',
        "up_validasi" => 'array',
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
}}
