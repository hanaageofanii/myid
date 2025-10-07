<?php

namespace App\Models;

use App\Filament\Resources\GcvDataTandaTerimaResource\Widgets\gcv_datatandaterimaStats;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\GcvDataSiteplan;
use App\Models\gcv_datatandaterima ;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function gcvDataSiteplans()
    {
        return $this->hasMany(GcvDataSiteplan::class);
    }

    public function dataLegalitas()
    {
        return $this->hasMany(gcv_legalitas::class);
    }

    public function masterDajam()
    {
        return $this->hasMany(GcvMasterDajam::class);
    }

    public function gcvStok()
    {
        return $this->hasMany(gcv_stok::class);
    }

    public function gcvKpr()
    {
        return $this->hasMany(gcv_kpr::class);
    }

    public function pencairanAkad()
    {
        return $this->hasMany(gcv_pencairan_akad::class);
    }

    public function pencairanDajam()
    {
        return $this->hasMany(gcv_pencairan_dajam::class);
    }

    public function validasiPph()
    {
        return $this->hasMany(gcv_validasi_pph::class);
    }

    public function dataFaktur()
    {
        return $this->hasMany(gcv_faktur::class);
    }

    public function pengajuanBn()
    {
        return $this->hasMany(gcv_pengajuan_bn::class);
    }

    public function pengajuanDajam()
    {
        return $this->hasMany(gcv_pengajuan_dajam::class);
    }

    public function verifikasiDajam()
    {
        return $this->hasMany(gcv_verifikasi_dajam::class);
    }

    public function dataTanah()
    {
        return $this->hasMany(gcv_datatanah::class);
    }

    public function bukuRekonsil()
    {
        return $this->hasMany(buku_rekonsil::class);
    }

    public function dataRekening()
    {
        return $this->hasMany(gcv_rekening::class);
    }

    public function kasKecil()
    {
        return $this->hasMany(gcv_kaskecil::class);
    }

    public function kartuKontrol()
    {
        return $this->hasMany(kartu_kontrolGCV::class);
    }
}