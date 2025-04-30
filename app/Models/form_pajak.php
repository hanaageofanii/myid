<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;


class form_pajak extends Model
{
    use HasFactory, SoftDeletes, HasRoles;

    protected $fillable = [
        'id','siteplan','no_sertifikat','kavling','nama_konsumen','nik','npwp','alamat',
        'nop','luas_tanah','harga','npoptkp','jumlah_bphtb','tarif_pph','jumlah_pph','kode_billing_pph',
        'tanggal_bayar_pph','ntpnpph','validasi_pph','tanggal_validasi','up_kode_bill','up_bukti_setor_pajak','up_suket_validasi'
    ];

    protected $casts = [
        "up_kode_bill" => 'array',
        "up_bukti_setor_pajak" => 'array',
        "up_suket_validasi" => 'array',
    ];
}
