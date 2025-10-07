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
}
