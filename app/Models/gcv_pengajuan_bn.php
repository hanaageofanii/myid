<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Team;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class gcv_pengajuan_bn extends Model
{
    use HasFactory, SoftDeletes, HasRoles;

    protected $fillable = [
        'team_id','kavling', 'siteplan', 'nama_konsumen', 'luas', 'harga_jual', 'tanggal_lunas', 'nop', 'nama_notaris', 'biaya_notaris',
        'pph', 'ppn', 'bphtb', 'adm_bphtb', 'catatan', 'status_bn', 'up_dokumen'
    ];

    protected $casts =[
        "up_dokumen" =>  'array',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}