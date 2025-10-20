<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Team;
class buku_rekonsil extends Model
{
    use HasFactory, SoftDeletes, HasRoles;

    protected $fillable=[
        'nama_perusahaan',
        'no_check',
        'tanggal_check',
        'nama_pencair',
        'tanggal_dicairkan',
        'nama_penerima',
        'account_bank',
        'bank',
        'jenis',
        'rekening',
        'deskripsi',
        'jumlah_uang',
        'tipe',
        'saldo',
        'status_disalurkan',
        'catatan',
        'bukti_bukti',
        'team_id'


    ];

    protected $casts = [
        'bukti_bukti' => 'array',
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