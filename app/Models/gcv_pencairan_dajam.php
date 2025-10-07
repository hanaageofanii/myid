<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Team;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;


class gcv_pencairan_dajam extends Model
{
    use HasFactory, SoftDeletes, HasRoles;

    protected $fillable = [
        'team_id',
            'siteplan',
            'kavling',
            'bank',
            'no_debitur',
            'nama_konsumen',
            'nama_dajam',
            'nilai_dajam',
            'tanggal_pencairan',
            'nilai_pencairan',
            'selisih_dajam',
            'up_rekening_koran',
            'up_lainnya',
    ];

    protected $casts = [
    'up_rekening_koran' => 'array',
    'up_lainnya' => 'array',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

}
