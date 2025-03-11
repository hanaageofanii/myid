<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class form_pajak extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id','siteplan','no_sertifikat','jenis_unit','nama_konsumen','nik','npwp','alamat',
        'nop','luas_tanah','harga','npoptkp','jumlah_bphtb','tarif_pph','jumlah_pph','kode_billing_pph',
        'tanggal_bayar_pph','ntpnpph','validasi_pph','tanggal_validasi','up_kode_bill','up_bukti_setor_pajak','up_suket_validasi'






];
}
