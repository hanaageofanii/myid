<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Team;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class gcv_verifikasi_dajam extends Model
{
    use HasFactory, SoftDeletes, HasRoles;

    protected $fillable = [
                'user_id',
        'team_id',
        'kavling',
        'siteplan',
        'bank',
        'no_debitur',
        'nama_konsumen',
        'max_kpr',
        'nilai_pencairan',
        'total_dajam',
        'dajam_sertifikat',
        'dajam_imb',
        'dajam_listrik',
        'dajam_jkk',
        'dajam_bestek',
        'jumlah_realisasi_dajam',
        'dajam_pph',
        'dajam_bphtb',
        'pembukuan',
        'no_surat_pengajuan',
        'tgl_pencairan_dajam_sertifikat',
        'tgl_pencairan_dajam_imb',
        'tgl_pencairan_dajam_listrik',
        'tgl_pencairan_dajam_jkk',
        'tgl_pencairan_dajam_bester',
        'tgl_pencairan_dajam_pph',
        'tgl_pencairan_dajam_bphtb',
        'total_pencairan_dajam',
        'sisa_dajam',
        'status_dajam',
        'catatan',
        'up_spd5',
        'up_lainnya',
    ];

    protected $casts = [
        "up_spd5" => 'array',
        "up_lainnya" => 'array'
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
