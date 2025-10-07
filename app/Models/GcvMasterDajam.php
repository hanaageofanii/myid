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


}
