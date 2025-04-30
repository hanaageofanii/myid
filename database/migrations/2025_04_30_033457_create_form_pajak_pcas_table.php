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
        Schema::create('form_pajak_pcas', function (Blueprint $table) {
            $table->id();

            $table->string('siteplan')->nullable();
            $table->string('no_sertifikat')->nullable();
            $table->enum('kavling',['standar','khusus','hook','komersil','tanah_lebih','kios'])->nullable();
            $table->string('nama_konsumen')->nullable();
            $table->string('nik')->nullable();
            $table->string('npwp')->nullable();
            $table->string('alamat')->nullable();
            $table->string('nop')->nullable();
            $table->string('luas_tanah')->nullable();
            $table->string('harga')->nullable();
            $table->string('npoptkp')->nullable();
            $table->string('jumlah_bphtb')->nullable();
            $table->enum('tarif_pph', ['1%','2,5%'])->nullable();
            $table->string('jumlah_pph')->nullable();
            $table->string('kode_billing_pph')->nullable();
            $table->date('tanggal_bayar_pph')->nullable();
            $table->string('ntpnpph')->nullable();
            $table->string('validasi_pph')->nullable();
            $table->date('tanggal_validasi')->nullable();
            

            $table->json('up_kode_billing')->nullable()->nullable();
            $table->json('up_bukti_setor_pajak')->nullable()->nullable();
            $table->json('up_suket_validasi')->nullable()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_pajak_pcas');
    }
};
