<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('form_pajaks', function (Blueprint $table) {
            $table->id();
            $table->string('siteplan');
            $table->string('no_sertifikat');
            $table->string('jenis_unit');
            $table->string('nama_konsumen');
            $table->string('nik');
            $table->string('npwp');
            $table->string('alamat');
            $table->string('nop');
            $table->string('luas_tanah');
            $table->string('harga');
            $table->string('npoptkp');
            $table->string('jumlah_bphtb');
            $table->enum('tarif_pph', ['1%','2,5%']);
            $table->string('jumlah_pph');
            $table->string('kode_billing_pph');
            $table->date('tanggal_bayar_pph');
            $table->string('ntpnpph');
            $table->string('validasi_pph');
            $table->date('tanggal_validasi');
            $table->timestamps();


            $table->string('up_kode_billing')->nullable();
            $table->string('up_bukti_setor_pajak')->nullable();
            $table->string('up_suket_validasi')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_pajaks');
    }
};
