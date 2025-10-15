<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Team;

class gcv_kaskecil extends Model
{
    use HasFactory, SoftDeletes, HasRoles;


    protected $fillable = [
        "tanggal",
        "team_id",
        "nama_perusahaan",
        "deskripsi",
        "jumlah_uang",
        "tipe",
        "saldo",
        "catatan",
        "bukti",
                'user_id',

    ];

    protected $casts = [
        'bukti' => 'array',
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