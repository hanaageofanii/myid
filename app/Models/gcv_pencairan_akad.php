<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Team;
use Illuminate\Database\Eloquent\SoftDeletes;



class gcv_pencairan_akad extends Model
{
    use HasFactory, HasRoles, SoftDeletes;

    protected $fillable = [
                'user_id',
        "team_id",
            "kavling",
            "siteplan",
            "bank",
            "nama_konsumen",
            "max_kpr",
            "tanggal_pencairan",
            "nilai_pencairan",
            "dana_jaminan",
            "status_pembayaran",
            "no_debitur",
            "up_spd5",
            "up_rekening_koran",
        ];

        protected $casts = [
            "up_rekening_koran" => 'array',
            "up_spd5" => 'array'
        ];

         public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
        public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    }