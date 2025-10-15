<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Team;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class gcv_uang_muka extends Model
{
    use HasFactory, SoftDeletes, HasRoles;

    protected $fillable = [
                'user_id',
        'team_id',
        'kavling',
        'siteplan',
        'nama_konsumen',
        'harga',
        'max_kpr',
        'sbum',
        'sisa_pembayaran',
        'dp',
        'laba_rugi',
        'tanggal_terima_dp',
        'pembayaran',
        'up_kwitansi',
        'up_pricelist',
    ];

    protected $casts = [
        "up_kwitansi" => 'array',
        "up_pricelist" => 'array'
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